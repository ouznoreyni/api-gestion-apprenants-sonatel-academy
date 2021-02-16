<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PromoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      routePrefix="/admin",
 *      normalizationContext={
 *          "groups"={"promo:read"}
 *      },
 *      denormalizationContext={
 *          "groups"={"promo:write"}
 *      },
 *      collectionOperations={
 *          "GET",
 *           "post"={"path"="/promos","deserialize"=false},
 *      },
 *      itemOperations={"GET", "PUT", "DELETE"}
 * )
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 */
class Promo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"promo:read", "promo:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"promo:read", "promo:write"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"promo:read", "promo:write"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"promo:read", "promo:write"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"promo:read", "promo:write"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"promo:read", "promo:write"})
     */
    private $referenceAgate;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"promo:read", "promo:write"})
     */
    private $fabrique;

    /**
     * @ORM\Column(type="date")
     *
     * @Groups({"promo:read", "promo:write"})
     */
    private $dateStarting;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"promo:read", "promo:write"})
     */
    private $archiver;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promos")
     *
     * @Groups({"promo:read", "promo:write"})
     */
    private $groupes;

    /**
     * @ORM\Column(type="blob", nullable=true)
     *
     * @Groups({"promo:read", "promo:write"})
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, inversedBy="promos")
     *
     * @Groups({"promo:read", "promo:write","ref:read"})
     */
    private $referentiels;

    public function __construct()
    {
        $this->setArchiver(false);
        $this->groupes = new ArrayCollection();
        $this->referentiels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getReferenceAgate(): ?string
    {
        return $this->referenceAgate;
    }

    public function setReferenceAgate(string $referenceAgate): self
    {
        $this->referenceAgate = $referenceAgate;

        return $this;
    }

    public function getFabrique(): ?string
    {
        return $this->fabrique;
    }

    public function setFabrique(string $fabrique): self
    {
        $this->fabrique = $fabrique;

        return $this;
    }

    public function getDateStarting(): ?\DateTimeInterface
    {
        return $this->dateStarting;
    }

    public function setDateStarting($dateStarting): self
    {
        $this->dateStarting = new \DateTime($dateStarting);

        return $this;
    }

    public function getArchiver(): ?bool
    {
        return $this->archiver;
    }

    public function setArchiver(bool $archiver): self
    {
        $this->archiver = $archiver;

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->setPromos($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getPromos() === $this) {
                $groupe->setPromos(null);
            }
        }

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Referentiel[]
     */
    public function getReferentiels(): Collection
    {
        return $this->referentiels;
    }

    public function addReferentiel(Referentiel $referentiel): self
    {
        if (!$this->referentiels->contains($referentiel)) {
            $this->referentiels[] = $referentiel;
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        $this->referentiels->removeElement($referentiel);

        return $this;
    }
}
