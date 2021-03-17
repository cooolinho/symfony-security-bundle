<?php

namespace Cooolinho\Bundle\SecurityBundle\Entity\Traits;

use Cooolinho\Bundle\SecurityBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

trait RoleTrait
{
    /**
     * @ORM\Column(type="json")
     */
    public array $roles = [];

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = User::ROLE_USER;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): self
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }
}
