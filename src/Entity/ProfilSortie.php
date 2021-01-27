<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProfilSortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"profilSortie:read"}},
 *      denormalizationContext={"groups"={"profilSortie:write"}},
 * )
 * @ORM\Entity(repositoryClass=ProfilSortieRepository::class)
 */
class ProfilSortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({
     *  "admin:write", "admin:read",
     *  "apprenant:write", "apprenant:read",
     *  "formateur:write", "formateur:read",
     *  "user:write", "user:read",
     *  "cm:write", "cm:read",
     *  "profil:read",
     *  "profilSortie:read", "profilSortie:write"
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({
     *  "admin:write", "admin:read",
     *  "apprenant:write", "apprenant:read",
     *  "formateur:write", "formateur:read",
     *  "user:write", "user:read",
     *  "cm:write", "cm:read",
     *  "profil:read",
     *  "profilSortie:read", "profilSortie:write"
     * })
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archiver;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="profilSortie")
     *
     * @Groups({
     *  "admin:write", "admin:read",
     *  "apprenant:write", "apprenant:read",
     *  "formateur:write", "formateur:read",
     *  "user:write", "user:read",
     *  "cm:write", "cm:read",
     *  "profil:read",
     *  "profilSortie:read", "profilSortie:write"
     * })
     */
    private $apprenants;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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
            $apprenant->setProfilSortie($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getProfilSortie() === $this) {
                $apprenant->setProfilSortie(null);
            }
        }

        return $this;
    }
}
