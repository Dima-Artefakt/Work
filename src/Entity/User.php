<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string')]
    private $name;

    #[ORM\Column(type: 'string', nullable: true)]
    private $imgAvatar;

    #[ORM\Column(type: 'string', nullable: true)]
    private $phone;

    #[ORM\Column(type: 'string', nullable: true)]
    private $mobile;

    #[ORM\Column(type: 'string', nullable: true)]
    private $address;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ItemCollection::class, orphanRemoval: true)]
    private $itemCollections;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ItemCollection::class, orphanRemoval: true)]
    private $Comments;

    public function __construct()
    {
        $this->itemCollections = new ArrayCollection();
    }
    public function isGranted($role)
    {
        return in_array($role, $this->getRoles());
    }

    public function __toString(): string
    {
        return  $this->email;
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
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getImgAvatar(): ?string
    {
        return $this->imgAvatar;
    }

    public function setImgAvatar(string $imgAvatar): self
    {
        $this->imgAvatar = $imgAvatar;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

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

    /**
     * @return Collection|ItemCollection[]
     */
    public function getItemCollections(): Collection
    {
        return $this->itemCollections;
    }

    public function addItemCollection(ItemCollection $itemCollection): self
    {
        if (!$this->itemCollections->contains($itemCollection)) {
            $this->itemCollections[] = $itemCollection;
            $itemCollection->setUser($this);
        }

        return $this;
    }

    public function removeItemCollection(ItemCollection $itemCollection): self
    {
        if ($this->itemCollections->removeElement($itemCollection)) {
            // set the owning side to null (unless already changed)
            if ($itemCollection->getUser() === $this) {
                $itemCollection->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->Comments;
    }

    public function addComments(ItemCollection $comment): self
    {
        if (!$this->Comments->contains($comment)) {
            $this->Comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComments(ItemCollection $comment): self
    {
        if ($this->Comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }
}


