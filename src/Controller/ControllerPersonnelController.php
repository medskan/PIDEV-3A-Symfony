<?php

namespace App\Controller;

use App\Repository\PersonnelRepository;
use App\Entity\Personnel;
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


class ControllerPersonnelController extends AbstractController
{
    /**
     * @Route("/controller/personnel", name="controller_personnel")
     */
    public function index(): Response
    {
        return $this->render('controller_personnel/index.html.twig', [
            'controller_name' => 'ControllerPersonnelController',
        ]);
    }

    /**
     * @Route("/liste", name="liste")
     */
    public function getPersonnels(PersonnelRepository $repo , SerializerInterface $serializerInterface)
    {
        $personnels=$repo->findAll();
        $json=$serializerInterface->serialize($personnels,'json',['groups'=>'personnel']);
        dump($personnels);
        die;
    }

    /**
     * @Route ("/add",name="add")
     */
    public function addpersonnel(Request $request, SerializerInterface $serializer,EntityManagerInterface $em)
    {
        $content=$request->getContent();
        $data=$serializer->deserialize($content,Personnel::class,'json');
        $em->persist($data);
        $em->flush();
        return new Response('personnel added successfully');
    }


    /******************Ajouter Personnel*****************************************/
    /**
     * @Route("/addPerso", name="add_Perso")
     * @Method("POST")
     */

    public function ajouterPersonnelAction(Request $request)
    {
        $personnel = new Personnel();
        $id=$request->query->get("id");
        $nomP = $request->query->get("nomP");
        $prenomP = $request->query->get("prenomP");
        $telP=$request->query->get("telP");
        $emailP = $request->query->get("emailP");
        $mdp = $request->query->get("mdp");
        $salaireP = $request->query->get("salaireP");
        $poste= $request->query->get("poste");
        $hTravail=$request->query->get("hTravail");
        $hAbsence=$request->query->get("hAbsence");



        $em = $this->getDoctrine()->getManager();
        $personnel->setId($id);
        $personnel->setNomP($nomP);
        $personnel->setPrenomP($prenomP);
        $personnel->setTelP($telP);
        $personnel->setEmailP($emailP);
        $personnel->setMdp($mdp);
        $personnel->setSalaireP($salaireP);
        $personnel->setPoste($poste);
        $personnel->setHTravail($hTravail);
        $personnel->setHAbsence($hAbsence);



        $em->persist($personnel);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($personnel);
        return new JsonResponse($formatted);

    }

    /******************Supprimer Personnel*****************************************/

    /**
     * @Route("/deletePersonnel", name="delete_personnel")
     * @Method("DELETE")
     */

    public function deletePersonnelAction(Request $request)
    {
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $personnel = $em->getRepository(Personnel::class)->find($id);
        if($Personnel!=null ) {
            $em->remove($personnel);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("personnel a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id reclamation invalide.");


    }


    /******************Modifier Personnel*****************************************/
    /**
     * @Route("/updatePersonnel", name="update_personnel")
     * @Method("PUT")
     */
    public function modifierPersonnelAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $personnel = $this->getDoctrine()->getManager()
            ->getRepository(Personnel::class)
            ->find($request->get("id"));

        $personnel->setNomP($request->get("objet"));
        $personnel->setPrenomP($request->get("prenomw"));
        $personnel->setEmailP($request->get("email"));

        $em->persist($personnel);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($personnel);
        return new JsonResponse("Personnel a ete modifiee avec success.");

    }


    /******************affichage Personnel*****************************************/

    /**
     * @Route("/displayPersonnel", name="display_personnel")
     */
    public function allPerAction(PersonnelRepository $repo, NormalizerInterface $normalizer)
    {

        $personnels=$repo->findAll();
        $personnelsNormalises= $normalizer->normalize($personnels,null,['groups'=>'personnel']);
        $json=json_encode($personnelsNormalises);
        $response=new response($json,200,["content-type"=>"application/json"]);

       return $response;
    }




    /******************Detail Personnel*****************************************/

    /**
     * @Route("/detailPersonnel", name="detail_personnel")
     * @Method("GET")
     */

    //Detail Reclamation
    public function detailPersonnelAction(Request $request)
    {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $personnel = $this->getDoctrine()->getManager()->getRepository(Personnel::class)->find($id);
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getDescription();
        });
        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($personnel);
        return new JsonResponse($formatted);
    }






}
