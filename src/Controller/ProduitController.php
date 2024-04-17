<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddProductType;
use App\Form\EditCartType;
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

    #[Route('/edit', name:'_edit')]
    public function EditCartAction(Request $request,int $min,int $max): Response{
        $form = $this->createForm(EditCartType::class,null,['data'=>['min'=>$min,'max'=>$max]]);
        $form->add('send',SubmitType::class,['label'=>'Modifier']);;
        return $this->render('Produit/EditCart.html.twig',['myform'=>$form->createView()]);
    }

}
