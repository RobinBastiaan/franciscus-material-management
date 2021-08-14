<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\LoanRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=LoanRepository::class)
 */
class Loan
{
    use TimestampableEntity;

    const RETURN_STATUSES = ['available', 'lent', 'to_repair', 'irreparable', 'lost'];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private DateTimeInterface $dateReturned;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $returnedStatus;

    /**
     * @ORM\ManyToOne(targetEntity=Material::class, inversedBy="loan")
     * @ORM\JoinColumn(nullable=false)
     */
    private Material $loanedMaterial;

    /**
     * @ORM\ManyToOne(targetEntity=Reservation::class, inversedBy="loans")
     */
    private Reservation $reservation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateReturned(): ?DateTimeInterface
    {
        return $this->dateReturned;
    }

    public function setDateReturned(?DateTimeInterface $dateReturned): self
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

    public function getLoanedMaterial(): ?Material
    {
        return $this->loanedMaterial;
    }

    public function setLoanedMaterial(?Material $loanedMaterial): self
    {
        $this->loanedMaterial = $loanedMaterial;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): self
    {
        $this->reservation = $reservation;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getId();
    }
}
