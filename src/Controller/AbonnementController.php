<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Form\AbonnementType;
use App\Repository\AbonnementRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AbonnementController extends AbstractController
{
    /**
     * @Route("/abonnement", name="abonnement")
     */
    public function index(): Response
    {
        return $this->render('abonnement/index.html.twig', [
            'controller_name' => 'AbonnementController',
        ]);
    }
    /**
     * @Route("/afficheA", name="afficheA")
     */
    public function afficheA(): Response
    {
        //rÃƒÂ©cupÃƒÂ©rer le repository pour utiliser la fonction findAll
        $r=$this->getDoctrine()->getRepository(Abonnement::class);
        //faire appel Ãƒ  la fonction findAll()
        $abonnement=$r->findAll();

        //ou $r=$this->getDoctrine()->getRepository(Classroom::class)->findAll();
        return $this->render('abonnement/afficheA.html.twig', [
            'c' => $abonnement,
        ]);
    }
    /**
     * @Route("/suppA/{id}", name="suppA")
     */
    public function supp($id): Response

    {
        //rÃ©cupÃ©rer le classroom Ã  supprimer
        $abonnement=$this->getDoctrine()->getRepository(Abonnement::class)->find($id);
        //on passe Ã  la suppression
        $em=$this->getDoctrine()->getManager();
        $em->remove($abonnement);
        $em->flush();

        return $this->redirectToRoute('afficheA');
    }
    /**
     * @Route("/ajoutA", name="ajoutA")
     */
    public function ajoutA(Request $request): Response
    {
        //crÃ©ation du formulaire
        $a = new Abonnement();
        $form = $this->createForm(AbonnementType::class, $a);
        //rÃ©cupÃ©rer les donnÃ©es saisies depuis la requete http
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($a);
            $em->flush();
            return $this->redirectToRoute('afficheA');
        }

        return $this->render('abonnement/ajoutA.html.twig', [
            'f' => $form->createView(),
        ]);

    }


    /**
     * @Route("/modifA/{id}", name="modifA")
     */
    public function modifA(Request $request,$id): Response
    {
        //rÃ©cupÃ©rer le classroom Ã  modifier
        $abonnement=$this->getDoctrine()->getRepository(Abonnement::class)->find($id);
        //crÃ©ation du formulaire rempli
        $form=$this->createForm(AbonnementType::class,$abonnement);
        //rÃ©cupÃ©rer les donnÃ©es saisies depuis la requete http
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em=$this->getDoctrine()->getManager();

            $em->flush();
            return $this->redirectToRoute('afficheA');
        }

        return $this->render('abonnement/ajoutA.html.twig', [
            'f' => $form->createView(),
        ]);
    }
    /**
     * @Route("/listA", name="listA", methods={"GET"})
     */
    public function listA(AbonnementRepository $abonnementRepository): Response{
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $abonnement = $abonnementRepository->findAll();


        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('abonnement/listA.html.twig',[
            'abonnement'=>$abonnement
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }


}
