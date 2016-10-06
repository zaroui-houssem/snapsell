<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MessageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message',TextareaType::class,array('attr'=>array('class'=>'form-control','style'=>'height: 200px;')))
            ->add('subject',TextType::class,array('attr'=>array('placeholder' => 'الموضوع :','class'=>'form-control')))
            ->add('receiver',EntityType::class,
                array('class'=>'UserBundle:User',
                    'choice_label'=>'username',
                    'multiple'=>false,
                    'expanded' => false,
                    'attr'=>array('class'=>'form-control select-input'),
                ))
            ->add('send', SubmitType::class, array('attr' => array('class' => 'save btn btn-primary')))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AdminBundle\Entity\Message'
        ));
    }
}
