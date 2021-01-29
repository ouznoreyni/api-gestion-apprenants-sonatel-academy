<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CompetencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Count;

/**
 * @ApiResource(
 *      routePrefix="/admin/",
 *      normalizationContext={"groups"={"comp:read"}},
 *      denormalizationContext={"groups"={"comp:write"}},
 *      itemOperations={"GET", "PUT", "DELETE"})
 * @ORM\Entity(repositoryClass=CompetencesRepository::class)
 */
class Competences
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"comp:read", "ref:read", "ref:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"comp:read", "comp:write", "grpcomp:read", "grpcomp:write", "ref:read", "ref:write"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"comp:read", "comp:write", "grpcomp:read", "grpcomp:write", "ref:read", "ref:write"})
     */
    private $archiver;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetences::class, mappedBy="competences")
     */
    private $groupeCompetences;

    /**
     * @ORM\OneToMany(targetEntity=Niveaux::class, mappedBy="competences", cascade={"persist"})
     *
     * @Assert\Count(
     *      min = 3,
     *      max = 3
     * )
     *
     * @Groups({"comp:read", "comp:write", "grpcomp:read", "grpcomp:write", "ref:read", "ref:write"})
     */
    private $niveaux;

    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
        $this->setArchiver(false);
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
     * @return Collection|GroupeCompetences[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
            $groupeCompetence->addCompetence($this);
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        if ($this->groupeCompetences->removeElement($groupeCompetence)) {
            $groupeCompetence->removeCompetence($this);
        }

        return $this;
    }

    /**
     * @return Collection|Niveaux[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveaux(Niveaux $niveaux): self
    {
        if (!$this->niveaux->contains($niveaux)) {
            $this->niveaux[] = $niveaux;
            $niveaux->setCompetences($this);
        }

        return $this;
    }

    public function removeNiveaux(Niveaux $niveaux): self
    {
        if ($this->niveaux->removeElement($niveaux)) {
            // set the owning side to null (unless already changed)
            if ($niveaux->getCompetences() === $this) {
                $niveaux->setCompetences(null);
            }
        }

        return $this;
    }
}
