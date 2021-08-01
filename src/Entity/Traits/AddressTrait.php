<?php

namespace Cooolinho\Bundle\SecurityBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait AddressTrait
{
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected ?string $street = null;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    protected ?string $streetNr = null;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    protected ?string $zip = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected ?string $city = null;

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string|null $street
     * @return $this
     */
    public function setStreet(?string $street): self
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStreetNr(): ?string
    {
        return $this->streetNr;
    }

    /**
     * @param string|null $streetNr
     * @return $this
     */
    public function setStreetNr(?string $streetNr): self
    {
        $this->streetNr = $streetNr;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * @param string|null $zip
     * @return $this
     */
    public function setZip(?string $zip): self
    {
        $this->zip = $zip;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     * @return $this
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }
}
