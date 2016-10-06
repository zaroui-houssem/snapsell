<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',TextType::class,array('attr'=>array('placeholder' => 'إسم المستعمل','class'=>'form-control')))
            ->add('password',PasswordType::class,array('attr'=>array('placeholder' => 'كلمة المرور','class'=>'form-control')))
            ->add('phone_number',TextType::class,array('attr'=>array('placeholder' => 'رقم الهاتف','class'=>'form-control')))
            ->add('address',TextType::class,array('attr'=>array('placeholder' => 'العنوان','class'=>'form-control','id'=>'pac-input')))
            ->add('longitude',HiddenType::class,array('attr'=>array('id'=>'lng')))
            ->add('latitude',HiddenType::class,array('attr'=>array('id'=>'lat')))
            ->add('img',FileType::class,array('attr'=>array('required'=>false)))
            ->add('save', SubmitType::class, array('attr' => array('class' => 'save btn btn-add')))
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
