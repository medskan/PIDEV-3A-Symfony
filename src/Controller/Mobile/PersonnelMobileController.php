<?php

namespace App\Controller\Mobile;

use App\Entity\Personnel;
use App\Repository\PersonnelRepository;
use App\Repository\SalleRepository;
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
 * @Route("/mobile/personnel")
 */
class PersonnelMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(PersonnelRepository $personnelRepository): Response
    {
        $personnels = $personnelRepository->findAll();

        if ($personnels) {
            return new JsonResponse($personnels, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/show", methods={"POST"})
     */
    public function show(Request $request, PersonnelRepository $personnelRepository): Response
    {
        $personnel = $personnelRepository->find((int)$request->get("id"));

        if ($personnel) {
            return new JsonResponse($personnel, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, SalleRepository $salleRepository): JsonResponse
    {
        $personnel = new Personnel();

        return $this->manage($personnel, $salleRepository, $request);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, PersonnelRepository $personnelRepository, SalleRepository $salleRepository): Response
    {
        $personnel = $personnelRepository->find((int)$request->get("id"));

        if (!$personnel) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($personnel, $salleRepository, $request);
    }

    public function manage($personnel, $salleRepository, $request): JsonResponse
    {
        $salle = $salleRepository->find((int)$request->get("salle"));
        if (!$salle) {
            return new JsonResponse("salle with id " . (int)$request->get("salle") . " does not exist", 203);
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

        $personnel->setUp(
            $request->get("nom"),
            $request->get("prenom"),
            DateTime::createFromFormat("d-m-Y", $request->get("dateEmbauche")),
            $request->get("tel"),
            $request->get("email"),
            $request->get("password"),
            (float)$request->get("salaire"),
            $request->get("poste"),
            $request->get("hTravail"),
            $request->get("hAbsence"),
            $salle
        );
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($personnel);
        $entityManager->flush();

        return new JsonResponse($personnel, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, PersonnelRepository $personnelRepository): JsonResponse
    {
        $personnel = $personnelRepository->find((int)$request->get("id"));

        if (!$personnel) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($personnel);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, PersonnelRepository $personnelRepository): Response
    {
        $personnels = $personnelRepository->findAll();

        foreach ($personnels as $personnel) {
            $entityManager->remove($personnel);
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
