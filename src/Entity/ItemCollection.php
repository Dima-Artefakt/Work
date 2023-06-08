<?php

namespace App\Entity;

use App\Repository\ItemCollectionRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ItemCollectionRepository::class)]
class ItemCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    // название статьи
    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'collection', targetEntity: Item::class, orphanRemoval: true)]
    private Collection $items;

    // описание
    #[ORM\Column(type: "text", length: 65535, nullable: true)]
    private $description;

    //Путь к файлу 
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $img;

    // описание изображения
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imgDes;
    //мини описание 
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $descriptionMin;

    #[ORM\ManyToOne(targetEntity: Topic::class, inversedBy: 'itemCollections')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private $topic;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'itemCollections')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private DateTimeInterface $createDate;

    #[ORM\OneToMany(mappedBy: 'itemCollection', targetEntity: Comment::class, orphanRemoval: true)]
    private $comments;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->createDate = new DateTimeImmutable();
        $this->comments = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreateDate(): ?DateTimeInterface
    {
        return $this->createDate;
    }

    public function getImgDes(): ?string
    {
        return $this->imgDes;
    }

    public function setImgDes(string $imgDes): self
    {
        $this->imgDes = $imgDes;

        return $this;
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

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setCollection($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getCollection() === $this) {
                $item->setCollection(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }
    
    /**
     * @return Collection|Topic[]
     */
    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }
    /**
     * @return Collection|User[]
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setItemCollection($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getItemCollection() === $this) {
                $comment->setItemCollection(null);
            }
        }

        return $this;
    }
}
