<?php

namespace App\Controller;



use App\Entity\Livraison;
use App\Form\LivraisonType;
use App\Repository\CalendarRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LivraisonController extends AbstractController
{
    /**
     * @Route("/livraison", name="livraison")
     */
    public function index(): Response
    {
        return $this->render('livraison/index.html.twig', [
            'controller_name' => 'LivraisonController',
        ]);
    }


    /**
     * @Route("/afficheL", name="afficheL")
     */
    public function afficheL(): Response
    {
        //rÃƒÂ©cupÃƒÂ©rer le repository pour utiliser la fonction findAll
        $r=$this->getDoctrine()->getRepository(Livraison::class);
        //faire appel Ãƒ  la fonction findAll()
        $livraisons=$r->findAll();

        //ou $r=$this->getDoctrine()->getRepository(Classroom::class)->findAll();
        return $this->render('livraison/afficheL.html.twig', [
            'l' => $livraisons,
        ]);
    }


    /**
     * @Route("/suppL/{id}", name="suppL")
     */
    public function supp($id,FlashyNotifier $flashy): Response

    {
        //rÃ©cupÃ©rer le classroom Ã  supprimer
        $livraisons=$this->getDoctrine()->getRepository(Livraison::class)->find($id);
        //on passe Ã  la suppression
        $em=$this->getDoctrine()->getManager();
        $em->remove($livraisons);
        $em->flush();
        $flashy->warning('Livraison Supprimée !');

        return $this->redirectToRoute('afficheL');
    }


    /**
     * @Route("/ajoutL", name="ajoutL")
     */
    public function ajoutL(Request $request,FlashyNotifier $flashy): Response
    {
        //crÃƒÂ©ation du formulaire
        $l = new Livraison();
        $form = $this->createForm(LivraisonType::class, $l);
        //rÃƒÂ©cupÃƒÂ©rer les donnÃƒÂ©es saisies depuis la requete http
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($l);
            $em->flush();
            $flashy->info('Merci de mettre à jour votre calendrier !');
            return $this->redirectToRoute('afficheL');
        }

        return $this->render('livraison/ajoutL.html.twig', [
            'f' => $form->createView(),
        ]);

    }

    /**
     * @Route("/modifL/{id}", name="modifL")
     */
    public function modifL(Request $request,$id,FlashyNotifier $flashy): Response
    {
        //rÃƒÂ©cupÃƒÂ©rer le classroom ÃƒÂ  modifier
        $livraisons=$this->getDoctrine()->getRepository(Livraison::class)->find($id);
        //crÃƒÂ©ation du formulaire rempli
        $form=$this->createForm(LivraisonType::class,$livraisons);
        //rÃƒÂ©cupÃƒÂ©rer les donnÃƒÂ©es saisies depuis la requete http
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em=$this->getDoctrine()->getManager();

            $em->flush();
            $flashy->info('Votre livraison a été modifiée !');
            return $this->redirectToRoute('afficheL');
        }

        return $this->render('livraison/ajoutL.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    /**
     * @Route("/calendrier", name="calendrier")
     */
    public function caledendar(CalendarRepository $calendar): Response

    {   $events = $calendar->findAll();


        $rdvs = [];

        foreach($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->getAllDay(),
            ];
        }
        $data = json_encode($rdvs);

        return $this->render('livraison/Calendar.html.twig',compact('data')
       );
    }

}
