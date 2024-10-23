<?php

declare(strict_types=1);

namespace App\Quiz\Exception;

use RuntimeException;
use Throwable;

final class QuizEndedException extends RuntimeException
{
    public function __construct(string $message = 'Quiz already ended.', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
