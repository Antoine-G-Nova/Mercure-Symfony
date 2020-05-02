<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\MercureCookieGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(
        UserRepository         $userRepository,
        MercureCookieGenerator $cookieGenerator
    ) {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $token = $cookieGenerator->generateToken($this->getUser());

        $response = $this->render('index/index.html.twig', ['token' =>  $token]);
        $response->headers->set('set-cookie', $cookieGenerator->generateCookie($token));

        return $response;
    }

    /**
     * @Route("/ping", name="ping", methods="GET")
     */
    public function pingIndex(UserRepository $userRepository, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $previousMessage = $request->query->get('message') ?? '';
        $users = $userRepository->findAll();

        return $this->render('index/ping.html.twig', [
            'users'   => $users,
            'message' => $previousMessage
        ]);
    }

    /**
     * @Route("/ping/{id}", name="ping_id", methods="POST")
     */
    public function pingWithId(
        ?User               $user,
        MessageBusInterface $bus,
        Request             $request
    ) {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $type = $request->query->get('type');
        $message = $request->request->get('message');
        $targets = [];
        if ($user !== null) {
            $targets = ["http://exemple.com/user/{$user->getId()}"];
        }

        $update = new Update('http://demo/books/1', json_encode(['type' => $type, 'message' => $message]), $targets);
        $bus->dispatch($update);

        return $this->redirectToRoute('ping', ['message' => $message]);
    }

    /**
     * @Route("/ping-all", name="ping_all", methods="POST")
     */
    public function pingAll(MessageBusInterface $bus, Request             $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $type = $request->query->get('type');
        $message = $request->request->get('message');

        $update = new Update('http://demo/books/1', json_encode(['type' => $type, 'message' => $message]));
        $bus->dispatch($update);

        return $this->redirectToRoute('ping', ['message' => $message]);
    }
}
