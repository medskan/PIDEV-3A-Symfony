<?php

namespace App\Controller\Mobile;

use App\Entity\Livraison;
use App\Repository\LivraisonRepository;
use App\Repository\CategorieRepository;
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
 * @Route("/mobile/livraison")
 */
class LivraisonMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(LivraisonRepository $livraisonRepository): Response
    {
        $livraisons = $livraisonRepository->findAll();

        if ($livraisons) {
            return new JsonResponse($livraisons, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/show", methods={"POST"})
     */
    public function show(Request $request, LivraisonRepository $livraisonRepository): Response
    {
        $livraison = $livraisonRepository->find((int)$request->get("id"));

        if ($livraison) {
            return new JsonResponse($livraison, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, CategorieRepository $categorieRepository): JsonResponse
    {
        $livraison = new Livraison();

        return $this->manage($livraison, $categorieRepository, $request);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, LivraisonRepository $livraisonRepository, CategorieRepository $categorieRepository): Response
    {
        $livraison = $livraisonRepository->find((int)$request->get("id"));

        if (!$livraison) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($livraison, $categorieRepository, $request);
    }

    public function manage($livraison, $categorieRepository, $request): JsonResponse
    {
        $dateDeb = null;
        $dateFin = null;
        if ($request->get("dateDebut") != "null" && $request->get("dateFin") != "null") {
            $dateDeb = DateTime::createFromFormat("d-m-Y", $request->get("dateDebut"));
            $dateFin = DateTime::createFromFormat("d-m-Y", $request->get("dateFin"));
        }

        $livraison->setUp(
            (int)$request->get("numL"),
            $request->get("nomLivreur"),
            $request->get("prenomLivreur"),
            $request->get("telLivreur"),
            $request->get("adresseLivraison"),
            $dateDeb,
            $dateFin
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($livraison);
        $entityManager->flush();

        return new JsonResponse("", 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, LivraisonRepository $livraisonRepository): Response
    {
        $livraison = $livraisonRepository->find((int)$request->get("id"));

        if (!$livraison) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($livraison);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, LivraisonRepository $livraisonRepository): Response
    {
        $livraisons = $livraisonRepository->findAll();

        foreach ($livraisons as $livraison) {
            $entityManager->remove($livraison);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }
}
