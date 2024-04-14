<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/admin", name:"admin")]
class AdminController extends AbstractController
{
    #[Route('/list', name: '_list')]
    public function listUserAction(EntityManagerInterface $em): Response
    {
        $userRepository = $em->getRepository(User::class);
        $users = $userRepository->findAll();
        $args = array(
            'users' => $users,
        );
        return $this->render('admin/list.html.twig', $args);
    }

    #[Route('/delete/{id}', name: '_delete', requirements: ['id' => '[1-9]\d*'])]
    public function supprUserAction(int $id,EntityManagerInterface $em): Response
    {
        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->find($id);

        if (is_null($user))
        {
            $this->addFlash('info', 'suppression utilisateur ' . $id . ' : échec');
            throw new NotFoundHttpException('utilisateur ' . $id . ' inconnu');
        }

        if ($user == $this->getUser())
        {
            $this->addFlash('info', 'suppression utilisateur ' . $id . ' : échec');
            throw new NotFoundHttpException('utilisateur ' . $id . ' connecté');
        }

        if (in_array('ROLE_SUPERADMIN', $user->getRoles()))
        {
            $this->addFlash('info', 'suppression utilisateur ' . $id . ' : échec');
            throw new NotFoundHttpException('utilisateur ' . $id . ' superadmin');
        }

        $em->remove($user);
        $em->flush();
        $this->addFlash('info', 'suppression utilisateur ' . $id . ' réussie');

        return $this->redirectToRoute('admin_list');
    }
}
