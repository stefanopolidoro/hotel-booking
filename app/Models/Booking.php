<?php

declare(strict_types=1);

namespace App\Models;

use Src\Core\BaseModel;

class Booking extends BaseModel
{
    protected string $table = 'bookings';

    protected array $fillable = [
        'room_id',
        'token',
        'first_name',
        'last_name',
        'email',
        'phone',
        'check_in',
        'check_out',
        'guests',
        'total_price',
        'status',
        'notes',
    ];

    public function findByToken(string $token): ?array
    {
        return $this->findBy('token', $token);
    }

    public function generateToken(): string
    {
        return bin2hex(random_bytes(16));
    }

    public function calculateTotal(float $pricePerNight, string $checkIn, string $checkOut): float
    {
        $nights = nights_between($checkIn, $checkOut);
        return round($pricePerNight * $nights, 2);
    }

    public function findWithRoom(int $id): ?array
    {
        $result = $this->db->query("
            SELECT
                b.*,
                r.name           AS room_name,
                r.image          AS room_image,
                r.price_per_night AS room_price
            FROM bookings b
            JOIN rooms r ON r.id = b.room_id
            WHERE b.id = ?
            LIMIT 1
        ", [$id])->fetch();

        return $result ?: null;
    }

    public function findByTokenWithRoom(string $token): ?array
    {
        $result = $this->db->query("
            SELECT
                b.*,
                r.name           AS room_name,
                r.image          AS room_image,
                r.price_per_night AS room_price
            FROM bookings b
            JOIN rooms r ON r.id = b.room_id
            WHERE b.token = ?
            LIMIT 1
        ", [$token])->fetch();

        return $result ?: null;
    }

    public function updateStatus(int $id, string $status): bool
    {
        $allowed = ['pending', 'confirmed', 'cancelled'];

        if (!in_array($status, $allowed, true)) {
            return false;
        }

        return $this->update($id, ['status' => $status]);
    }

    public function getStats(): array
    {
        $result = $this->db->query("
            SELECT
                COUNT(*)                                            AS total,
                SUM(status = 'pending')                            AS pending,
                SUM(status = 'confirmed')                          AS confirmed,
                SUM(status = 'cancelled')                          AS cancelled,
                SUM(MONTH(created_at) = MONTH(CURRENT_DATE())
                    AND YEAR(created_at) = YEAR(CURRENT_DATE()))   AS this_month,
                COALESCE(
                    SUM(CASE
                        WHEN status = 'confirmed'
                         AND MONTH(created_at) = MONTH(CURRENT_DATE())
                         AND YEAR(created_at)  = YEAR(CURRENT_DATE())
                        THEN total_price
                    END), 0
                )                                                   AS monthly_revenue
            FROM bookings
        ")->fetch();

        return $result ?: [];
    }

    public function allWithRoom(): array
    {
        return $this->db->query("
            SELECT
                b.*,
                r.name AS room_name
            FROM bookings b
            JOIN rooms r ON r.id = b.room_id
            ORDER BY b.created_at DESC
        ")->fetchAll();
    }
}