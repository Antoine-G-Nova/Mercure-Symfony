<?php

namespace App\Service;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use App\Repository\UserRepository;
use Lcobucci\JWT\Signer\Hmac\Sha256;

final class MyJwtProvider
{
    private $secretKey;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(
        $secretKey,
        UserRepository $userRepository
    ) {
        $this->secretKey      = $secretKey;
        $this->userRepository = $userRepository;
    }

    public function __invoke(): string
    {
        $users = $this->userRepository->findAll();
        $usersAllowArray = [];

        foreach ($users as $user) {
            $usersAllowArray[] = "http://exemple.com/user/{$user->getId()}";
        }

        $token = (new Builder())
            ->withClaim('mercure', ['subscribe' => $usersAllowArray, 'publish' => $usersAllowArray])
            ->getToken(new Sha256(), new Key($this->secretKey));

        return $token;
    }
}
