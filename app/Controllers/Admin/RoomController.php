<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Src\Core\BaseController;
use App\Models\Room;

class RoomController extends BaseController
{
    private Room $room;

    private const ALLOWED_MIME = ['image/jpeg', 'image/png', 'image/webp'];
    private const MAX_SIZE     = 2 * 1024 * 1024; // 2 MB in byte
    private const UPLOAD_DIR   = '/public/assets/img/rooms/';

    public function __construct()
    {
        $this->room = new Room();
    }

    public function index(): void
    {
        $this->requireAuth();

        $rooms = $this->room->all();

        $this->render('admin/rooms/index', [
            'pageTitle' => 'Gestione camere',
            'rooms'     => $rooms,
        ], 'admin');
    }

    public function create(): void
    {
        $this->requireAuth();

        $this->render('admin/rooms/form', [
            'pageTitle' => 'Nuova camera',
            'room'      => null,
            'errors'    => $_SESSION['form_errors'] ?? [],
            'old'       => $_SESSION['old_input']   ?? [],
        ], 'admin');

        unset($_SESSION['form_errors'], $_SESSION['old_input']);
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $post   = $this->getPost();
        $errors = $this->validate($post);

        $imageName = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->handleUpload($_FILES['image']);
            if (is_string($uploadResult)) {
                $errors['image'] = $uploadResult;
            } else {
                $imageName = $uploadResult;
            }
        }

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['old_input']   = $post;
            $this->redirect(url('/admin/rooms/create'));
        }

        $this->room->create([
            'name'            => $post['name'],
            'description'     => $post['description'],
            'price_per_night' => (float) $post['price_per_night'],
            'capacity'        => (int)   $post['capacity'],
            'size_sqm'        => (float) $post['size_sqm'],
            'amenities'       => $post['amenities'] ?? '',
            'image'           => $imageName,
            'is_active'       => isset($post['is_active']) ? 1 : 0,
        ]);

        flash('success', 'Camera creata con successo.');
        $this->redirect(url('/admin/rooms'));
    }

    public function edit(string $id): void
    {
        $this->requireAuth();

        $room = $this->room->find((int) $id);
        if ($room === null) {
            $this->abort(404, 'Camera non trovata');
        }

        $this->render('admin/rooms/form', [
            'pageTitle' => 'Modifica — ' . $room['name'],
            'room'      => $room,
            'errors'    => $_SESSION['form_errors'] ?? [],
            'old'       => $_SESSION['old_input']   ?? [],
        ], 'admin');

        unset($_SESSION['form_errors'], $_SESSION['old_input']);
    }

    public function update(string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $room = $this->room->find((int) $id);
        if ($room === null) {
            $this->abort(404, 'Camera non trovata');
        }

        $post   = $this->getPost();
        $errors = $this->validate($post);

        $imageName = $room['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->handleUpload($_FILES['image']);
            if (is_string($uploadResult)) {
                $errors['image'] = $uploadResult;
            } else {
                $this->deleteImage($room['image']);
                $imageName = $uploadResult;
            }
        }

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['old_input']   = $post;
            $this->redirect(url('/admin/rooms/' . $id . '/edit'));
        }

        $this->room->update((int) $id, [
            'name'            => $post['name'],
            'description'     => $post['description'],
            'price_per_night' => (float) $post['price_per_night'],
            'capacity'        => (int)   $post['capacity'],
            'size_sqm'        => (float) $post['size_sqm'],
            'amenities'       => $post['amenities'] ?? '',
            'image'           => $imageName,
            'is_active'       => isset($post['is_active']) ? 1 : 0,
        ]);

        flash('success', 'Camera aggiornata con successo.');
        $this->redirect(url('/admin/rooms'));
    }

    public function delete(string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $room = $this->room->find((int) $id);
        if ($room === null) {
            $this->abort(404, 'Camera non trovata');
        }

        $this->deleteImage($room['image']);
        $this->room->delete((int) $id);

        flash('success', 'Camera eliminata con successo.');
        $this->redirect(url('/admin/rooms'));
    }

    private function validate(array $post): array
    {
        $errors = [];

        if (empty($post['name'])) {
            $errors['name'] = 'Il nome è obbligatorio.';
        } elseif (mb_strlen($post['name']) > 100) {
            $errors['name'] = 'Il nome non può superare 100 caratteri.';
        }

        if (empty($post['description'])) {
            $errors['description'] = 'La descrizione è obbligatoria.';
        }

        if (!isset($post['price_per_night']) || $post['price_per_night'] === '') {
            $errors['price_per_night'] = 'Il prezzo è obbligatorio.';
        } elseif ((float) $post['price_per_night'] <= 0) {
            $errors['price_per_night'] = 'Il prezzo deve essere maggiore di zero.';
        }

        if (!isset($post['capacity']) || (int) $post['capacity'] < 1) {
            $errors['capacity'] = 'La capacità minima è 1 ospite.';
        }

        if (!isset($post['size_sqm']) || (float) $post['size_sqm'] <= 0) {
            $errors['size_sqm'] = 'La metratura deve essere maggiore di zero.';
        }

        return $errors;
    }

    private function handleUpload(array $file): string|array
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return 'Errore durante il caricamento del file.';
        }

        if ($file['size'] > self::MAX_SIZE) {
            return 'L\'immagine non può superare 2 MB.';
        }

        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, self::ALLOWED_MIME, true)) {
            return 'Formato non supportato. Usa JPG, PNG o WebP.';
        }

        $extension = match($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
        };

        $filename  = bin2hex(random_bytes(8)) . '.' . $extension;
        $destPath  = ROOT_PATH . self::UPLOAD_DIR . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            return 'Impossibile salvare il file. Controlla i permessi della cartella.';
        }

        return ['filename' => $filename];
    }

    private function deleteImage(?string $imageName): void
    {
        if (empty($imageName)) {
            return;
        }

        $path = ROOT_PATH . self::UPLOAD_DIR . $imageName;
        if (file_exists($path)) {
            unlink($path);
        }
    }
}