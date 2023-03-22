<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class AdminController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index()
    {
        return $this->render('front.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/user", name="user")
     */
    public function usersList(UserRepository $user)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('security/user.html.twig', [
            'user' => $user->findAll(),
        ]);
    }

    /**
     * @Route("/user/modifier/{id}", name="modifier_utilisateur")
     */
    public function editUser(User $user, Request $request)
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('admin_utilisateurs');
        }

        return $this->render('security/edituser.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }
}
