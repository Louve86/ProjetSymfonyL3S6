<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Entity\ShoppingCartProduct;
use App\Entity\User;
use App\Form\EditCartType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client',name:'client')]
class ClientController extends AbstractController
{
    #[Route('/produits_list', name:'_produits_list')]
    public function listAction(EntityManagerInterface $em) : Response{
        $produits=$em->getRepository(Product::class)->findAll();
        return $this->render('Client/list.html.twig',array('produits'=>$produits,'user'=>$this->getUser()));
    }

    public function EditCartAction(Request $request,EntityManagerInterface $em, $min,int $max,int $produitId,int $userId): Response{
        $form=$this->createForm(EditCartType::class,null,['data'=>['min'=>$min,'max'=>$max]]);
        return $this->render('Client/EditCart.html.twig',['myform'=>$form->createView(),'min'=>$min,'max'=>$max,'produitId'=>$produitId,'userId'=>$userId]);
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
            foreach ($cart->getShoppingCartProducts() as $cartProds){
                if ($cartProds->getProduct()->getId()==$produitId){
                    $prodexist=true;
                    if ($nbArticles==$min){
                        $em->remove($cartProds);
                    }
                    else{
                        $cartProds->setQuantity($cartProds->getQuantity()+$nbArticles);
                    }
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
            $produit->setQuantity($produit->getQuantity()-$nbArticles);
            $em->flush();
        }
        return $this->redirectToRoute("client_produits_list");
    }

    #[Route('/editProfile',name:'_editProfile')]
    public function EditProfileAction(Request $request,EntityManagerInterface $em): Response{
        $user=$this->getUser();
        $form=$this->createForm(UserType::class,$user);
        $form->add('send',SubmitType::class,['label'=>'Modifier']);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em->flush();
            $this->addFlash('info','Informations compte modifiées avec succès');
            return $this->redirectToRoute("accueil");
        }

        if ($form->isSubmitted()){
            $this->addFlash('info', 'formulaire modification compte incorrect');
        }
        return $this->render('Client/EditProfile.html.twig',['myform'=>$form->createView()]);
    }

    #[Route('/cart', name:'_cart')]
    public function cartAction(Request $request,EntityManagerInterface $em){
        $user=$this->getUser();
        $produits=null;
        $cart = $user->getShoppingCart();
        if (!is_null($cart)){
            $produits=$cart->getShoppingCartProducts();
        }
        return $this->render('Client/Cart.html.twig',array('produits'=>$produits,'cart'=>$cart));
    }

    #[Route('/deleteCartProd/{id_prod}',name:'_deleteCartProd',requirements: ['id'=>'\d+'])]
    public function deleteCartProdAction( int $id_prod,EntityManagerInterface $em){
        $productCart=$em->getRepository(ShoppingCartProduct::class)->find($id_prod);
        if (is_null($productCart)){
            throw new NotFoundHttpException('Ce produit de panier n\'existe pas');
        }
        $produit=$productCart->getProduct();
        $produit->setQuantity($produit->getQuantity()+$productCart->getQuantity());
        $em->remove($productCart);
        $em->flush();
        $this->addFlash('info', 'produit supprimé!');
        return $this->redirectToRoute('client_cart');
    }
    #[Route('/emptyCart/{id_cart}',name:'_emptyCart',requirements: ['id'=>'\d+'])]
    public function emptyCartAction(int $id_cart,EntityManagerInterface $em){
        $cart=$em->getRepository(ShoppingCart::class)->find($id_cart);
        if (is_null($cart)){
            throw new NotFoundHttpException("Ce panier n\'existe pas");
        }
        else{
            foreach ($cart->getShoppingCartProducts() as $cartProd){
                $produit=$cartProd->getProduct();
                $produit->setQuantity($produit->getQuantity()+$cartProd->getQuantity());
                $em->remove($cartProd);
            }
        }
        $this->addFlash('info', 'Panier vidé!');
        $em->flush();
        return $this->redirectToRoute('client_cart');
    }

    #[Route('/buy/{id_cart}',name:'_buy',requirements: ['id'=>'\d+'])]
    public function buyAction(int $id_cart,EntityManagerInterface $em){
        $cart=$em->getRepository(ShoppingCart::class)->find($id_cart);
        if (is_null($cart)){
            throw new NotFoundHttpException("Ce panier n\'existe pas");
        }
        else{
            foreach ($cart->getShoppingCartProducts() as $cartProd){
                $em->remove($cartProd);
            }
        }
        $this->addFlash('info', 'Achat effectué!');
        $em->flush();
        return $this->redirectToRoute('client_cart');
    }
}
