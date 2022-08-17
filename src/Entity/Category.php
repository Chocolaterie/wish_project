<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Wish::class, mappedBy="category")
     */
    private $wishList;

    public function __construct()
    {
        $this->wishList = new ArrayCollection();
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
     * @return Collection<int, Wish>
     */
    public function getWishList(): Collection
    {
        return $this->wishList;
    }

    public function addWishList(Wish $wishList): self
    {
        if (!$this->wishList->contains($wishList)) {
            $this->wishList[] = $wishList;
            $wishList->setCategory($this);
        }

        return $this;
    }

    public function removeWishList(Wish $wishList): self
    {
        if ($this->wishList->removeElement($wishList)) {
            // set the owning side to null (unless already changed)
            if ($wishList->getCategory() === $this) {
                $wishList->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->getName();
    }
}
