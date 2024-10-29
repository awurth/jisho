<?php

declare(strict_types=1);

namespace App\Common\Serializer\Normalizer;

use ArrayObject;
use Override;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @template T
 */
abstract class AbstractNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @param array<string, mixed> $context
     */
    abstract public function supports(mixed $data, ?string $format = null, array $context = []): bool;

    abstract protected function getName(): string;

    #[Override]
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if (isset($context[$this->getAlreadyCalledKey()])) {
            return false;
        }

        return $this->supports($data, $format, $context);
    }

    #[Override]
    public function getSupportedTypes(?string $format): array
    {
        return $this->normalizer->getSupportedTypes($format);
    }

    #[Override]
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|ArrayObject|null
    {
        $context = $this->updateContext($object, $format, $context);

        $context[$this->getAlreadyCalledKey()] = true;

        $data = $this->normalizer->normalize($object, $format, $context);

        return $this->updateData($data);
    }

    /**
     * @param T                    $object
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    protected function updateContext(mixed $object, ?string $format = null, array $context = []): array
    {
        return $context;
    }

    protected function updateData(mixed $data): mixed
    {
        return $data;
    }

    private function getAlreadyCalledKey(): string
    {
        return $this->getName().'_NORMALIZER_ALREADY_CALLED';
    }
}
