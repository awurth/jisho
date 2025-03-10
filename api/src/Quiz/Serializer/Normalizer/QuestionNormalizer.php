<?php

declare(strict_types=1);

namespace App\Quiz\Serializer\Normalizer;

use App\Common\Serializer\Normalizer\AbstractNormalizer;
use App\Quiz\ApiResource\Question;
use DateTimeImmutable;
use Override;

/**
 * @extends AbstractNormalizer<Question>
 */
final class QuestionNormalizer extends AbstractNormalizer
{
    #[Override]
    public function supports(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Question;
    }

    #[Override]
    protected function updateContext(mixed $object, ?string $format = null, array $context = []): array
    {
        if ($object->answeredAt instanceof DateTimeImmutable) {
            $context['groups'][] = 'question:answered:read';
        }

        if ($object->skippedAt instanceof DateTimeImmutable) {
            $context['groups'][] = 'question:skipped:read';
        }

        return $context;
    }

    #[Override]
    protected function getName(): string
    {
        return 'QUESTION';
    }
}
