<?php

namespace App\Entity;

use App\Repository\AnnouncementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnnouncementRepository::class)]
class Announcement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $creation_date = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $cat_name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $cat_birth = null;

    #[ORM\Column]
    private ?bool $cat_loof = null;

    #[ORM\ManyToOne(inversedBy: 'announcements')]
    private ?CatGender $cat_gender = null;

    #[ORM\ManyToOne(inversedBy: 'announcements')]
    private ?User $user = null;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'announcement')]
    private Collection $photos;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->creation_date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCatName(): ?string
    {
        return $this->cat_name;
    }

    public function setCatName(string $cat_name): static
    {
        $this->cat_name = $cat_name;

        return $this;
    }

    public function getCatBirth(): ?\DateTimeInterface
    {
        return $this->cat_birth;
    }

    public function setCatBirth(\DateTimeInterface $cat_birth): static
    {
        $this->cat_birth = $cat_birth;

        return $this;
    }

    public function isCatLoof(): ?bool
    {
        return $this->cat_loof;
    }

    public function setCatLoof(bool $cat_loof): static
    {
        $this->cat_loof = $cat_loof;

        return $this;
    }

    public function getCatGender(): ?CatGender
    {
        return $this->cat_gender;
    }

    public function setCatGender(?CatGender $cat_gender): static
    {
        $this->cat_gender = $cat_gender;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setAnnouncement($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getAnnouncement() === $this) {
                $photo->setAnnouncement(null);
            }
        }

        return $this;
    }
}
