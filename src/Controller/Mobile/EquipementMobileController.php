<?php

namespace App\Controller\Mobile;

use App\Entity\Equipement;
use App\Repository\EquipementRepository;
use App\Repository\FournisseurRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/equipement")
 */
class EquipementMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(EquipementRepository $equipementRepository): Response
    {
        $equipements = $equipementRepository->findAll();

        if ($equipements) {
            return new JsonResponse($equipements, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/show", methods={"POST"})
     */
    public function show(Request $request, EquipementRepository $equipementRepository): Response
    {
        $equipement = $equipementRepository->find((int)$request->get("id"));

        if ($equipement) {
            return new JsonResponse($equipement, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, FournisseurRepository $fournisseurRepository): JsonResponse
    {
        $equipement = new Equipement();

        return $this->manage($equipement, $fournisseurRepository, $request);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, EquipementRepository $equipementRepository, FournisseurRepository $fournisseurRepository): Response
    {
        $equipement = $equipementRepository->find((int)$request->get("id"));

        if (!$equipement) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($equipement, $fournisseurRepository, $request);
    }

    public function manage($equipement, $fournisseurRepository, $request): JsonResponse
    {
        $fournisseur = $fournisseurRepository->find((int)$request->get("fournisseur"));
        if (!$fournisseur) {
            return new JsonResponse("fournisseur with id " . (int)$request->get("fournisseur") . " does not exist", 203);
        }

        $file = $request->files->get("file");
        if ($file) {
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            try {
                $file->move($this->getParameter('brochures_directory'), $fileName);
            } catch (FileException $e) {
                dd($e);
            }
        } else {
            if ($request->get("image")) {
                $fileName = $request->get("image");
            } else {
                $fileName = "null";
            }
        }

        $equipement->setUp(
            $request->get("nomE"),
            $request->get("typeE"),
            $request->get("marque"),
            $request->get("gamme"),
            (int)$request->get("quantite"),
            DateTime::createFromFormat("d-m-Y", $request->get("dateCommande")),
            (float)$request->get("prix"),
            $fournisseur
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($equipement);
        $entityManager->flush();

        return new JsonResponse($equipement, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, EquipementRepository $equipementRepository): JsonResponse
    {
        $equipement = $equipementRepository->find((int)$request->get("id"));

        if (!$equipement) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($equipement);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, EquipementRepository $equipementRepository): Response
    {
        $equipements = $equipementRepository->findAll();

        foreach ($equipements as $equipement) {
            $entityManager->remove($equipement);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/image/{image}", methods={"GET"})
     */
    public function getPicture(Request $request): BinaryFileResponse
    {
        return new BinaryFileResponse(
            $this->getParameter('brochures_directory') . "/" . $request->get("image")
        );
    }
}
