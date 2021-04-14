<?php

namespace Alura\Cursos\Controller;

use Nyholm\Psr7\Response;
use Alura\Cursos\Entity\Usuario;
use Psr\Http\Message\ResponseInterface;
use Doctrine\ORM\EntityManagerInterface;
use Alura\Cursos\Helper\FlashMessageTrait;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RealizarLogin implements RequestHandlerInterface
{
    use FlashMessageTrait;

    private $repositorioDeUsuarios;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repositorioDeUsuarios = $entityManager
            ->getRepository(Usuario::class);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $email = filter_var(
            ($request->getParsedBody())['email'], 
            FILTER_VALIDATE_EMAIL
        );

        $senha = filter_var(
            ($request->getParsedBody())['senha'],
            FILTER_SANITIZE_STRING
        );

        if (is_null($email) || $email === false || is_null($senha) || $senha === false){
            $this->defineMensagem('danger', "Email ou senha incorretos!");

            return new Response(302, [
                "Location" => "/login"
            ]);
        }


        $usuario = $this->repositorioDeUsuarios->findOneBy(['email' => $email]);
        if (!$usuario){
            $this->defineMensagem('danger', "Email ou senha incorretos!");
            return new Response(302, [
                "Location" => "/login"
            ]);
        }

        if (!$usuario->senhaEstaCorreta($senha)){
            $this->defineMensagem('danger', "Email ou senha incorretos!");
            return new Response(302, [
                "Location" => "/login"
            ]);
        }
        session_start();
        $_SESSION['logado'] = true;

        return new Response(302, [
            "Location" => "/listar-cursos"
        ]);
    }
}
