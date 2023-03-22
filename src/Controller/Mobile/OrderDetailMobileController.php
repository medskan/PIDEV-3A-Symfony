<?php
namespace App\Controller\Mobile;

use App\Entity\OrderDetail;
use App\Repository\OrderDetailRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/orderDetail")
 */
class OrderDetailMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(OrderDetailRepository $orderDetailRepository): Response
    {
        $orderDetails = $orderDetailRepository->findAll();

        if ($orderDetails) {
            return new JsonResponse($orderDetails, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/show", methods={"POST"})
     */
    public function show(Request $request, OrderDetailRepository $orderDetailRepository): Response
    {
        $orderDetail = $orderDetailRepository->find((int)$request->get("id"));

        if ($orderDetail) {
            return new JsonResponse($orderDetail, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, ProduitRepository $produitRepository): JsonResponse
    {
        $orderDetail = new OrderDetail();

        return $this->manage($orderDetail, $produitRepository,  $request);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, OrderDetailRepository $orderDetailRepository, ProduitRepository $produitRepository): Response
    {
        $orderDetail = $orderDetailRepository->find((int)$request->get("id"));

        if (!$orderDetail) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($orderDetail, $produitRepository, $request);
    }

    public function manage($orderDetail, $produitRepository, $request): JsonResponse
    {   
        $produit = $produitRepository->find((int)$request->get("produit"));
        if (!$produit) {
            return new JsonResponse("produit with id " . (int)$request->get("produit") . " does not exist", 203);
        }

        $orderDetail->setUp(
            (float)$request->get("quantity"),
            (int)$request->get("prix"),
            $produit
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($orderDetail);
        $entityManager->flush();

        return new JsonResponse($orderDetail, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, OrderDetailRepository $orderDetailRepository): JsonResponse
    {
        $orderDetail = $orderDetailRepository->find((int)$request->get("id"));

        if (!$orderDetail) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($orderDetail);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, OrderDetailRepository $orderDetailRepository): Response
    {
        $orderDetails = $orderDetailRepository->findAll();

        foreach ($orderDetails as $orderDetail) {
            $entityManager->remove($orderDetail);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }
    
}
