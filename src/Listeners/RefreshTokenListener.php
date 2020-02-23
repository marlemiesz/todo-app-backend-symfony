<?php

namespace App\Listeners;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class RefreshTokenListener implements EventSubscriberInterface
{

    private $secure = false;
    private $ttl;

    /**
     * AuthenticationSuccessListener constructor.
     * @param $ttl
     */
    public function __construct($ttl)
    {

        $this->ttl = $ttl;
    }


    /**
     * @param AuthenticationSuccessEvent $event
     * @throws \Exception
     */
    public function setRefreshToken(AuthenticationSuccessEvent $event)
    {
        $refreshToken = $event->getData()['refresh_token'];

        $response = $event->getResponse();

        if($refreshToken){
            $response->headers->setCookie(
                new Cookie(
                    "REFRESH_TOKEN",
                    $refreshToken,
                    (new \DateTime())->add(new \DateInterval('PT' . $this->ttl . 'S')),
                    '/',
                    null,
                    $this->secure
                )
            );
        }
    }


    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'lexik_jwt_authentication.on_authentication_success' => [
                ['setRefreshToken']
            ]
        ];
    }
}