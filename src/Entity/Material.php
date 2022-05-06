<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\MaterialRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=MaterialRepository::class)
 * @UniqueEntity("name")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Vich\Uploadable()
 */
class Material
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    public const STATES = ['Goed', 'Matig', 'Slecht', 'Afgeschreven'];

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
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $information;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $category;

    /**
     * @ORM\Column(type="integer", options={"default": "1"})
     * @Assert\Positive
     */
    private int $amount = 1;

    /**
     * @ORM\Column(type="string", length=255, options={"default": "Goed"})
     */
    private string $state = 'Goed';

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
    private ?float $residualValue = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(min = 0)
     */
    private ?int $depreciationYears;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $image = null;

    /**
     * @Vich\UploadableField(mapping="materials", fileNameProperty="image")
     */
    private ?File $imageFile = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $receipt = null;

    /**
     * @Vich\UploadableField(mapping="receipts", fileNameProperty="receipt")
     */
    private ?File $receiptFile = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $manufacturer;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="materials", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private ?Location $location;

    /**
     * @ORM\OneToMany(targetEntity=Loan::class, mappedBy="loanedMaterial")
     */
    private Collection $loan;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="material")
     */
    private Collection $notes;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="materials", cascade={"persist"})
     */
    private Collection $tags;

    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private ?User $createdBy;

    /**
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private ?User $updatedBy;

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

    public function getName(): string
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

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(?string $information): self
    {
        $this->information = $information;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

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

    public function getResidualValue(): ?float
    {
        return $this->residualValue;
    }

    public function setResidualValue(?float $residualValue): self
    {
        $this->residualValue = $residualValue;

        return $this;
    }

    public function getDepreciationYears(): ?int
    {
        return $this->depreciationYears;
    }

    public function setDepreciationYears(?int $depreciationYears): self
    {
        $this->depreciationYears = $depreciationYears;

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

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
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
            $loan->setLoanedMaterial($this);
        }

        return $this;
    }

    public function removeLoan(Loan $loan): self
    {
        if ($this->loan->removeElement($loan)) {
            // set the owning side to null (unless already changed)
            if ($loan->getLoanedMaterial() === $this) {
                $loan->setLoanedMaterial(null);
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
            $note->setMaterial($this);
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

    public function getCurrentValue(): ?float
    {
        if ($this->depreciationYears === 0) { // no current value from the start
            return 0;
        }

        if ($this->depreciationYears === null) { // never depreciating
            return $this->value;
        }

        $expiredYears = $this->dateBought->diff(new DateTime('now'))->y;

        return max(0, max(0, $this->value - $this->residualValue) * max(0, 1 - ($expiredYears / $this->depreciationYears)) + $this->residualValue);
    }

    public function isDeprecated(): bool
    {
        $expiredYears = $this->dateBought->diff(new DateTime('now'))->y;

        return $expiredYears > $this->depreciationYears;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile($imageFile): self
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            $this->updatedAt = new DateTime();
        }

        return $this;
    }

    public function getReceipt(): ?string
    {
        return $this->receipt;
    }

    public function setReceipt(?string $receipt): self
    {
        $this->receipt = $receipt;

        return $this;
    }

    public function getReceiptFile()
    {
        return $this->receiptFile;
    }

    public function setReceiptFile($receiptFile): self
    {
        $this->receiptFile = $receiptFile;

        if ($receiptFile) {
            $this->updatedAt = new DateTime();
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
