<?php

namespace Alura\Cursos\Controller;

use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Helper\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Persistencia implements RequestHandlerInterface
{
    use FlashMessageTrait;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function inserirNovoCurso(string $descricao): void
    {
        $curso = new Curso();
        $curso->setDescricao($descricao);

        $this->entityManager->persist($curso);
        $this->entityManager->flush();
        $this->defineMensagem('success', "Curso inserido com sucesso!");
    }

    protected function atualizarCurso(int $id_curso, string $descricao): void
    {
        $curso = $this->entityManager->getReference(Curso::class, $id_curso);
        $curso->setDescricao($descricao);

        $this->entityManager->flush();
        $this->defineMensagem('success', "Curso atualizado com sucesso!");
    }
    
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id_curso = filter_var(
            ($request->getParsedBody())['id'], 
            FILTER_VALIDATE_INT
        );

        $descricao = filter_var(
            ($request->getParsedBody())['descricao'],
            FILTER_SANITIZE_STRING
        );


        if (is_null($id_curso) || $id_curso === false){
            $this->inserirNovoCurso($descricao);
        } else {
            $this->atualizarCurso($id_curso, $descricao);
        }

        return new Response(302, [
            "Location" => "/listar-cursos"
        ]);
    }
}