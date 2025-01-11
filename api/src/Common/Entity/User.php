<?php

declare(strict_types=1);

namespace App\Common\Entity;

use App\Common\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Override;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use function array_unique;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface
{
    #[Id]
    #[Column(type: 'uuid')]
    protected(set) Uuid $id;

    /**
     * @var non-empty-string
     */
    #[Column(length: 180, unique: true)]
    public string $email;

    /**
     * @var list<string>
     */
    #[Column]
    public array $roles = [];

    #[Column]
    public string $name;

    #[Column]
    public string $avatarUrl = '';

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    #[Override]
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    #[Override]
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @see UserInterface
     */
    #[Override]
    public function eraseCredentials(): void
    {
    }
}
