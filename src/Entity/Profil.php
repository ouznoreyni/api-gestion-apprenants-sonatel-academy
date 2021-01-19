<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      routePrefix="/admin",
 *      normalizationContext={"groups"={"profil:read"}},
 *      denormalizationContext={"groups"={"profil:write"}},
 *      attributes={
 *         "pagination_items_per_page"=10,
 *     },
 *     itemOperations={"GET", "PUT", "DELETE"}
 * )
 * @ApiFilter(BooleanFilter::class, properties={"archiver"=false})
 * )
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 */
class Profil
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
     *  "profil:read"
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
     *  "profil:write","profil:read"
     * })
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({
     *  "admin:write", "admin:read",
     *  "apprenant:write", "apprenant:read",
     *  "formateur:write", "formateur:read",
     *  "user:write", "user:read",
     *  "cm:write", "cm:read",
     *  "profil:read"
     * })
     */
    private $archiver;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     *
     * @ApiSubresource
     *
     * @Groups({
     *  "profil:read"
     * })
     *
     */
    private $users;

    public function __construct()
    {
        $this->setArchiver(false);
        $this->users = new ArrayCollection();
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

    public function getArchiver(): ?string
    {
        return $this->archiver;
    }

    public function setArchiver(string $archiver): self
    {
        $this->archiver = $archiver;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setProfil($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
            }
        }

        return $this;
    }
}
