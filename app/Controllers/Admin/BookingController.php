<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Src\Core\BaseController;
use App\Models\Booking;

class BookingController extends BaseController
{
    private Booking $booking;

    public function __construct()
    {
        $this->booking = new Booking();
    }

    public function index(): void
    {
        $this->requireAuth();

        $status = trim($_GET['status'] ?? '');
        $search = trim($_GET['search'] ?? '');

        $bookings = $this->getFiltered($status, $search);

        $this->render('admin/bookings/index', [
            'pageTitle' => 'Prenotazioni',
            'bookings'  => $bookings,
            'status'    => $status,
            'search'    => $search,
        ], 'admin');
    }

    public function show(string $id): void
    {
        $this->requireAuth();

        $booking = $this->booking->findWithRoom((int) $id);

        if ($booking === null) {
            $this->abort(404, 'Prenotazione non trovata');
        }

        $nights = nights_between($booking['check_in'], $booking['check_out']);

        $this->render('admin/bookings/show', [
            'pageTitle' => 'Prenotazione #' . $id,
            'booking'   => $booking,
            'nights'    => $nights,
        ], 'admin');
    }

    public function updateStatus(string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $booking = $this->booking->find((int) $id);

        if ($booking === null) {
            $this->abort(404, 'Prenotazione non trovata');
        }

        $newStatus = trim($_POST['status'] ?? '');
        $updated   = $this->booking->updateStatus((int) $id, $newStatus);

        if ($updated) {
            flash('success', 'Stato aggiornato con successo.');
        } else {
            flash('error', 'Stato non valido o nessuna modifica effettuata.');
        }

        $this->redirect(url('/admin/bookings/' . $id));
    }

    private function getFiltered(string $status, string $search): array
    {
        $all = $this->booking->allWithRoom();

        return array_filter($all, function (array $b) use ($status, $search): bool {
            if ($status !== '' && $b['status'] !== $status) {
                return false;
            }

            if ($search !== '') {
                $haystack = mb_strtolower(
                    $b['first_name'] . ' ' .
                    $b['last_name']  . ' ' .
                    $b['email']      . ' ' .
                    $b['token']
                );
                if (!str_contains($haystack, mb_strtolower($search))) {
                    return false;
                }
            }

            return true;
        });
    }
}