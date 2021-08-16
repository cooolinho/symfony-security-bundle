<?php

namespace Cooolinho\Bundle\SecurityBundle\Entity;

use Cooolinho\Bundle\SecurityBundle\Entity\Constant\Role;
use Cooolinho\Bundle\SecurityBundle\Entity\Traits\CredentialsTrait;
use Cooolinho\Bundle\SecurityBundle\Entity\Traits\EmailTrait;
use Cooolinho\Bundle\SecurityBundle\Entity\Traits\RoleTrait;
use Cooolinho\Bundle\SecurityBundle\Entity\Traits\TimestampTrait;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

abstract class AbstractUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    use EmailTrait, CredentialsTrait, RoleTrait, TimestampTrait;

    public const CHOICE_ROLE = [
        'security.user.super_admin' => Role::SUPER_ADMIN,
        'security.user.admin' => Role::ADMIN,
        'security.user.label' => Role::USER,
    ];

    public function __construct()
    {
        $this->addRole(Role::USER);
        $this->updatedTimestamps();
    }

    abstract public function getId(): ?int;

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
