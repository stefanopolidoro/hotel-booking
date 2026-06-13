<?php

declare(strict_types=1);

namespace App\Controllers;

use Src\Core\BaseController;
use App\Models\Room;

class RoomController extends BaseController
{
    private Room $room;

    public function __construct()
    {
        $this->room = new Room();
    }

    public function index(): void
    {
        $checkIn  = trim($_GET['check_in']  ?? '');
        $checkOut = trim($_GET['check_out'] ?? '');
        $guests   = (int) ($_GET['guests']  ?? 1);

        $searchPerformed = $checkIn !== '' && $checkOut !== '';
        $error           = null;
        $rooms           = [];
        $searchParams    = [];

        if ($searchPerformed) {
            $searchParams = compact('checkIn', 'checkOut', 'guests');

            if ($checkOut <= $checkIn) {
                $error = 'La data di check-out deve essere successiva al check-in.';
            } else {
                $rooms = $this->room->findAvailable($checkIn, $checkOut, $guests);
            }
        } else {
            $rooms = $this->room->allActive();
        }

        $this->render('rooms/index', [
            'pageTitle'       => 'Camere',
            'rooms'           => $rooms,
            'searchPerformed' => $searchPerformed,
            'searchParams'    => $searchParams,
            'error'           => $error,
        ]);
    }

    public function show(string $id): void
    {
        $room = $this->room->find((int) $id);

        if ($room === null || !$room['is_active']) {
            $this->abort(404, "Camera {$id} non trovata");
        }

        $amenities = $this->room->getAmenities($room);

        $checkIn  = trim($_GET['check_in']  ?? '');
        $checkOut = trim($_GET['check_out'] ?? '');
        $guests   = (int) ($_GET['guests']  ?? 1);

        $this->render('rooms/show', [
            'pageTitle' => e($room['name']),
            'room'      => $room,
            'amenities' => $amenities,
            'checkIn'   => $checkIn,
            'checkOut'  => $checkOut,
            'guests'    => $guests,
        ]);
    }
}