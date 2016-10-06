<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AdminBundle\Form\MediaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,array('attr'=>array('placeholder' => 'إسم المادة','class'=>'form-control')))
            ->add('description',TextareaType::class,array('attr'=>array('placeholder' => 'إسم المادة','class'=>'form-control')))
            ->add('hashtags',TextType::class,array('attr'=>array('placeholder' =>'نوع المادة','class'=>'form-control')))
            ->add('offerType',ChoiceType::class,
                array('placeholder' =>'نوع العرض',
                    'attr'=>array('class'=>'form-control select-input'),
                    'choices' => array(
                        'بيع' => 'sale',
                        'مزايدة' => 'bidding'

                    )))
            ->add('price',TextType::class,array('attr'=>array('placeholder' =>'السعر','class'=>'form-control')))


            ->add('save', SubmitType::class, array('attr' => array('class' => 'save btn btn-add')))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }
}
