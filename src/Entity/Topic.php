<?php

namespace App\Entity;

use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TopicRepository::class)]
class Topic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\NotNull]
    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'topic', targetEntity: ItemCollection::class, orphanRemoval: true)]
    private $itemCollections;

    public function __construct()
    {
        $this->itemCollections = new ArrayCollection();
    }
    public function __toString(): string
    {
        return  $this->name;
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
            $itemCollection->setTopic($this);
        }

        return $this;
    }

    public function removeItemCollection(ItemCollection $itemCollection): self
    {
        if ($this->itemCollections->removeElement($itemCollection)) {
            // set the owning side to null (unless already changed)
            if ($itemCollection->getTopic() === $this) {
                $itemCollection->setTopic(null);
            }
        }

        return $this;
    }
}
