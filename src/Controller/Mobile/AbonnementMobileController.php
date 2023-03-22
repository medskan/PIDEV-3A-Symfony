<?php

namespace App\Controller\Mobile;

use App\Entity\Abonnement;
use App\Repository\AbonnementRepository;
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
 * @Route("/mobile/abonnement")
 */
class AbonnementMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(AbonnementRepository $abonnementRepository): Response
    {
        $abonnements = $abonnementRepository->findAll();

        if ($abonnements) {
            return new JsonResponse($abonnements, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/show", methods={"POST"})
     */
    public function show(Request $request, AbonnementRepository $abonnementRepository): Response
    {
        $abonnement = $abonnementRepository->find((int)$request->get("id"));

        if ($abonnement) {
            return new JsonResponse($abonnement, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $abonnement = new Abonnement();

        return $this->manage($abonnement, $request);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, AbonnementRepository $abonnementRepository): Response
    {
        $abonnement = $abonnementRepository->find((int)$request->get("id"));

        if (!$abonnement) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($abonnement, $request);
    }

    public function manage($abonnement, $request): JsonResponse
    {
        $abonnement->setUp(
            $request->get("type"),
            (float)$request->get("tarif"),
            DateTime::createFromFormat("d-m-Y", $request->get("dateDebut")),
            DateTime::createFromFormat("d-m-Y", $request->get("dateFin"))
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($abonnement);
        $entityManager->flush();

        return new JsonResponse($abonnement, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, AbonnementRepository $abonnementRepository): JsonResponse
    {
        $abonnement = $abonnementRepository->find((int)$request->get("id"));

        if (!$abonnement) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($abonnement);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, AbonnementRepository $abonnementRepository): Response
    {
        $abonnements = $abonnementRepository->findAll();

        foreach ($abonnements as $abonnement) {
            $entityManager->remove($abonnement);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }
}
