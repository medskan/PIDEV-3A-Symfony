<?php

namespace App\Controller\Mobile;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/user")
 */
class UserMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        if ($users) {
            return new JsonResponse($users, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/show", methods={"POST"})
     */
    public function show(Request $request, UserRepository $userRepository): Response
    {
        $user = $userRepository->find((int)$request->get("id"));

        if ($user) {
            return new JsonResponse($user, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, UserPasswordEncoderInterface $userPasswordEncoder): JsonResponse
    {
        $user = new User();

        return $this->manage($user, $request, $userPasswordEncoder, false);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $user = $userRepository->find((int)$request->get("id"));

        if (!$user) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($user, $request, $userPasswordEncoder, true);
    }

    public function manage($user, $request, $userPasswordEncoder, $isEdit): JsonResponse
    {

        if (!$isEdit) {
            $checkEmail = $this->getDoctrine()->getRepository(User::class)
                ->findOneBy(["email" => $request->get("email")]);

            if ($checkEmail) {
                return new JsonResponse("Email already exist", 203);
            }
        }

        $user->setUp(
            $request->get("email"),
            $request->get("username"),
            $userPasswordEncoder->encodePassword($user, $request->get("password")),
            $request->get("confirm_password"),
            [$request->get("roles")]
        );

        if (!$isEdit) {
            $email = $user->getEmail();
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $transport = new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
                $transport->setUsername('pidev.app.esprit@gmail.com')->setPassword('pidev-cred');
                $mailer = new Swift_Mailer($transport);
                $message = new Swift_Message('EasyFit');
                $message->setFrom(array('pidev.app.esprit@gmail.com' => 'Our Code World'))
                    ->setTo(array($user->getEmail() => $user->getEmail()))
                    ->setBody("<h1>Welcome to EasyFit</h1>", 'text/html');
                $mailer->send($message);
            }
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse($user, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find((int)$request->get("id"));

        if (!$user) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        foreach ($users as $user) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/verif", methods={"POST"})
     */
    public function verif(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $user = $userRepository->findOneBy(["email" => $request->get("email")]);

        if ($user) {
            if ($userPasswordEncoder->isPasswordValid($user, $request->get("password"))) {
                return new JsonResponse($user, 200);
            } else {
                return new JsonResponse("user found but pass wrong", 203);
            }
        } else {
            return new JsonResponse([], 204);
        }
    }
}
