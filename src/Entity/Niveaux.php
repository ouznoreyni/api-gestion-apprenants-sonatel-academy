<?php

namespace App\Entity;

use App\Repository\NiveauxRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=NiveauxRepository::class)
 */
class Niveaux
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"comp:read", "comp:write", "grpcomp:read", "grpcomp:write", "ref:read", "ref:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     *
     * @Groups({"comp:read", "comp:write", "grpcomp:read", "grpcomp:write", "ref:read", "ref:write"})
     */
    private $criteresEvaluation;

    /**
     * @ORM\Column(type="text")
     *
     * @Groups({"comp:read", "comp:write", "grpcomp:read", "grpcomp:write", "ref:read", "ref:write"})
     */
    private $groupeAction;

    /**
     * @ORM\ManyToOne(targetEntity=Competences::class, inversedBy="niveauxes")
     */
    private $competences;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"comp:read", "comp:write", "grpcomp:read", "grpcomp:write", "ref:read", "ref:write"})
     */
    private $archiver;

    public function __construct()
    {
        $this->setArchiver(false);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCriteresEvaluation(): ?string
    {
        return $this->criteresEvaluation;
    }

    public function setCriteresEvaluation(string $criteresEvaluation): self
    {
        $this->criteresEvaluation = $criteresEvaluation;

        return $this;
    }

    public function getGroupeAction(): ?string
    {
        return $this->groupeAction;
    }

    public function setGroupeAction(string $groupeAction): self
    {
        $this->groupeAction = $groupeAction;

        return $this;
    }

    public function getCompetences(): ?Competences
    {
        return $this->competences;
    }

    public function setCompetences(?Competences $competences): self
    {
        $this->competences = $competences;

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
