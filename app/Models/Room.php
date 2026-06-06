<?php

declare(strict_types=1);

namespace App\Models;

use Src\Core\BaseModel;

class Room extends BaseModel
{
    protected string $table = 'rooms';

    protected array $fillable = [
        'name',
        'description',
        'price_per_night',
        'capacity',
        'size_sqm',
        'amenities',
        'image',
        'is_active',
    ];

    public function findAvailable(
        string $checkIn,
        string $checkOut,
        int $guests = 1
    ): array {
        $sql = "
            SELECT *
            FROM rooms
            WHERE is_active = 1
              AND capacity >= ?
              AND id NOT IN (
                  SELECT room_id
                  FROM bookings
                  WHERE status != 'cancelled'
                    AND check_in  < ?
                    AND check_out > ?
              )
            ORDER BY price_per_night ASC
        ";

        return $this->db->query($sql, [$guests, $checkOut, $checkIn])->fetchAll();
    }

    public function allActive(): array
    {
        return $this->db
            ->query("SELECT * FROM rooms WHERE is_active = 1 ORDER BY price_per_night ASC")
            ->fetchAll();
    }

    public function getAmenities(array $room): array
    {
        if (empty($room['amenities'])) {
            return [];
        }

        return array_map(
            'trim',
            explode(',', $room['amenities'])
        );
    }
}