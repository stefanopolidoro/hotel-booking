<?php

declare(strict_types=1);

namespace Src\Exceptions;

class NotFoundException extends HttpException
{
    public function __construct(string $message = 'Pagina non trovata')
    {
        parent::__construct(404, $message);
    }
}