<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 * @UniqueEntity("name")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Item
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $type;

    /**
     * @ORM\Column(type="integer", options={"default": "1"})
     */
    private int $amount = 1;

    /**
     * @ORM\Column(type="string", length=255, options={"default": "Goed"})
     */
    private string $status = 'Goed';

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateBought;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $value;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $depreciation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $manufacturer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $location;

    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private User $createdBy;

    /**
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private User $updatedBy;

    /**
     * @ORM\OneToMany(targetEntity=Loan::class, mappedBy="loanedItem")
     */
    private Collection $loan;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="item")
     */
    private Collection $notes;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="items")
     */
    private $tags;

    public function __construct()
    {
        $this->loan = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $amount = empty($amount) ? 1 : $amount;

        $this->amount = $amount;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDateBought(): ?\DateTimeInterface
    {
        return $this->dateBought;
    }

    public function setDateBought(\DateTimeInterface $dateBought): self
    {
        $this->dateBought = $dateBought;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getDepreciation(): ?float
    {
        return $this->depreciation;
    }

    public function setDepreciation(?float $depreciation): self
    {
        $this->depreciation = $depreciation;

        return $this;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?string $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection|Loan[]
     */
    public function getLoan(): Collection
    {
        return $this->loan;
    }

    public function addLoan(Loan $loan): self
    {
        if (!$this->loan->contains($loan)) {
            $this->loan[] = $loan;
            $loan->setLoanedItem($this);
        }

        return $this;
    }

    public function removeLoan(Loan $loan): self
    {
        if ($this->loan->removeElement($loan)) {
            // set the owning side to null (unless already changed)
            if ($loan->getLoanedItem() === $this) {
                $loan->setLoanedItem(null);
            }
        }

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
            $note->setItem($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        $this->notes->removeElement($note);

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }
}
