<?php

namespace App\Entity;

use App\Repository\ShoppingCartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShoppingCartRepository::class)]
#[ORM\Table(name: 'lic_shoppingCart')]
class ShoppingCart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'shoppingCart', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $customer = null;

    #[ORM\OneToMany(targetEntity: ShoppingCartProduct::class, mappedBy: 'shoppingCart', orphanRemoval: true)]
    private Collection $shoppingCartProducts;

    public function __construct()
    {
        $this->shoppingCartProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(User $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection<int, ShoppingCartProduct>
     */
    public function getShoppingCartProducts(): Collection
    {
        return $this->shoppingCartProducts;
    }

    public function addShoppingCartProduct(ShoppingCartProduct $shoppingCartProduct): static
    {
        if (!$this->shoppingCartProducts->contains($shoppingCartProduct)) {
            $this->shoppingCartProducts->add($shoppingCartProduct);
            $shoppingCartProduct->setShoppingCart($this);
        }

        return $this;
    }

    public function removeShoppingCartProduct(ShoppingCartProduct $shoppingCartProduct): static
    {
        if ($this->shoppingCartProducts->removeElement($shoppingCartProduct)) {
            // set the owning side to null (unless already changed)
            if ($shoppingCartProduct->getShoppingCart() === $this) {
                $shoppingCartProduct->setShoppingCart(null);
            }
        }

        return $this;
    }
}
