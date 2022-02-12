<?php

namespace Cooolinho\Bundle\SecurityBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait CredentialsTrait
{
    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    protected string $password;
    protected ?string $plainPassword = null;

    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }
}
