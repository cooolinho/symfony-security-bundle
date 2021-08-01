<?php

namespace Cooolinho\Bundle\SecurityBundle\Entity;

use Cooolinho\Bundle\SecurityBundle\Entity\Traits\CredentialsTrait;
use Cooolinho\Bundle\SecurityBundle\Entity\Traits\EmailTrait;
use Cooolinho\Bundle\SecurityBundle\Entity\Traits\RoleTrait;
use Cooolinho\Bundle\SecurityBundle\Entity\Traits\TimestampTrait;
use Cooolinho\Bundle\SecurityBundle\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use EmailTrait, CredentialsTrait, RoleTrait, TimestampTrait;

    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_USER = 'ROLE_USER';

    public const CHOICE_ROLE = [
        'security.user.super_admin' => self::ROLE_SUPER_ADMIN,
        'security.user.admin' => self::ROLE_ADMIN,
        'security.user.label' => self::ROLE_USER,
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    public function __construct()
    {
        $this->addRole(self::ROLE_USER);
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
