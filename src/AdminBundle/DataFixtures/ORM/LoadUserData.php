<?php
namespace AdminBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use UserBundle\Entity\User ;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setSignUpType('form');
        $user->setActivated(1);
        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user,'admin');
        $user->setPassword($encoded);

        $manager->persist($user);
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}