<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Src\Core\BaseController;

class AuthController extends BaseController
{
    public function loginForm(): void
    {
        if ($this->isLoggedIn()) {
            $this->redirect(url('/admin/dashboard'));
        }

        $this->render('admin/login', [
            'pageTitle' => 'Accesso admin',
            'error'     => $_SESSION['login_error'] ?? null,
        ], 'admin_blank');

        unset($_SESSION['login_error']);
    }

    public function login(): void
    {
        $this->verifyCsrf();

        $post     = $this->getPost();
        $email    = $post['email']    ?? '';
        $password = $post['password'] ?? '';

        if (
            $email === ADMIN_EMAIL &&
            password_verify($password, ADMIN_PASSWORD)
        ) {
            session_regenerate_id(true);

            $_SESSION[ADMIN_SESSION_KEY] = true;
            $_SESSION['admin_email']     = $email;
            $_SESSION['admin_logged_at'] = time();

            $this->redirect(url('/admin/dashboard'));
        }

        $_SESSION['login_error'] = 'Credenziali non valide. Riprova.';
        $this->redirect(url('/admin/login'));
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        $this->redirect(url('/admin/login'));
    }

    protected function isLoggedIn(): bool
    {
        return !empty($_SESSION[ADMIN_SESSION_KEY]);
    }
}