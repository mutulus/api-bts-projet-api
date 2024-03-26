<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class FilmController extends AbstractController
{
    #[Route('/film', name: 'app_film')]
    public function index(FilmRepository $filmRepository,SerializerInterface $serializer): Response
    {
        $films = $filmRepository->findFilmAffiche();
        $filmsJson = $serializer->serialize($films,'json');
        return new Response($filmsJson, Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }
}
