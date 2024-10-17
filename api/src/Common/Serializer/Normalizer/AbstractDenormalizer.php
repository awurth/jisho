<?php

declare(strict_types=1);

namespace App\Common\Serializer\Normalizer;

use Override;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * @template T
 */
abstract class AbstractDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    /**
     * @param array<string, mixed> $context
     */
    abstract public function supports(mixed $data, string $type, ?string $format = null, array $context = []): bool;

    abstract protected function getName(): string;

    #[Override]
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        if (isset($context[$this->getAlreadyCalledKey()])) {
            return false;
        }

        return $this->supports($data, $type, $format, $context);
    }

    #[Override]
    public function getSupportedTypes(?string $format): array
    {
        return $this->denormalizer->getSupportedTypes($format);
    }

    #[Override]
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $context = $this->updateContext($data, $type, $format, $context);

        $context[$this->getAlreadyCalledKey()] = true;

        $object = $this->denormalizer->denormalize($data, $type, $format, $context);

        return $this->updateObject($object);
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    protected function updateContext(mixed $data, string $type, ?string $format = null, array $context = []): array
    {
        return $context;
    }

    /**
     * @param T $object
     */
    protected function updateObject(mixed $object): mixed
    {
        return $object;
    }

    private function getAlreadyCalledKey(): string
    {
        return $this->getName().'_DENORMALIZER_ALREADY_CALLED';
    }
}
