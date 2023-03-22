<?php

namespace App\Controller\Mobile;

use App\Entity\Permission;
use App\Repository\PermissionRepository;
use App\Repository\PersonnelRepository;
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
 * @Route("/mobile/permission")
 */
class PermissionMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(PermissionRepository $permissionRepository): Response
    {
        $permissions = $permissionRepository->findAll();

        if ($permissions) {
            return new JsonResponse($permissions, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/show", methods={"POST"})
     */
    public function show(Request $request, PermissionRepository $permissionRepository): Response
    {
        $permission = $permissionRepository->find((int)$request->get("id"));

        if ($permission) {
            return new JsonResponse($permission, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, PersonnelRepository $personnelRepository): JsonResponse
    {
        $permission = new Permission();

        return $this->manage($permission, $personnelRepository, $request);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, PermissionRepository $permissionRepository, PersonnelRepository $personnelRepository): Response
    {
        $permission = $permissionRepository->find((int)$request->get("id"));

        if (!$permission) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($permission, $personnelRepository, $request);
    }

    public function manage($permission, $personnelRepository, $request): JsonResponse
    {
        $personnel = $personnelRepository->find((int)$request->get("personnel"));
        if (!$personnel) {
            return new JsonResponse("personnel with id " . (int)$request->get("personnel") . " does not exist", 203);
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

        $permission->setUp(
            $request->get("type"),
            $request->get("reclamation"),
            DateTime::createFromFormat("d-m-Y", $request->get("dateDebut")),
            DateTime::createFromFormat("d-m-Y", $request->get("dateFin")),
            $personnel,
            $fileName
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($permission);
        $entityManager->flush();

        return new JsonResponse($permission, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, PermissionRepository $permissionRepository): JsonResponse
    {
        $permission = $permissionRepository->find((int)$request->get("id"));

        if (!$permission) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($permission);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, PermissionRepository $permissionRepository): Response
    {
        $permissions = $permissionRepository->findAll();

        foreach ($permissions as $permission) {
            $entityManager->remove($permission);
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
