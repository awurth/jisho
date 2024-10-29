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
        $context['groups'][] = $object->answeredAt instanceof DateTimeImmutable
            ? 'question:answered:read'
            : 'question:unanswered:read';

        return $context;
    }

    #[Override]
    protected function getName(): string
    {
        return 'QUESTION';
    }
}
