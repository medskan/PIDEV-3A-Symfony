<?php

namespace App\Controller;


use App\Entity\Equipement;
use App\Form\EquipementType;
use Knp\Component\Pager\PaginatorInterface;

use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;




class EquipementController extends AbstractController
{
    /**
     * @Route("/equipement", name="equipement")
     */
    public function index(): Response
    {
        return $this->render('equipement/index.html.twig', [
            'controller_name' => 'EquipementController',
        ]);
    }

    /**
     * @Route("/afficheE", name="afficheE")
     */
    public function afficheE(Request $request,PaginatorInterface $paginator )
    {
        //rÃƒÂ©cupÃƒÂ©rer le repository pour utiliser la fonction findAll
        $r=$this->getDoctrine()->getRepository(Equipement::class);
        //faire appel Ãƒ  la fonction findAll()
        $données=$r->findAll();
        $equipements=$paginator->paginate(
            $données,
            $request->query->getInt('page',1)
        );


        //ou $r=$this->getDoctrine()->getRepository(Classroom::class)->findAll();

        return $this->render('equipement/afficheE.html.twig', [
            'e' => $equipements,
        ]);
    }

    /**
     * @Route("/suppEquipement/{id}", name="suppEquipement")
     */
    public function supp($id,FlashyNotifier $flashy): Response

    {
        //rÃ©cupÃ©rer le classroom Ã  supprimer
        $equipement=$this->getDoctrine()->getRepository(Equipement::class)->find($id);
        //on passe Ã  la suppression
        $em=$this->getDoctrine()->getManager();
        $em->remove($equipement);
        $em->flush();
        $flashy->warning('Equipement Supprimé!');

        return $this->redirectToRoute('afficheE');
    }



    /**
     * @Route("/ajoutE", name="ajoutE")
     */
    public function ajoutE(Request $request,FlashyNotifier $flashy): Response
    {
        //crÃƒÂ©ation du formulaire
        $e = new Equipement();
        $form = $this->createForm(EquipementType::class, $e);
        //rÃƒÂ©cupÃƒÂ©rer les donnÃƒÂ©es saisies depuis la requete http
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($e);
            $em->flush();

            $flashy->success('Ajout succès!');
            return $this->redirectToRoute('afficheE');
        }

        return $this->render('equipement/ajoutE.html.twig', [
            'f' => $form->createView(),
        ]);

    }

    /**
     * @Route("/modifE/{id}", name="modifE")
     */
    public function modifE(Request $request,$id): Response
    {
        //rÃƒÂ©cupÃƒÂ©rer le classroom ÃƒÂ  modifier
        $equipement=$this->getDoctrine()->getRepository(Equipement::class)->find($id);
        //crÃƒÂ©ation du formulaire rempli
        $form=$this->createForm(EquipementType::class,$equipement);
        //rÃƒÂ©cupÃƒÂ©rer les donnÃƒÂ©es saisies depuis la requete http
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em=$this->getDoctrine()->getManager();

            $em->flush();
            return $this->redirectToRoute('afficheE');
        }

        return $this->render('equipement/ajoutE.html.twig', [
            'f' => $form->createView(),
        ]);
    }




}
