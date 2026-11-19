<?php

namespace App\Tests\Entity;

use App\Entity\Assunto;
use App\Entity\Autor;
use App\Entity\Livro;
use App\Form\AssuntoType;
use App\Form\AutorType;
use App\Form\LivroType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormFactoryInterface;

class ValidacaoEntidadesTest extends KernelTestCase
{
    private FormFactoryInterface $formFactory;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->formFactory = static::getContainer()
            ->get('form.factory');
    }

    public function testAutorComNomeVazioEhInvalido(): void
    {
        $form = $this->formFactory->create(
            AutorType::class,
            new Autor(),
            ['csrf_protection' => false]
        );

        $form->submit(['nome' => '']);

        self::assertFalse($form->isValid());
        self::assertGreaterThan(
            0,
            count($form->get('nome')->getErrors(true))
        );
    }

    public function testAssuntoComDescricaoVaziaEhInvalido(): void
    {
        $form = $this->formFactory->create(
            AssuntoType::class,
            new Assunto(),
            ['csrf_protection' => false]
        );

        $form->submit(['descricao' => '']);

        self::assertFalse($form->isValid());
        self::assertGreaterThan(
            0,
            count($form->get('descricao')->getErrors(true))
        );
    }

    public function testLivroComTituloVazioEhInvalido(): void
    {
        $form = $this->formFactory->create(
            LivroType::class,
            new Livro(),
            ['csrf_protection' => false]
        );

        $form->submit(['titulo' => ''], false);

        self::assertGreaterThan(
            0,
            count($form->get('titulo')->getErrors(true))
        );
    }

    public function testAutorComNomeAcimaDe40CaracteresEhInvalido(): void
    {
        $form = $this->formFactory->create(
            AutorType::class,
            new Autor(),
            ['csrf_protection' => false]
        );

        $form->submit(['nome' => str_repeat('A', 41)]);

        self::assertFalse($form->isValid());
    }

    public function testAssuntoComDescricaoAcimaDe20CaracteresEhInvalido(): void
    {
        $form = $this->formFactory->create(
            AssuntoType::class,
            new Assunto(),
            ['csrf_protection' => false]
        );

        $form->submit(['descricao' => str_repeat('A', 21)]);

        self::assertFalse($form->isValid());
    }

    public function testLivroComTituloAcimaDe40CaracteresEhInvalido(): void
    {
        $form = $this->formFactory->create(
            LivroType::class,
            new Livro(),
            ['csrf_protection' => false]
        );

        $form->submit(['titulo' => str_repeat('A', 41)], false);

        self::assertFalse($form->isValid());
    }

    public function testLivroComEditoraAcimaDe40CaracteresEhInvalido(): void
    {
        $form = $this->formFactory->create(
            LivroType::class,
            new Livro(),
            ['csrf_protection' => false]
        );

        $form->submit(['editora' => str_repeat('A', 41)], false);

        self::assertFalse($form->isValid());
    }

}
