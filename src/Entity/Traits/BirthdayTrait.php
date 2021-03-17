<?php

namespace Cooolinho\Bundle\SecurityBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

trait BirthdayTrait
{
    /**
     * @ORM\Column(type="date")
     */
    private DateTimeInterface $birthday;

    public function getBirthday(): ?DateTimeInterface
    {
        return $this->birthday;
    }
}
