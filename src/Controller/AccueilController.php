<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/", name:"accueil")]
class AccueilController extends AbstractController
{
    #[Route('', name: '')]
    public function accueilAction(): Response
    {
        return $this->render('Accueil/accueil.html.twig');
    }

    public function menuAction() : Response
    {
        //il faudra interroger la base de donnée pour avoir le nombre d'article dans le panier. Pour l'instant, c'est arbitraire.
        $nbArticles=5;
        return $this->render('Layouts/menu.html.twig',array("nbArticles"=>$nbArticles));
    }
}
