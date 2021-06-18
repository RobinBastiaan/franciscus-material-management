<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\LoanRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LoanRepository::class)
 */
class Loan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateReturned;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $returnedStatus;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="loan")
     * @ORM\JoinColumn(nullable=false)
     */
    private $loanedItem;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="loans")
     */
    private $OrderId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateReturned(): ?\DateTimeInterface
    {
        return $this->dateReturned;
    }

    public function setDateReturned(?\DateTimeInterface $dateReturned): self
    {
        $this->dateReturned = $dateReturned;

        return $this;
    }

    public function getReturnedStatus(): ?string
    {
        return $this->returnedStatus;
    }

    public function setReturnedStatus(?string $returnedStatus): self
    {
        $this->returnedStatus = $returnedStatus;

        return $this;
    }

    public function getLoanedItem(): ?Item
    {
        return $this->loanedItem;
    }

    public function setLoanedItem(?Item $loanedItem): self
    {
        $this->loanedItem = $loanedItem;

        return $this;
    }

    public function getOrderId(): ?Order
    {
        return $this->OrderId;
    }

    public function setOrderId(?Order $OrderId): self
    {
        $this->OrderId = $OrderId;

        return $this;
    }
}
