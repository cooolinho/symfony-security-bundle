<?php

namespace Cooolinho\Bundle\SecurityBundle\Entity\Traits;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

trait BirthdayTrait
{
    /**
     * @ORM\Column(type="date")
     */
    protected DateTimeInterface $birthday;

    public function getBirthday(): ?DateTimeInterface
    {
        return $this->birthday;
    }
}
