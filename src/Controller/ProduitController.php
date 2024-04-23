<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Entity\ShoppingCartProduct;
use App\Entity\User;
use App\Form\EditCartType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    public function EditCartAction(Request $request,EntityManagerInterface $em, $min,int $max,int $produitId,int $userId): Response{
        $form=$this->createForm(EditCartType::class,null,['data'=>['min'=>$min,'max'=>$max]]);
        return $this->render('Produit/EditCart.html.twig',['myform'=>$form->createView(),'min'=>$min,'max'=>$max,'produitId'=>$produitId,'userId'=>$userId]);
    }

    #[Route('/handleForm/{min}/{max}/{userId}/{produitId}', name:'_handleForm')]
    public function HandleForm(Request $request,EntityManagerInterface $em, $min,$max,$userId,$produitId): Response{
        $form=$this->createForm(EditCartType::class,null,['data'=>['min'=>$min,'max'=>$max]]);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $user=$em->getRepository(User::class)->find($userId);
            $produit=$em->getRepository(Product::class)->find($produitId);
            $nbArticles=$form->get('nbArticles')->getData();
            $cart=$user->getShoppingCart();
            if (is_null($cart)){
                $cart=new ShoppingCart();
                $user->setShoppingCart($cart);
                $cart->setCustomer($user);
                $em->persist($cart);
            }
            $prodexist=false;
            dump($cart->getShoppingCartProducts());
            foreach ($cart->getShoppingCartProducts() as $cartProds){
                if ($cartProds->getProduct()->getId()==$produitId){
                    $prodexist=true;
                    $cartProds->setQuantity($cartProds->getQuantity()+$nbArticles);
                }
            }
            if(!$prodexist && $nbArticles!=0){
                $cartProd=new ShoppingCartProduct();
                $cartProd->setProduct($produit)
                    ->setQuantity($nbArticles)
                    ->setShoppingCart($cart);
                $cart->addShoppingCartProduct($cartProd);
                $em->persist($cartProd);
            }
            $em->flush();
            dump($form->get('nbArticles')->getData());
        }
        return $this->redirectToRoute("produit_list");
    }

}
