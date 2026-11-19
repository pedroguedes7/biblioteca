<?php

namespace App\Tests\Entity;

use App\Entity\Assunto;
use App\Entity\Autor;
use App\Entity\Livro;
use PHPUnit\Framework\TestCase;

class LivroRelacionamentoTest extends TestCase
{
    public function testAdicionaAutorAoLivro(): void
    {
        $livro = new Livro();
        $autor = new Autor();

        $livro->addAutor($autor);

        self::assertTrue($livro->getAutores()->contains($autor));
        self::assertTrue($autor->getLivros()->contains($livro));
    }

    public function testRemoveAutorDoLivro(): void
    {
        $livro = new Livro();
        $autor = new Autor();

        $livro->addAutor($autor);
        $livro->removeAutor($autor);

        self::assertFalse($livro->getAutores()->contains($autor));
        self::assertFalse($autor->getLivros()->contains($livro));
    }

    public function testAdicionaAssuntoAoLivro(): void
    {
        $livro = new Livro();
        $assunto = new Assunto();

        $livro->addAssunto($assunto);

        self::assertTrue($livro->getAssuntos()->contains($assunto));
        self::assertTrue($assunto->getLivros()->contains($livro));
    }

    public function testRemoveAssuntoDoLivro(): void
    {
        $livro = new Livro();
        $assunto = new Assunto();

        $livro->addAssunto($assunto);
        $livro->removeAssunto($assunto);

        self::assertFalse($livro->getAssuntos()->contains($assunto));
        self::assertFalse($assunto->getLivros()->contains($livro));
    }
}
