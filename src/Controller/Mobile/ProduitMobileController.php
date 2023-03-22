<?php

namespace App\Controller\Mobile;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/produit")
 */
class ProduitMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        if ($produits) {
            return new JsonResponse($produits, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/cat={id}", methods={"GET"})
     */
    public function parCat($id, CategorieRepository $categorieRepository, ProduitRepository $produitRepository): Response
    {
        $categorie = $categorieRepository->find((int)$id);

        if (!$categorie) {
            return new JsonResponse([], 204);
        }

        $produits = $produitRepository->findBy(['categorie' => $categorie]);

        if ($produits) {
            return new JsonResponse($produits, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }


    /**
     * @Route("/show", methods={"POST"})
     */
    public function show(Request $request, ProduitRepository $produitRepository): Response
    {
        $produit = $produitRepository->find((int)$request->get("id"));

        if ($produit) {
            return new JsonResponse($produit, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, CategorieRepository $categorieRepository): Response
    {
        $produit = new Produit();

        return $this->manage($produit, $categorieRepository, $request);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {
        $produit = $produitRepository->find((int)$request->get("id"));

        if (!$produit) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($produit, $categorieRepository, $request);
    }

    public function manage($produit, $categorieRepository, $request): Response
    {
        $categorie = $categorieRepository->find((int)$request->get("categorie"));
        if (!$categorie) {
            return new JsonResponse("categorie with id " . (int)$request->get("categorie") . " does not exist", 203);
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

        $produit->setUp(
            $request->get("description"),
            $request->get("nomP"),
            (int)$request->get("nombre"),
            (float)$request->get("prix"),
            (float)$request->get("reduction"),
            $categorie,
            $fileName
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($produit);
        $entityManager->flush();

        return new JsonResponse($produit, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, ProduitRepository $produitRepository): Response
    {
        $produit = $produitRepository->find((int)$request->get("id"));

        if (!$produit) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($produit);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        foreach ($produits as $produit) {
            $entityManager->remove($produit);
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
