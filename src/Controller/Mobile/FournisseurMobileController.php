<?php

namespace App\Controller\Mobile;

use App\Entity\Fournisseur;
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
 * @Route("/mobile/fournisseur")
 */
class FournisseurMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(FournisseurRepository $fournisseurRepository): Response
    {
        $fournisseurs = $fournisseurRepository->findAll();

        if ($fournisseurs) {
            return new JsonResponse($fournisseurs, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/show", methods={"POST"})
     */
    public function show(Request $request, FournisseurRepository $fournisseurRepository): Response
    {
        $fournisseur = $fournisseurRepository->find((int)$request->get("id"));

        if ($fournisseur) {
            return new JsonResponse($fournisseur, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $fournisseur = new Fournisseur();

        return $this->manage($fournisseur, $request);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, FournisseurRepository $fournisseurRepository): Response
    {
        $fournisseur = $fournisseurRepository->find((int)$request->get("id"));

        if (!$fournisseur) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($fournisseur, $request);
    }

    public function manage($fournisseur, $request): JsonResponse
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

        $fournisseur->setUp(
            $request->get("nomF"),
            $request->get("prenomF"),
            (int)$request->get("telF"),
            $request->get("emailF"),
            $request->get("adresse"),
            $request->get("ribF"),
            $fileName
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($fournisseur);
        $entityManager->flush();

        return new JsonResponse($fournisseur, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, FournisseurRepository $fournisseurRepository): JsonResponse
    {
        $fournisseur = $fournisseurRepository->find((int)$request->get("id"));

        if (!$fournisseur) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($fournisseur);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, FournisseurRepository $fournisseurRepository): Response
    {
        $fournisseurs = $fournisseurRepository->findAll();

        foreach ($fournisseurs as $fournisseur) {
            $entityManager->remove($fournisseur);
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
