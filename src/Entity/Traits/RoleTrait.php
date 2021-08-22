<?php

namespace Cooolinho\Bundle\SecurityBundle\Entity\Traits;

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
        return array_unique($this->roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = array_values(array_unique($roles));

        return $this;
    }

    public function addRole(string $role): self
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles(), true);
    }

    public function removeRole(string $role): self
    {
        if (in_array($role, $this->roles, true)) {
            unset($this->roles[array_search($role, $this->roles, true)]);
        }

        return $this;
    }
}
