<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Entity\ShoppingCartProduct;
use App\Form\ModifCartType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/produit',name:'produit')]
class ProduitController extends AbstractController
{
    #[Route('/list', name:'_list')]
    public function listAction(EntityManagerInterface $em) : Response{
        $produits=$em->getRepository(Product::class)->findAll();
        return $this->render('Produit/list.html.twig',array('produits'=>$produits,'user'=>$this->getUser()));
    }

    public function modifCartAction(Product $produit,int $min, Request $request): Response{
        $user=$this->getUser();
        $form = $this->createForm(ModifCartType::class,null,['data'=>['max'=>$produit->getQuantity(),'min'=>$min]]);
        $form->add('send',SubmitType::class,['label'=>'Modifier']);
        /*
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $shoppingCart=$user->getShoppingCart();
            if(is_null($shoppingCart)){
                $shoppingCart=new ShoppingCart();
                $shoppingCartProduct= new ShoppingCartProduct();
                $shoppingCartProduct->setProduct($produit);
                $shoppingCartProduct->setQuantity($form->get('nbArticles')->getData());
                $shoppingCart->addShoppingCartProduct($shoppingCartProduct);
            }
        }
        */

        return $this->render('Produit/modif_cart.html.twig',array('myform'=>$form->createView(),'produit'=>$produit,'user'=>$user));
    }
}
