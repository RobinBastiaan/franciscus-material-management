<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\LoanRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=LoanRepository::class)
 */
class Loan
{
    use TimestampableEntity;

    const STATES = ['Goed', 'Matig', 'Slecht', 'Afgeschreven'];

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
    private string $returnedState;

    /**
     * @ORM\ManyToOne(targetEntity=Material::class, inversedBy="loan")
     * @ORM\JoinColumn(nullable=false)
     */
    private Material $loanedMaterial;

    /**
     * @ORM\ManyToOne(targetEntity=Reservation::class, inversedBy="loans")
     */
    private Reservation $reservation;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="loan")
     */
    private ?Collection $notes;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

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

    public function getReturnedState(): ?string
    {
        return $this->returnedState;
    }

    public function setReturnedState(?string $returnedState): self
    {
        $this->returnedState = $returnedState;

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

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setLoan($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getLoan() === $this) {
                $note->setLoan(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->getId();
    }
}
