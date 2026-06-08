<?php

declare(strict_types=1);

namespace App\Controllers;

use Src\Core\BaseController;
use App\Models\Room;

class HomeController extends BaseController
{
    private Room $room;

    public function __construct()
    {
        $this->room = new Room();
    }

    public function index(): void
    {
        $searchPerformed = false;
        $rooms           = [];
        $searchParams    = [];

        $checkIn  = trim($_GET['check_in']  ?? '');
        $checkOut = trim($_GET['check_out'] ?? '');
        $guests   = (int) ($_GET['guests']  ?? 1);

        if ($checkIn !== '' && $checkOut !== '') {
            $searchPerformed = true;
            $searchParams    = compact('checkIn', 'checkOut', 'guests');

            if ($checkOut <= $checkIn) {
                $error = 'La data di check-out deve essere successiva al check-in.';
            } else {
                $rooms = $this->room->findAvailable($checkIn, $checkOut, $guests);
            }
        }

        $this->render('home/index', [
            'pageTitle'       => 'Benvenuto',
            'searchPerformed' => $searchPerformed,
            'searchParams'    => $searchParams,
            'rooms'           => $rooms,
            'error'           => $error ?? null,
        ]);
    }
}