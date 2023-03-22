<?php

namespace App\Controller\Mobile;

use App\Entity\Commentaire;
use App\Repository\CommentaireRepository;
use App\Repository\PublicationRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/comment")
 */
class CommentaireMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        $commentaires = $commentaireRepository->findAll();

        if ($commentaires) {
            return new JsonResponse($commentaires, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/show", methods={"POST"})
     */
    public function show(Request $request, CommentaireRepository $commentaireRepository): Response
    {
        $commentaire = $commentaireRepository->find((int)$request->get("id"));

        if ($commentaire) {
            return new JsonResponse($commentaire, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, PublicationRepository $publicationRepository): JsonResponse
    {
        $commentaire = new Commentaire();
        $commentaire->setDateCom(new DateTime());
        return $this->manage($commentaire, $publicationRepository, $request);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, CommentaireRepository $commentaireRepository, PublicationRepository $publicationRepository): Response
    {
        $commentaire = $commentaireRepository->find((int)$request->get("id"));

        if (!$commentaire) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($commentaire, $publicationRepository, $request);
    }

    public function manage($commentaire, $publicationRepository, $request): JsonResponse
    {
        $publication = $publicationRepository->find((int)$request->get("post"));
        if (!$publication) {
            return new JsonResponse("publication with id " . (int)$request->get("post") . " does not exist", 203);
        }

        $commentaire->setUp(
            $request->get("content"),
            $publication,
            (int)$request->get("userId")
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commentaire);
        $entityManager->flush();

        return new JsonResponse($commentaire, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, CommentaireRepository $commentaireRepository): JsonResponse
    {
        $commentaire = $commentaireRepository->find((int)$request->get("id"));

        if (!$commentaire) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($commentaire);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, CommentaireRepository $commentaireRepository): Response
    {
        $commentaires = $commentaireRepository->findAll();

        foreach ($commentaires as $commentaire) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }
}
