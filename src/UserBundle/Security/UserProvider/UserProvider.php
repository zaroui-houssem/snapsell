<?php
namespace UserBundle\Security\UserProvider;


use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use UserBundle\Entity\User;
use Doctrine\ORM\EntityManager ;

class UserProvider implements UserProviderInterface
{

    public function __construct(EntityManager $em){

        $this->em=$em;

    }
    public function loadUserByUsername($username)
    {
        $userData =$this->em->getRepository("UserBundle:User")->findOneBy(array("username"=>$username)) ;
        if ($userData) {
            return $userData;
        }

        throw new UsernameNotFoundException(
            sprintf('User Provider : user not found  ', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'UserBundle\Entity\User';
    }


}