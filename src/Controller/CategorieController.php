<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }

    /**
     * @Route("/afficheCategorie", name="afficheCategorie")
     */
    public function afficheCategorie(CategorieRepository $repository): Response
    {
        //rÃ©cupÃ©rer le repository pour utiliser la fonction findAll
        $Categorie=$repository->findAll();

        //ou $r=$this->getDoctrine()->getRepository(Classroom::class)->findAll();
        return $this->render('categorie/afficheC.html.twig', [
            'categorie' => $Categorie,
        ]);
    }

    /**
     * @Route("/afficheCategorieClient", name="afficheCategorieClient")
     */
    public function afficheCategorieC(): Response
    {
        //rÃ©cupÃ©rer le repository pour utiliser la fonction findAll
        $r=$this->getDoctrine()->getRepository(Categorie::class);
        //faire appel Ã  la fonction findAll()
        $categories=$r->findAll();

        //ou $r=$this->getDoctrine()->getRepository(Classroom::class)->findAll();
        return $this->render('categorie/afficheCategorieC.html.twig', [
            'categories' => $categories,
        ]);
    }


    /**
     * @Route("/supp/{id}", name="suppCategorie")
     */
    public function supp($id): Response

    {
        //récupérer le classroom à supprimer


        $categories=$this->getDoctrine()->getRepository(Categorie::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($categories);
        $em->flush();

        return $this->redirectToRoute('afficheCategorie');
    }

    /**
     * @Route("/ajoutC", name="ajoutCategorie")
     */
    public function ajoutC(Request $request): Response
    {
        //crÃ©ation du formulaire
        $c= new Categorie();
        $form = $this->createForm(CategorieType::class, $c);
        $form->add('Ajouter',SubmitType::class);
        //rÃ©cupÃ©rer les donnÃ©es saisies depuis la requete http
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($c);
            $em->flush();
            return $this->redirectToRoute('afficheCategorie');
        }

        return $this->render('categorie/ajoutCategorie.html.twig', [
            'form' => $form->createView(),
        ]);

    }



    /**
     * @Route("/modifC/{id}", name="modifCategorie")
     */
    public function modifP(Request $request,$id): Response
    {
        //rÃ©cupÃ©rer le classroom Ã  modifier
        $categorie=$this->getDoctrine()->getRepository(Categorie::class)->find($id);
        //crÃ©ation du formulaire rempli
        $form=$this->createForm(CategorieType::class,$categorie);
        $form->add('Enregister',SubmitType::class);
        //rÃ©cupÃ©rer les donnÃ©es saisies depuis la requete http
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em=$this->getDoctrine()->getManager();

            $em->flush();
            return $this->redirectToRoute('afficheCategorie');
        }

        return $this->render('categorie/updateCategorie.html.twig', [
            'f' => $form->createView(),
        ]);
    }
}
