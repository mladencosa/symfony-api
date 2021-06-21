<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CountryRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Country
 *
 * @ORM\Table(name="countries")
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 */
class Country
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @Groups("user-list")
     * @ORM\Column(name="name", type="string", length=63, nullable=false)
     */
    private string $name;

    /**
     * @Groups("user-list")
     * @ORM\Column(name="iso2", type="string", length=2, nullable=false)
     */
    private string $iso2;

    /**
     * @Groups("user-list")
     * @ORM\Column(name="iso3", type="string", length=3, nullable=true)
     */
    private ?string $iso3;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIso2(): ?string
    {
        return $this->iso2;
    }

    public function setIso2(string $iso2): self
    {
        $this->iso2 = $iso2;

        return $this;
    }

    public function getIso3(): ?string
    {
        return $this->iso3;
    }

    public function setIso3(?string $iso3): self
    {
        $this->iso3 = $iso3;

        return $this;
    }
}
