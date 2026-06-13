<?php

declare(strict_types=1);

namespace App\Controllers;

use Src\Core\BaseController;
use App\Models\Room;
use App\Models\Booking;

class BookingController extends BaseController
{
    private Room    $room;
    private Booking $booking;

    public function __construct()
    {
        $this->room    = new Room();
        $this->booking = new Booking();
    }

    public function create(): void
    {
        $roomId   = (int) ($_GET['room_id']   ?? 0);
        $checkIn  = trim($_GET['check_in']    ?? '');
        $checkOut = trim($_GET['check_out']   ?? '');
        $guests   = (int) ($_GET['guests']    ?? 1);

        $room = $this->room->find($roomId);

        if ($room === null || !$room['is_active']) {
            $this->abort(404, 'Camera non trovata');
        }

        if ($checkIn === '' || $checkOut === '' || $checkOut <= $checkIn) {
            flash('error', 'Date non valide. Seleziona il periodo di soggiorno.');
            $this->redirect(url('/rooms/' . $roomId));
        }

        $available = $this->room->findAvailable($checkIn, $checkOut, $guests);
        $isAvailable = array_filter(
            $available,
            fn($r) => (int) $r['id'] === $roomId
        );

        if (empty($isAvailable)) {
            flash('error', 'La camera non è disponibile per le date selezionate.');
            $this->redirect(url('/rooms/' . $roomId));
        }

        $nights = nights_between($checkIn, $checkOut);
        $total  = $this->booking->calculateTotal(
            (float) $room['price_per_night'],
            $checkIn,
            $checkOut
        );

        $this->render('booking/create', [
            'pageTitle' => 'Prenota — ' . $room['name'],
            'room'      => $room,
            'checkIn'   => $checkIn,
            'checkOut'  => $checkOut,
            'guests'    => $guests,
            'nights'    => $nights,
            'total'     => $total,
            'old'       => $_SESSION['old_input'] ?? [],
            'errors'    => $_SESSION['form_errors'] ?? [],
        ]);

        unset($_SESSION['old_input'], $_SESSION['form_errors']);
    }

    public function store(): void
    {
        $this->verifyCsrf();

        $post = $this->getPost();

        $roomId   = (int) ($post['room_id']   ?? 0);
        $checkIn  = $post['check_in']          ?? '';
        $checkOut = $post['check_out']          ?? '';
        $guests   = (int) ($post['guests']     ?? 1);

        $room = $this->room->find($roomId);

        if ($room === null || !$room['is_active']) {
            $this->abort(404, 'Camera non trovata');
        }

        $errors = $this->validate($post, $room);

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['old_input']   = $post;
            $this->redirect(
                url('/booking/create?' . http_build_query([
                    'room_id'   => $roomId,
                    'check_in'  => $checkIn,
                    'check_out' => $checkOut,
                    'guests'    => $guests,
                ]))
            );
        }

        $available = $this->room->findAvailable($checkIn, $checkOut, $guests);
        $isAvailable = array_filter(
            $available,
            fn($r) => (int) $r['id'] === $roomId
        );

        if (empty($isAvailable)) {
            $_SESSION['form_errors'] = [
                'general' => 'La camera non è più disponibile per le date selezionate. Scegli date diverse.'
            ];
            $_SESSION['old_input'] = $post;
            $this->redirect(
                url('/booking/create?' . http_build_query([
                    'room_id'   => $roomId,
                    'check_in'  => $checkIn,
                    'check_out' => $checkOut,
                    'guests'    => $guests,
                ]))
            );
        }

        $total = $this->booking->calculateTotal(
            (float) $room['price_per_night'],
            $checkIn,
            $checkOut
        );

        $token = $this->booking->generateToken();

        $this->booking->create([
            'room_id'     => $roomId,
            'token'       => $token,
            'first_name'  => $post['first_name'],
            'last_name'   => $post['last_name'],
            'email'       => $post['email'],
            'phone'       => $post['phone'] ?? '',
            'check_in'    => $checkIn,
            'check_out'   => $checkOut,
            'guests'      => $guests,
            'total_price' => $total,
            'status'      => 'pending',
            'notes'       => $post['notes'] ?? '',
        ]);

        unset($_SESSION['old_input'], $_SESSION['form_errors']);

        $this->redirect(url('/booking/confirm/' . $token));
    }

    public function confirm(string $token): void
    {
        $booking = $this->booking->findByTokenWithRoom($token);

        if ($booking === null) {
            $this->abort(404, 'Prenotazione non trovata');
        }

        $this->render('booking/confirm', [
            'pageTitle' => 'Prenotazione confermata',
            'booking'   => $booking,
            'nights'    => nights_between($booking['check_in'], $booking['check_out']),
        ]);
    }

    private function validate(array $post, array $room): array
    {
        $errors = [];

        if (empty($post['first_name'])) {
            $errors['first_name'] = 'Il nome è obbligatorio.';
        } elseif (mb_strlen($post['first_name']) > 100) {
            $errors['first_name'] = 'Il nome non può superare 100 caratteri.';
        }

        if (empty($post['last_name'])) {
            $errors['last_name'] = 'Il cognome è obbligatorio.';
        } elseif (mb_strlen($post['last_name']) > 100) {
            $errors['last_name'] = 'Il cognome non può superare 100 caratteri.';
        }

        if (empty($post['email'])) {
            $errors['email'] = "L'email è obbligatoria.";
        } elseif (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "L'indirizzo email non è valido.";
        }

        if (!empty($post['phone']) && !preg_match('/^[\d\s\+\-\(\)]{6,20}$/', $post['phone'])) {
            $errors['phone'] = 'Il numero di telefono non è valido.';
        }

        $checkIn  = $post['check_in']  ?? '';
        $checkOut = $post['check_out'] ?? '';

        if (empty($checkIn)) {
            $errors['check_in'] = 'La data di check-in è obbligatoria.';
        } elseif ($checkIn < date('Y-m-d')) {
            $errors['check_in'] = 'La data di check-in non può essere nel passato.';
        }

        if (empty($checkOut)) {
            $errors['check_out'] = 'La data di check-out è obbligatoria.';
        } elseif ($checkOut <= $checkIn) {
            $errors['check_out'] = 'Il check-out deve essere successivo al check-in.';
        }

        $guests = (int) ($post['guests'] ?? 1);
        if ($guests < 1 || $guests > (int) $room['capacity']) {
            $errors['guests'] = "Il numero di ospiti deve essere tra 1 e {$room['capacity']}.";
        }

        return $errors;
    }
}