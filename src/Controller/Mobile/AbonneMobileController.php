<?php

namespace App\Controller\Mobile;

use App\Entity\Abonne;
use App\Repository\AbonneRepository;
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
 * @Route("/mobile/abonne")
 */
class AbonneMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(AbonneRepository $abonneRepository): Response
    {
        $abonnes = $abonneRepository->findAll();

        if ($abonnes) {
            return new JsonResponse($abonnes, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/show", methods={"POST"})
     */
    public function show(Request $request, AbonneRepository $abonneRepository): Response
    {
        $abonne = $abonneRepository->find((int)$request->get("id"));

        if ($abonne) {
            return new JsonResponse($abonne, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, AbonnementRepository $abonnementRepository): JsonResponse
    {
        $abonne = new Abonne();

        return $this->manage($abonne, $abonnementRepository, $request);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, AbonneRepository $abonneRepository, AbonnementRepository $abonnementRepository): Response
    {
        $abonne = $abonneRepository->find((int)$request->get("id"));

        if (!$abonne) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($abonne, $abonnementRepository, $request);
    }

    public function manage($abonne, $abonnementRepository, $request): JsonResponse
    {
        $abonnement = $abonnementRepository->find((int)$request->get("abonnement"));
        if (!$abonnement) {
            return new JsonResponse("abonnement with id " . (int)$request->get("abonnement") . " does not exist", 203);
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

        $abonne->setUp(
            $request->get("nom"),
            $request->get("prenom"),
            (int)$request->get("age"),
            $request->get("sexe"),
            $request->get("email"),
            $request->get("mdp"),
            (int)$request->get("tel"),
            $request->get("adresse"),
            $abonnement,
            $request->get("message"),
            $fileName

        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($abonne);
        $entityManager->flush();

        return new JsonResponse($abonne, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, AbonneRepository $abonneRepository): JsonResponse
    {
        $abonne = $abonneRepository->find((int)$request->get("id"));

        if (!$abonne) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($abonne);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, AbonneRepository $abonneRepository): Response
    {
        $abonnes = $abonneRepository->findAll();

        foreach ($abonnes as $abonne) {
            $entityManager->remove($abonne);
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
