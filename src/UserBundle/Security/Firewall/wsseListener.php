<?php
namespace UserBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use UserBundle\Security\Authentication\Token\WsseUserToken;

class wsseListener implements ListenerInterface
{
    protected $tokenStorage;
    protected $authenticationManager;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $token = new WsseUserToken();
        $wsseRegex = '/UsernameToken Username="([^"]+)", PasswordDigest="([^"]+)", Nonce="([^"]+)", Created="([^"]+)"/';
        $wsseRegexFacebook='/UsernameToken Username="([^"]+)", facebookID="([^"]+)", Nonce="([^"]+)", Created="([^"]+)"/' ;
        $wsseRegexGoogle='/UsernameToken Username="([^"]+)", googleID="([^"]+)", Nonce="([^"]+)", Created="([^"]+)"/';
        $wsseRegexTwitter='/UsernameToken Username="([^"]+)", twitterID="([^"]+)", Nonce="([^"]+)", Created="([^"]+)"/';

        if ($request->headers->has('x-wsse') && 1 == preg_match($wsseRegex, $request->headers->get('x-wsse'), $matches)) {

            $token->setUser(base64_decode($matches[1]));
            $token->digest   = $matches[2];
            $token->nonce    = $matches[3];
            $token->created  = $matches[4];
        }
        else if($request->headers->has('x-wsse') && 1 == preg_match($wsseRegexFacebook, $request->headers->get('x-wsse'), $matches)){

                $token->setUser(base64_decode($matches[1]));
                $token->facebookID =$matches[2];
                $token->nonce    = $matches[3];
                $token->created  = $matches[4];

        } else if($request->headers->has('x-wsse') && 1 == preg_match($wsseRegexGoogle, $request->headers->get('x-wsse'), $matches)){

                $token->setUser(base64_decode($matches[1]));
                $token->googleID =$matches[2];
                $token->nonce    = $matches[3];
                $token->created  = $matches[4];
        }else if($request->headers->has('x-wsse') && 1 == preg_match($wsseRegexTwitter, $request->headers->get('x-wsse'), $matches)){

                $token->setUser(base64_decode($matches[1]));
                $token->twitterID =$matches[2];
                $token->nonce    = $matches[3];
                $token->created  = $matches[4];
        }
        else {
            $response = new Response();
            $response->setStatusCode(403);
            $event->setResponse($response);


        }



        try {
            $authToken = $this->authenticationManager->authenticate($token);

            $this->tokenStorage->setToken($authToken);

        } catch (AuthenticationException $failed) {
            $failedMessage = 'WSSE Login failed for '.$token->getUsername();

            // Deny authentication with a '403 Forbidden' HTTP response
            $response = new Response();
            $response->setContent($failedMessage);
            $response->setStatusCode(403);
            $event->setResponse($response);

        }
    }
}