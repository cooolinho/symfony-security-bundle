<?php

namespace Cooolinho\Bundle\SecurityBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait NameTrait
{
    /**
     * @ORM\Column(type="string", length=50)
     */
    protected string $firstname;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected string $lastname;

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFullname(): string
    {
        return implode(' ', [$this->getFirstname(), $this->getLastname()]);
    }
}
