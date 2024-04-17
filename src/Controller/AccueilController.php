<?php

namespace App\Controller;

use App\Service\ShoppingCartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/", name:"accueil")]
class AccueilController extends AbstractController
{
    #[Route('', name: '')]
    public function accueilAction(): Response
    {
        return $this->render('Accueil/accueil.html.twig',array("user"=>$this->getUser()));
    }

    public function menuAction(ShoppingCartService $shoppingCartService) : Response
    {
        $user = $this->getUser();
        if (!is_null($user)){
            $nbProducts = $shoppingCartService->countProductsInShoppingCart($user->getId());
        }
        else $nbProducts = 0;
        return $this->render('Layouts/menu.html.twig',array("user"=>$user, "nbproducts" => $nbProducts));
    }
}
