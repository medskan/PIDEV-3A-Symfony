<?php

namespace App\Controller\Mobile;

use App\Entity\Publication;
use App\Repository\PublicationRepository;
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
 * @Route("/mobile/post")
 */
class PublicationMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(PublicationRepository $publicationRepository): Response
    {
        $publications = $publicationRepository->findAll();

        $publicationsArray = [];
        foreach ($publications as $publication) {
            $publicationArray = $publication->jsonSerialize();

            $commentsArray = [];
            foreach ($publication->getCommentaires() as $comment) {
                $commentsArray[] = $comment->jsonSerialize();
            }
            $publicationArray["comments"] = $commentsArray;
            $publicationsArray[] = $publicationArray;
        }

        if ($publications) {
            return new JsonResponse($publicationsArray, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/show", methods={"POST"})
     */
    public function show(Request $request, PublicationRepository $publicationRepository): Response
    {
        $publication = $publicationRepository->find((int)$request->get("id"));

        if ($publication) {
            return new JsonResponse($publication, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $publication = new Publication();
        $publication->setDatePub(new DateTime());
        return $this->manage($publication, $request);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, PublicationRepository $publicationRepository): Response
    {
        $publication = $publicationRepository->find((int)$request->get("id"));

        if (!$publication) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($publication, $request);
    }

    public function manage($publication, $request): JsonResponse
    {
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

        $publication->setUp(
            $request->get("object"),
            $request->get("content"),
            $fileName,
            (int)$request->get("userId")
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($publication);
        $entityManager->flush();

        return new JsonResponse($publication, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, PublicationRepository $publicationRepository): JsonResponse
    {
        $publication = $publicationRepository->find((int)$request->get("id"));

        if (!$publication) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($publication);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, PublicationRepository $publicationRepository): Response
    {
        $publications = $publicationRepository->findAll();

        foreach ($publications as $publication) {
            $entityManager->remove($publication);
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
