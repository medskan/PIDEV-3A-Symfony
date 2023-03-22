<?php

namespace App\Controller;

use App\Entity\Abonne;
use App\Entity\ProduitLike;
use App\Entity\SearchData;
use App\Form\ProduitType;
use App\Form\SearchType;
use App\Repository\AbonneRepository;
use App\Repository\ProduitLikeRepository;
use App\Services\QrcodeService;
use App\Services\QrcodeService2;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="produit")
     */
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }
    /**
     * @Route("/list", name="list")
     */
    public function AfficheP(ProduitRepository $repository,Request $request,QrcodeService $qrcodeService){
        $qrCode=null;
        $data= new SearchData();
        $data->page=$request->get('page',1);
        $form=$this->createForm(SearchType::class,$data);
        $qrCode=$qrcodeService->qrcode($data->q);

        $form->handleRequest($request);
        $produit=$repository->findSearch($data);
        return $this->render('produit/list.html.twig',
            ['produit'=>$produit,
                'form'=>$form->createView(),
                'qrCode'=>$qrCode
            ]);
    }
    /**
     * @Route("/listclient", name="listclient")
     */
    public function AfficheClient(ProduitRepository $repository,Request $request,QrcodeService2 $qrcodeService){
        $qrCode=null;
        $data= new SearchData();
        $data->page=$request->get('page',1);
        $form=$this->createForm(SearchType::class,$data);
        $qrCode=$qrcodeService->qrcode($data->q);
        $form->handleRequest($request);
        $produit=$repository->findSearch($data);
        return $this->render('produit/listClient.html.twig',
            ['produit'=>$produit,
                'form'=>$form->createView(),
                'qrCode'=>$qrCode
            ]);
    }
    /**
     * @Route("/listp", name="listp")
     */
    public function AffichePDF(ProduitRepository $repository){

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled',true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $produit=$repository->findAll();


        // Retrieve the HTML generated in our twig file
        $html = $this->render('produit/listp.html.twig',
            ['produit'=>$produit,
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

    /**
     * @Route("/deleteP/{idproduit}", name="deleteP")
     */
    public function DeleteP($idproduit,ProduitRepository  $repository){

        $produit=$repository->find($idproduit);
        $em=$this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute('list');
    }

    /**
     * @Route("produit/Add", name="AddP")
     */
    function AddP(Request $request){
        $produit=new Produit();
        $form=$this->createForm(ProduitType::class,$produit);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('image')->getData();
            $fileName= md5(uniqid()).'.'.$file->guessExtension();
            try{$file->move(
                $this->getParameter('brochures_directory'),
                $fileName
            );


            }catch(FileException $e){}
            $em=$this->getDoctrine()->getManager();
            $produit->setImage($fileName);
            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('list');
        }
        return $this->render('produit/AddP.html.twig',
            ['form'=>$form->createView()
            ]);
    }
    /**
     * @Route("produit/update{idproduit}", name="updateP")
     */
    function UpdateP(ProduitRepository $repository,$idproduit,Request $request){
        $produit=$repository->find($idproduit);
        $form=$this->createForm(ProduitType::class,$produit);
        $form->add('update',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('image')->getData();
            $fileName= md5(uniqid()).'.'.$file->guessExtension();
            try{$file->move(
                $this->getParameter('brochures_directory'),
                $fileName
            );


            }catch(FileException $e){}
            $em=$this->getDoctrine()->getManager();
            $produit->setImage($fileName);
            $em->flush();

            return $this->redirectToRoute('list');
        }
        return $this->render('produit/updateP.html.twig',
            ['f'=>$form->createView()
            ]);
    }

    /**
     *
     * @Route("/produit/like{idproduit}", name="like")
     * @param ProduitRepository $prepository
     * @param $idproduit
     * @param ProduitLikeRepository $likeRepo
     * @param AbonneRepository $repository
     * @return Response
     */
    public function like(ProduitRepository $prepository,$idproduit,ProduitLikeRepository $likeRepo,AbonneRepository $repository):Response{
        $produit=$prepository->find($idproduit);
        $abonne=$repository->find(14403755);
        $em=$this->getDoctrine()->getManager();
        if($produit->isliked1( $abonne)){
            $like=$likeRepo->findOneBy([
                    'produit'=>$produit,
                    'abonne'=> $abonne
                ]

            );
            $em->remove($like);
            $em->flush();
            return $this->json(['code'=>200,
                'message'=>'like bien supprimer',
                'likes'=>$likeRepo->count(['produit'=>$produit])
            ],200);
        }

        $like =new ProduitLike();

        $like->setProduit($produit);

        $like->setAbonne( $abonne);

        $em->persist($like);
        $em->flush();
        return $this->json(['code'=>200,
            'message'=>'like bien ajouté',
            'likes'=>$likeRepo->count(['produit'=>$produit])
        ],200) ;
    }
}
