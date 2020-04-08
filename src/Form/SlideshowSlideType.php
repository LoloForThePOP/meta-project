<?php

namespace App\Form;

use App\Entity\Slide;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class SlideshowSlideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('slideName',TextType::class, [
                'disabled' => true,
            ])
            ->add('id',TextType::class, [
                'attr' => [
                    'readonly' => true,
                    'autocomplete' => 'off',
                ],
            ])
            ->add('position', HiddenType::class,[
                'attr' => [
                    'readonly' => true,
                    'class'    => 'my-position', // selector is the one used on the js side
                    'autocomplete' => 'off',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Slide::class,
        ]);
    }

}
