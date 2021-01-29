<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\GroupeCompetencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"grpcomp:read"}},
 *      denormalizationContext={"groups"={"grpcomp:write"}},
 *      routePrefix="/admin/",
 *      collectionOperations={
 *          "POST"={"path"="/groupe-competences"},
 *          "GET"={"path"="/groupe-competences"},
 *          "get_grpecompetence_have_competences"={
 *              "method"="GET",
 *              "path"="/groupe-competences/competences" ,
 *              "normalization_context"={"groups"={"comp:read"}},
 *             }
 *      },
 *      itemOperations={
 *          "GET"={"path"="/groupe-competences/{id}"},
 *          "PUT"={"path"="/groupe-competences/{id}"}
 *      }
 * )
 * @ORM\Entity(repositoryClass=GroupeCompetencesRepository::class)
 */
class GroupeCompetences
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"grpcomp:read", "ref:read", "ref:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"grpcomp:read", "grpcomp:write", "grpcomp:read", "comp:read", "ref:read", "ref:write"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"grpcomp:read", "grpcomp:write", "ref:read", "ref:write"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Competences::class, inversedBy="groupeCompetences", cascade={"persist"})
     *@ApiSubresource

     * @Groups({"grpcomp:read", "grpcomp:write", "comp:read", "ref:read", "ref:write"})
     */
    private $competences;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, mappedBy="competences")
     */
    private $referentiels;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archiver;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->referentiels = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Competences[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competences $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competences $competence): self
    {
        $this->competences->removeElement($competence);

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
            $referentiel->addCompetence($this);
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        if ($this->referentiels->removeElement($referentiel)) {
            $referentiel->removeCompetence($this);
        }

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
}
