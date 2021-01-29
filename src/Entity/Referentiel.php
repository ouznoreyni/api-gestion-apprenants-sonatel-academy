<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      routePrefix="/admin",
 *      normalizationContext={"groups"={"ref:read"}},
 *      denormalizationContext={"groups"={"ref:write"}},
 *      collectionOperations={
 *          "POST"={},
 *          "GET"={},
 *          "get_grpecompetence_have_competences"={
 *              "method"="GET",
 *              "path"="/referentiels/groupe-competences" ,
 *              "normalization_context"={"groups"={"ref:read"}},
 *             }
 *      },
 *      itemOperations={
 *          "GET"={},
 *          "PUT"={},
 *          "DELETE"
 *      }
 * )
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 */
class Referentiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"ref:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"ref:read","ref:write"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     *
     * @Groups({"ref:read","ref:write"})
     */
    private $presentation;

    /**
     * @ORM\Column(type="blob")
     *
     * @Groups({"ref:read","ref:write"})
     */
    private $programme;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetences::class, inversedBy="referentiels", cascade={"persist"})
     *
     * @ApiSubresource()
     *
     * @Groups({"ref:read", "ref:write"})
     */
    private $competences;

    /**
     * @ORM\Column(type="boolean")
     *
     * Groups({"ref:read"})
     */
    private $archiver;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getProgramme()
    {
        return $this->programme;
    }

    public function setProgramme($programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    /**
     * @return Collection|GroupeCompetences[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(GroupeCompetences $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(GroupeCompetences $competence): self
    {
        $this->competences->removeElement($competence);

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
