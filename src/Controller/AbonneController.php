<?php

namespace App\Controller;

use App\Entity\Abonne;
use App\Form\AbonneType;
use App\Form\ContactType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;


class AbonneController extends AbstractController
{
    /**
     * @Route("/abonne", name="abonne")
     */
    public function index(): Response
    {
        return $this->render('abonne/index.html.twig', [
            'controller_name' => 'AbonneController',
        ]);
    }
    /**
     * @Route("/afficheAb", name="afficheAb")
     */
    public function afficheAb(): Response
    {
        //rÃƒÂ©cupÃƒÂ©rer le repository pour utiliser la fonction findAll
        $r=$this->getDoctrine()->getRepository(Abonne::class);
        //faire appel Ãƒ  la fonction findAll()
        $abonne=$r->findAll();

        //ou $r=$this->getDoctrine()->getRepository(Classroom::class)->findAll();
        return $this->render('abonne/afficheAb.html.twig', [
            'c' => $abonne,
        ]);
    }
    /**
     * @Route("/supp/{id}", name="suppAb")
     */
    public function supp($id): Response

    {
        //rÃ©cupÃ©rer le classroom Ã  supprimer
        $abonne=$this->getDoctrine()->getRepository(Abonne::class)->find($id);
        //on passe Ã  la suppression
        $em=$this->getDoctrine()->getManager();
        $em->remove($abonne);
        $em->flush();


        return $this->redirectToRoute('afficheAb');
    }
    /**
     * @Route("/ajoutAb", name="ajoutAb")
     */
    public function ajoutAb(Request $request ): Response
    {
        //crÃ©ation du formulaire
        $ab = new Abonne();
        $form = $this->createForm(AbonneType::class, $ab);
        //rÃ©cupÃ©rer les donnÃ©es saisies depuis la requete http
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ab);
            $em->flush();

            return $this->redirectToRoute('afficheAb');
        }

        return $this->render('abonne/ajoutAb.html.twig', [
            'f' => $form->createView(),
        ]);

    }


    /**
     * @Route("/ajoutfrontAb", name="ajoutfrontAb")
     */
    public function ajoutfrontAb(Request $request): Response
    {
        //crÃ©ation du formulaire
        $ab = new Abonne();
        $form = $this->createForm(AbonneType::class, $ab);
        //rÃ©cupÃ©rer les donnÃ©es saisies depuis la requete http
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ab);
            $em->flush();
            return $this->redirectToRoute('afficheAb');
        }

        return $this->render('abonne/ajoutfrontAb.html.twig', [
            'f' => $form->createView(),
        ]);

    }
    /**
     * @Route("/modifAb/{id}", name="modifAb")
     */
    public function modifAb(Request $request,$id): Response
    {
        //rÃ©cupÃ©rer le classroom Ã  modifier
        $abonne=$this->getDoctrine()->getRepository(Abonne::class)->find($id);
        //crÃ©ation du formulaire rempli
        $form=$this->createForm(AbonneType::class,$abonne);
        //rÃ©cupÃ©rer les donnÃ©es saisies depuis la requete http
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em=$this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('afficheAb');
        }

        return $this->render('abonne/ajoutAb.html.twig', [
            'f' => $form->createView(),
        ]);
    }
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $contactformData = $form->getData();
            dump($contactformData);
            $email = (new Email())
                ->from($contactformData ['email'])
                ->to('mohamedskander.ouada@esprit.tn')
                ->text($contactformData['message'],
                'text/plain')
                    ;

            $mailer->send($email);


        }
        return $this->render('abonne/contact.html.twig', [
            'our_form'=> $form->createView()
        ]);
    }


}
