<?php

namespace App\Controller;


use App\Repository\SalleRepository;
use App\Entity\Salle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;

class ControllerSalleController extends AbstractController
{
    /**
     * @Route("/controller/salle", name="controller_salle")
     */
    public function index(): Response
    {
        return $this->render('controller_salle/index.html.twig', [
            'controller_name' => 'ControllerSalleController',
        ]);
    }


    /******************Ajouter Salle*****************************************/
    /**
     * @Route("/addSalle", name="add_Salle")
     * @Method("POST")
     */

    public function ajouterSalleAction(Request $request)
    {
        $salle = new Salle();
        $id=$request->query->get("id");
        $nomS = $request->query->get("nomS");
        $adresseS = $request->query->get("adresseS");
        $codePostal=$request->query->get("codePostal");
        $ville = $request->query->get("ville");
        $nombreP = $request->query->get("nombreP");
        $image = $request->query->get("image");

        $em = $this->getDoctrine()->getManager();
        $salle->setId($id);
        $salle->setNomS($nomS);
        $salle->setAdresseS($adresseS);
        $salle->setCodePostal($codePostal);
        $salle->setVille( $ville);
        $salle->setNombreP($nombreP );
        $salle->setImage($image);


        $em->persist($salle);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($salle);
        return new JsonResponse($formatted);

    }


    /******************affichage salle*****************************************/

    /**
     * @Route("/displaySalle", name="display_salle")
     */
    public function allSalAction(SalleRepository $repo, NormalizerInterface $normalizer)
    {

        $salles=$repo->findAll();
        $sallesNormalises= $normalizer->normalize($salles,null,['groups'=>'salle']);
        $json=json_encode($sallesNormalises);
        $response=new response($json,200,["content-type"=>"application/json"]);

        return $response;
    }


    /******************Supprimer Salle*****************************************/

    /**
     * @Route("/deleteSalle", name="delete_salle")
     * @Method("DELETE")
     */

    public function deleteSalleAction(Request $request)
    {
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $salle = $em->getRepository(Salle::class)->find($id);
        if($salle!=null ) {
            $em->remove($salle);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("sallea ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id reclamation invalide.");


    }

    /******************Modifier Salle****************************************/
    /**
     * @Route("/updateSalle", name="update_salle")
     * @Method("PUT")
     */
    public function modifierSalleAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $salle = $this->getDoctrine()->getManager()
            ->getRepository(Salle::class)
            ->find($request->get("id"));

        $salle->setId($request->get("id"));
        $salle->setNomS($request->get("nomS"));
        $salle->setAdresseS($request->get("adresseS"));
        $salle->setCodePostal($request->get("codePostal"));
        $salle->setVille($request->get("ville"));
        $salle->setNombreP($request->get("nombreP"));
        $salle->setImage($request->get("image"));


        $em->persist($salle);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($salle);
        return new JsonResponse("salle a ete modifiee avec success.");

    }












}
