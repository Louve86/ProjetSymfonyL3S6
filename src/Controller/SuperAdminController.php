<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\ProductType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route("/superadmin", name:"superadmin")]
class SuperAdminController extends AbstractController
{

    #[Route('/admin/add', name: '_admin_add')]
    public function createAdminAction(EntityManagerInterface $em, Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->add('send', SubmitType::class, ['label' => 'add user']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user->setRoles(['ROLE_ADMIN']);
            $em->persist($user);
            $em->flush();
            $this->addFlash('info', 'ajout admin réussi');
            return $this->redirectToRoute('accueil');
        }

        if ($form->isSubmitted())
            $this->addFlash('info', 'formulaire ajout admin incorrect');

        $args = array(
            'myform' => $form->createView(),
        );
        return $this->render('superadmin/admin_add.html.twig', $args);

    }
}
