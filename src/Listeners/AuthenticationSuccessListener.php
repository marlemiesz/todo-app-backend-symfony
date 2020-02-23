<?php

namespace App\Listeners;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Cookie;

class AuthenticationSuccessListener
{

    private $secure = false;
    private $tokenTtl;

    /**
     * AuthenticationSuccessListener constructor.
     * @param $tokenTtl
     */
    public function __construct($tokenTtl)
    {

        $this->tokenTtl = $tokenTtl;
    }

    /**
     * @param AuthenticationSuccessEvent $event
     * @throws \Exception
     */
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $response = $event->getResponse();
        $data = $event->getData();

        $token = $data['token'];

        $event->setData([]);

        $response->headers->setCookie(
            new Cookie(
                "BEARER",
                $token,
                (new \DateTime())->add(new \DateInterval('PT' . $this->tokenTtl . 'S')),
                '/',
                null,
                $this->secure
            )
        );
    }

}