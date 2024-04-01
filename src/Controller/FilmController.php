<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class FilmController extends AbstractController
{
    #[Route('/films', name: 'app_films',methods: ['GET'])]
    public function index(FilmRepository $filmRepository,SerializerInterface $serializer): Response
    {
        $films = $filmRepository->findFilmAffiche();
        $filmsJson = $serializer->serialize($films,'json',['groups'=>'list_films']);

        return new Response($filmsJson, Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }
    #[Route('/films/{id}',name: 'app_films_id',requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(FilmRepository $filmRepository,SerializerInterface $serializer,int $id):Response
    {
        $film = $filmRepository->findFilmDetailId($id);
        $filmJson = $serializer->serialize($film,'json',['groups'=>'detail_film']);
        return new Response($filmJson,Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }
}
