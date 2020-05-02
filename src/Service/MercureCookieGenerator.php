<?php

namespace App\Service;

use App\Entity\User;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Lcobucci\JWT\Builder;

class MercureCookieGenerator extends AbstractController
{
    private $secretKey;

    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function generateCookie($token)
    {
        return sprintf('mercureAuthorization=%s; path=/.well-known/mercure; secure; httponly; SameSite=strict', $token);
    }

    public function generateToken(User $user)
    {
        $token = (new Builder())
            ->withClaim('mercure', ['subscribe' => ["http://exemple.com/user/{$user->getId()}"],]) 
            ->getToken(new Sha256(), new Key($this->secretKey));
        
        return $token;
    }
}
