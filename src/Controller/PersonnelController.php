<?php

namespace App\Controller;
use App\Form\SearchPersonnelType;
use App\Repository\PersonnelRepository;
use App\Entity\Personnel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PersonnelType;
use Dompdf\Dompdf;
use Dompdf\Options;


class PersonnelController extends AbstractController
{
    /**
     * @Route("/personnel", name="personnel")
     */
    public function index(): Response
    {
        return $this->render('personnel/index.html.twig', [
            'controller_name' => 'PersonnelController',
        ]);
    }

    /**
     * @Route("/affichePersonnel", name="affichePersonnel")
     */
    public function affichePersonnel(): Response
    {
        //rÃ©cupÃ©rer le repository pour utiliser la fonction findAll
        $r=$this->getDoctrine()->getRepository(Personnel::class);
        //faire appel Ã  la fonction findAll()
        $personnels=$r->findAll();

        //ou $r=$this->getDoctrine()->getRepository(Classroom::class)->findAll();
        return $this->render('personnel/affichePersonnel.html.twig', [
            'p' => $personnels,
        ]);
    }

    /**
     * @Route("/suppPersonnel/{id}", name="suppPersonnel")
     */
    public function suppPersonnel($id,PersonnelRepository $repository): Response

    {

        $p=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($p);
        $em->flush();


        return $this->redirectToRoute('affichePersonnel');
    }

    /**
     * @Route("/ajoutPersonnel", name="ajoutPersonnel")
     */
    public function ajoutPersonnel(Request $request): Response
    {
        //crÃ©ation du formulaire
        $p = new Personnel();
        $form = $this->createForm(PersonnelType::class, $p);
        //rÃ©cupÃ©rer les donnÃ©es saisies depuis la requete http
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($p);
            $em->flush();
            return $this->redirectToRoute('affichePersonnel');
        }

        return $this->render('personnel/ajoutPersonnel.html.twig', [
            'f' => $form->createView(),
        ]);

    }

    /**
     * @Route("/modifPersonnel/{id}", name="modifPersonnel")
     */
    public function modifPersonnel(Request $request,$id): Response
    {
        //rÃ©cupÃ©rer le classroom Ã  modifier
        $personnel=$this->getDoctrine()->getRepository(Personnel::class)->find($id);
        //crÃ©ation du formulaire rempli
        $form=$this->createForm(PersonnelType::class,$personnel);
        //rÃ©cupÃ©rer les donnÃ©es saisies depuis la requete http
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();

            $em->flush();
            return $this->redirectToRoute('affichePersonnel');
        }

        return $this->render('personnel/ajoutPersonnel.html.twig', [
            'f' => $form->createView(),
        ]);
    }


    /**
     * @Route("/listPersonnelWithSearch", name="listPersonnelWithSearch")
     */
    public function listStudentWithSearch(Request $request,PersonnelRepository $repository)
    {
         //All of Student
        $personnels= $repository->findAll();
        //list of students order By Mail
        $personnelsByMail = $repository->orderByMail();
        //search
        $searchForm = $this->createForm(SearchPersonnelType::class);
        $searchForm->add("Recherche",SubmitType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $prenomP = $searchForm['prenomP']->getData();
            $resultOfSearch = $repository->searchPersonnel($prenomP);
            return $this->render('personnel/searchPersonnel.html.twig', array(
                "resultOfSearch" => $resultOfSearch,
                "searchPersonnel" => $searchForm->createView()));
        }
        return $this->render('Personnel/affichePersonnel.html.twig', array(
            "personnels" => $personnels,
            "personnelsByMail" => $personnelsByMail,
            "searchPersonnel" => $searchForm->createView()));
    }

    /**
     * @Route("/listpdf", name="listpdf", methods={"GET"})
     */
       public function listp(PersonnelRepository $personnelRepository):Response
       {   $pdfOptions = new Options();
           $pdfOptions->set('defaultFont', 'Arial');

           // Instantiate Dompdf with our options
           $dompdf = new Dompdf($pdfOptions);
           $personnels = $personnelRepository->findAll();

           // Retrieve the HTML generated in our twig file
           $html = $this->renderView('personnel/pdf.html.twig', [
               'personnels' => $personnels ,
           ]);


           // Load HTML to Dompdf
           $dompdf->loadHtml($html);

           // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
           $dompdf->setPaper('A3', 'portrait');

           // Render the HTML as PDF
           $dompdf->render();


           // Output the generated PDF to Browser (force download)
           $dompdf->stream("mypdf.pdf", [
               "Attachment" => true ]);



       }


}

