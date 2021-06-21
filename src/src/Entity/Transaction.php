<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TransactionRepository;
use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Transactions
 *
 * @ORM\Table(name="transactions")
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Transaction
{
    /**
     * @Groups({"user-list", "transaction-list"})
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @Groups({"user-list", "transaction-list"})
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     */
    private string $code;

    /**
     * @Groups({"user-list", "transaction-list"})
     * @ORM\Column(name="amount", type="string", length=255, nullable=false)
     */
    private string $amount;

    /**
     * @Groups({"user-list", "transaction-list"})
     * @ORM\Column(name="user_id", type="integer", nullable=false, options={"unsigned"=true})
     */
    private int $userId;

    /**
     * @Groups({"user-list", "transaction-list"})
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private ?DateTimeInterface $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
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

    /**
     * @ORM\PrePersist()
     */
    public function beforeSave(): void
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
