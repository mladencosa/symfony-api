<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserDetailsRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * UserDetails
 *
 * @ORM\Table(name="user_details")
 * @ORM\Entity(repositoryClass=UserDetailsRepository::class)
 */
class UserDetails
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private int $userId;

    /**
     * @Groups({"user-list", "user-detail"})
     * @ORM\Column(name="citizenship_country_id", type="integer", nullable=false)
     */
    private int $citizenshipId;

    /**
     * @Groups({"user-list", "user-detail"})
     * @ORM\Column(name="first_name", type="string", length=255, nullable=false)
     */
    private string $firstName;

    /**
     * @Groups({"user-list", "user-detail"})
     * @ORM\Column(name="last_name", type="string", length=255, nullable=false)
     */
    private string $lastName;

    /**
     * @Groups({"user-list", "user-detail"})
     * @ORM\Column(name="phone_number", type="string", length=255, nullable=false)
     */
    private string $phoneNumber;

    /**
     * @Groups("user-list")
     * @ORM\ManyToOne(targetEntity=Country::class, fetch="EAGER")
     * @ORM\JoinColumn(nullable=false, name="citizenship_country_id", referencedColumnName="id")
     */
    private ?Country $country;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="userDetails")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private ?User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getCitizenshipId(): ?int
    {
        return $this->citizenshipId;
    }

    public function setCitizenshipId(int $citizenshipId): self
    {
        $this->citizenshipId = $citizenshipId;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
