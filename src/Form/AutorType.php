<?php

namespace App\Form;

use App\Entity\Autor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AutorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nome', TextType::class, [
                'label' => 'Nome do autor',
                'attr' => [
                    'placeholder' => 'Digite o nome do autor',
                    'maxlength' => 40,
                ],
                'constraints' => [
                    new Assert\NotBlank(
                        message: 'O nome do autor é obrigatório.'
                    ),
                    new Assert\Length(
                        max: 40,
                        maxMessage: 'O nome deve ter no máximo {{ limit }} caracteres.'
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Autor::class,
        ]);
    }
}
