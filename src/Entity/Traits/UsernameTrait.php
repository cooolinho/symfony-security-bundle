<?php

namespace Cooolinho\Bundle\SecurityBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait UsernameTrait
{
    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    protected string $username = '';

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }
}
