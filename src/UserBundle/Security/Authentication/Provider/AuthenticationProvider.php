<?php

namespace UserBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use UserBundle\Security\Authentication\Token\WsseUserToken;

class AuthenticationProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $cacheDir;

    public function __construct(UserProviderInterface $userProvider, $cacheDir)
    {
        $this->userProvider = $userProvider;
        $this->cacheDir     = $cacheDir;
    }

    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());
        if(!$user){
            throw new AuthenticationException("Bad credentials... Did you forgot your username ?");
        }

        if($user && !is_null($token->facebookID)){
            if($user && $this->validateToken($token->facebookID, $token->nonce, $token->created ,$user->getFacebookID())){
                $authenticatedToken = new WsseUserToken($user->getRoles());
                $authenticatedToken->setUser($user);
                return $authenticatedToken;
            }
        }else if($user && !is_null($token->googleID)){
            if($user && $this->validateToken($token->googleID , $token->nonce, $token->created,$user->getGoogleID())){
                $authenticatedToken = new WsseUserToken($user->getRoles());
                $authenticatedToken->setUser($user);
                return $authenticatedToken;
            }
        }else if($user&& !is_null($token->twitterID)){
            if($user && $this->validateToken($token->twitterID , $token->nonce, $token->created,$user->getTwitterID())){
                $authenticatedToken = new WsseUserToken($user->getRoles());
                $authenticatedToken->setUser($user);
                return $authenticatedToken;
            }
        }
        else if ($user && $this->validateDigest($token->digest, $token->nonce, $token->created, $user->getPassword())) {
            $authenticatedToken = new WsseUserToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The WSSE authentication failed.');
    }

    protected function validateDigest($digest, $nonce, $created, $secret)
    {
        // Expire le timestamp après 5 minutes
        if (time() - strtotime($created) > 300) {
            return false;
        }

        // Valide que le nonce est unique dans les 5 minutes
        if (file_exists($this->cacheDir.'/'.$nonce) && file_get_contents($this->cacheDir.'/'.$nonce) + 300 > time()) {
            throw new NonceExpiredException('Previously used nonce detected');
        }

        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }

        file_put_contents($this->cacheDir.'/'.$nonce, time());

        // Valide le Secret
        $expected = base64_encode(sha1(base64_decode($nonce).$created.$secret, true));
        return $digest === $expected;
    }

    protected function validateToken($token,$nonce, $created,$userToken){
        if (time() - strtotime($created) > 300) {
            return false;
        }

        // Valide que le nonce est unique dans les 5 minutes
        if (file_exists($this->cacheDir.'/'.$nonce) && file_get_contents($this->cacheDir.'/'.$nonce) + 300 > time()) {
            throw new NonceExpiredException('Previously used nonce detected');
        }

        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }

        file_put_contents($this->cacheDir.'/'.$nonce, time());
        return $token===$userToken ;
    }
    public function supports(TokenInterface $token)
    {
        return $token instanceof WsseUserToken;
    }
}
