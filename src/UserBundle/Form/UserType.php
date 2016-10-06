<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password')
            ->add('salt')
            ->add('roles')
            ->add('facebookId')
            ->add('facebookProfile')
            ->add('googleId')
            ->add('googleProfile')
            ->add('twitterId')
            ->add('twitterProfile')
            ->add('gcm_registration_id')
            ->add('phone_number')
            ->add('address')
            ->add('longitude')
            ->add('latitude')
            ->add('signUpType')
            ->add('vote')
            ->add('nb_vote')
            ->add('activated')
            ->add('avatar')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\User'
        ));
    }
}
