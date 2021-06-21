<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class User
{
    /**
     * @Groups("user-list")
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @Groups("user-list")
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private string $email;

    /**
     * @Groups("user-list")
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private bool $active;

    /**
     * @Groups("user-list")
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private ?DateTimeInterface $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updatedAt;

    /**
     * @Groups("user-list")
     * @ORM\OneToOne(targetEntity=UserDetails::class, mappedBy="user", fetch="EAGER")
     */
    private ?UserDetails $userDetails;

    /**
     * @Groups("user-list")
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="user", fetch="EAGER", cascade={"remove"})
     */
    private Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUserDetails(): ?UserDetails
    {
        return $this->userDetails;
    }

    public function setUserDetails(?UserDetails $userDetails): self
    {
        // unset the owning side of the relation if necessary
        if ($userDetails === null && $this->userDetails !== null) {
            $this->userDetails->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($userDetails !== null && $userDetails->getUser() !== $this) {
            $userDetails->setUser($this);
        }

        $this->userDetails = $userDetails;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function beforeSave(): void
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function beforeUpdate(): void
    {
        $this->updatedAt = new DateTime();
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setUser($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getUser() === $this) {
                $transaction->setUser(null);
            }
        }

        return $this;
    }
}
