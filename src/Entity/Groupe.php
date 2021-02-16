<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *       normalizationContext={"groups"={"grp:read"}},
 *      denormalizationContext={"groups"={"grp:write"}},
 *       routePrefix="/admin",
 *      collectionOperations={
 *          "POST",
 *      "get_all_groups"={
 *              "method"="GET",
 *              "path"="/groupes"
 *             },
 *         "get_apprenant_have_groups"={
 *              "method"="GET",
 *              "path"="/groupes/apprenants",
 *              "normalization_context"={"groups"=      {"apprenant:read"}},
 *
 *             }
 *      },
 *     itemOperations={"GET", "PUT",
 *      "DELETE"={
 *          "path"="/groupes/{id_groupe}/apprenants/{id_apprenant}"
 *      }}
 * )
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 */
class Groupe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"grp:read", "grp:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"grp:read", "grp:write"})
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"grp:read", "grp:write"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"grp:read", "grp:write"})
     */
    private $archiver;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="groupes")
     *
     * @Groups({"grp:read", "grp:write", "promo:red"})
     */
    private $promos;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="groupe")
     *
     * @Groups({"grp:read", "grp:write", "apprenant:read"})
     */
    private $apprenants;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
        $this->setCreatedAt(new \DateTime());
        $this->setArchiver(false);

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getPromos(): ?Promo
    {
        return $this->promos;
    }

    public function setPromos(?Promo $promos): self
    {
        $this->promos = $promos;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->setGroupe($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getGroupe() === $this) {
                $apprenant->setGroupe(null);
            }
        }

        return $this;
    }
}
