<?php

declare(strict_types=1);

namespace App\Quiz\Exception;

use RuntimeException;
use Throwable;

final class QuestionAlreadyAnsweredException extends RuntimeException
{
    public function __construct(string $message = 'Question already answered.', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
