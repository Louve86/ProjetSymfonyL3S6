<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\ShoppingCartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/createAccount',name:'_createAccount')]
    public function createAccountAction(Request $request,EntityManagerInterface $em): Response{
        $user=new User();
        $form = $this->createForm(UserType :: class,$user);
        $form->add('send',SubmitType::class,['label'=>'Créer un compte']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_CLIENT']);
            $em->flush();
            $this->addFlash('info', 'Compte crée, essayez de vous connecter!');
            return $this->redirectToRoute("accueil");
        }
        if ($form->isSubmitted()){
            $this->addFlash('info', 'formulaire création compte incorrect');
        }
        return $this->render('Accueil/createAccount.html.twig',array('myform'=>$form->createView()));
    }
}
