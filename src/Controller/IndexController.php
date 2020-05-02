<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\MercureCookieGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(UserRepository $userRepository, MercureCookieGenerator $cookieGenerator)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $token = $cookieGenerator->generateToken($this->getUser());

        $response = $this->render('index/index.html.twig', ['token' =>  $token]);
        $response->headers->set('set-cookie', $cookieGenerator->generateCookie($token));

        return $response;
    }

    /**
     * @Route("/ping", name="ping", methods="GET")
     */
    public function ping(UserRepository $userRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $users = $userRepository->findAll();

        return $this->render('index/ping.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/ping/{id}", name="ping_id", methods="POST")
     */
    public function pingId(
        ?User $user,
        MessageBusInterface $bus
    ) {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $targets = [];
        if ($user !== null) {
            $targets = ["http://exemple.com/user/{$user->getId()}"];
        }
        $update = new Update('http://demo/books/1', json_encode(['data' => 'test']), $targets);
        $bus->dispatch($update);

        return $this->redirectToRoute('ping');
    }
}
