<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Src\Core\BaseController;
use App\Models\Booking;
use App\Models\Room;

class DashboardController extends BaseController
{
    private Booking $booking;
    private Room    $room;

    public function __construct()
    {
        $this->booking = new Booking();
        $this->room    = new Room();
    }

    public function index(): void
    {
        $this->requireAuth();

        $stats         = $this->booking->getStats();
        $latestBookings = $this->getLatestBookings();
        $totalRooms    = count($this->room->all());
        $activeRooms   = count($this->room->allActive());

        $this->render('admin/dashboard', [
            'pageTitle'      => 'Dashboard',
            'stats'          => $stats,
            'latestBookings' => $latestBookings,
            'totalRooms'     => $totalRooms,
            'activeRooms'    => $activeRooms,
        ], 'admin');
    }

    private function getLatestBookings(): array
    {
        return $this->booking->allWithRoom();
    }

    protected function requireAuth(): void
    {
        if (empty($_SESSION[ADMIN_SESSION_KEY])) {
            $this->redirect(url('/admin/login'));
        }
    }
}