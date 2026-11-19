<?php

namespace App\Form;

use App\Entity\Assunto;
use App\Entity\Autor;
use App\Entity\Livro;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class LivroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo', TextType::class, [
                'label' => 'Título',
                'attr' => [
                    'placeholder' => 'Digite o título do livro',
                    'maxlength' => 40,
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'O título é obrigatório.'),
                    new Assert\Length(max: 40),
                ],
            ])
            ->add('editora', TextType::class, [
                'label' => 'Editora',
                'attr' => [
                    'placeholder' => 'Digite a editora',
                    'maxlength' => 40,
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'A editora é obrigatória.'),
                    new Assert\Length(max: 40),
                ],
            ])
            ->add('edicao', IntegerType::class, [
                'label' => 'Edição',
                'constraints' => [
                    new Assert\NotBlank(message: 'A edição é obrigatória.'),
                    new Assert\Positive(message: 'A edição deve ser maior que zero.'),
                ],
            ])
            ->add('anoPublicacao', ChoiceType::class, [
                'label' => 'Ano de publicação',
                'placeholder' => 'Selecione o ano',
                'choices' => array_combine(
                    range((int) date('Y'), 1450),
                    range((int) date('Y'), 1450)
                ),
                'constraints' => [
                    new Assert\NotBlank(message: 'O ano de publicação é obrigatório.'),
                ],
            ])
            ->add('valor', MoneyType::class, [
                'label' => 'Valor',
                'currency' => 'BRL',
                'scale' => 2,
                'constraints' => [
                    new Assert\NotBlank(message: 'O valor é obrigatório.'),
                    new Assert\PositiveOrZero(message: 'O valor não pode ser negativo.'),
                ],
            ])
            ->add('autores', EntityType::class, [
                'class' => Autor::class,
                'choice_label' => 'nome',
                'label' => 'Autores',
                'placeholder' => 'Selecione os autores',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'constraints' => [
                    new Assert\Count(
                        min: 1,
                        minMessage: 'Selecione pelo menos um autor.'
                    ),
                ],
            ])
            ->add('assuntos', EntityType::class, [
                'class' => Assunto::class,
                'choice_label' => 'descricao',
                'label' => 'Assuntos',
                'placeholder' => 'Selecione os assuntos',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'constraints' => [
                    new Assert\Count(
                        min: 1,
                        minMessage: 'Selecione pelo menos um assunto.'
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livro::class,
        ]);
    }
}
