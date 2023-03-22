<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ControllerPermissionController extends AbstractController
{
    /**
     * @Route("/controller/permission", name="app_controller_permission")
     */
    public function index(): Response
    {
        return $this->render('controller_permission/index.html.twig', [
            'controller_name' => 'ControllerPermissionController',
        ]);
    }
}
