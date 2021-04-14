<?php

namespace Alura\Cursos\Controller;

use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Helper\RenderizadorDeHtmlTrait;
use Doctrine\ORM\EntityManagerInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FormularioEdicao implements RequestHandlerInterface
{
    use RenderizadorDeHtmlTrait;
    
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id_curso = ($request->getQueryParams())['id'];
        $id_curso = filter_var($id_curso, FILTER_VALIDATE_INT);

        if(is_null($id_curso) || $id_curso === false){
            return new Response(302, [
                "Location" => "/listar-cursos"
            ], '');
        }

        

        $curso = $this->entityManager->getReference(Curso::class, $id_curso);
        $titulo = 'Alterar curso '.$curso->getDescricao();

        return new Response(
            200, 
            [],
            $this->renderizaHtml("cursos/formulario.php", [
                'titulo' => $titulo,
                'curso'  => $curso
            ])
        );
    }
}
