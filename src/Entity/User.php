<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({
 *      "user":"User",
 *      "apprenant" = "Apprenant", "admin" = "Admin",
 *      "communityManager"="CommunityManager", "formateur"="Formateur"})
 * @ApiResource(
 *       attributes={
 *          "pagination_items_per_page"=10
 *      },
 *      normalizationContext={"groups"={"user:read"}},
 *      denormalizationContext={"groups"={"user:write"}},
 *     collectionOperations={
 *          "post"={"path"="/admin/users"},
 *          "get"={"path"="/admin/users"},
 *      },
 *     itemOperations={
 *      "get"={"path"="/admin/users/{id}"},
 *      "put"={"path"="/admin/users/{id}"},
 *      "delete"={
 *              "path"="/admin/users/{id}",
 *               "method":"DELETE"
 *             }
 *      },
 * )
 * @ApiFilter(BooleanFilter::class, properties={"archiver"=0})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({
     *  "admin:read",
     *  "apprenant:read",
     *  "formateur:read",
     *  "user:read",
     *  "cm:read"
     * })
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * * @Groups({
     *  "admin:write", "admin:read",
     *  "apprenant:write", "apprenant:read",
     *  "formateur:write", "formateur:read",
     *  "user:write", "user:read",
     *  "cm:write", "cm:read"
     * })
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     *
     * * @Groups({
     *  "admin:read",
     *  "apprenant:read",
     *  "formateur:read",
     *  "user:read",
     *  "cm:read"
     * })
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     *
     * * @Groups({
     *  "admin:write",
     *  "apprenant:write",
     *  "formateur:write",
     *  "user:write",
     *  "cm:write"
     * })
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * * @Groups({
     *  "admin:write", "admin:read",
     *  "apprenant:write", "apprenant:read",
     *  "formateur:write", "formateur:read",
     *  "user:write", "user:read",
     *  "cm:write", "cm:read"
     * })
     */
    private $nom;

    /**
     * @ORM\Column(type="blob", nullable=true)
     *
     * * @Groups({
     *  "admin:write", "admin:read",
     *  "apprenant:write", "apprenant:read",
     *  "formateur:write", "formateur:read",
     *  "user:write", "user:read",
     *  "cm:write", "cm:read"
     * })
     */
    private $avatar;

    /**
     * @ORM\Column(type="boolean")
     *
     * * @Groups({
     *  "admin:read",
     *  "apprenant:read",
     *  "formateur:read",
     *  "user:read",
     *  "cm:read"
     * })
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

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
