<?php

namespace App\Service;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
class ShoppingCartService
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function countProductsInShoppingCart(int $userId): int
    {

        $user = $this->entityManager->getRepository(User::class)->find($userId);
        $shoppingCart = $user->getShoppingCart();

        if ($shoppingCart) {
            $nbProducts = count($shoppingCart->getShoppingCartProducts());
        } else{
            $nbProducts = 0;
        }
        return $nbProducts;
    }
}