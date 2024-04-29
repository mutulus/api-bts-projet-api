<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Seance;
use App\Entity\User;
use App\Repository\FilmRepository;
use App\Repository\SalleRepository;
use App\Repository\SeanceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class FilmController extends AbstractController
{
    #[Route('/films', name: 'app_films', methods: ['GET'])]
    public function index(FilmRepository $filmRepository, SerializerInterface $serializer): Response
    {
        $films = $filmRepository->findFilmAffiche();
        $filmsJson = $serializer->serialize($films, 'json', ['groups' => 'list_films']);

        return new Response($filmsJson, Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    #[Route('/films/{id}', name: 'app_films_id', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(FilmRepository $filmRepository, SerializerInterface $serializer, int $id): Response
    {
        $film = $filmRepository->findFilmDetailId($id);
        if (empty($film)) {
            $filmJson = json_encode(["Code" => "400", "Erreur" => "Ce film n'existe pas"]);
            return new Response($filmJson, Response::HTTP_NOT_FOUND, ['content-type' => 'Application/json']);
        }
        $filmJson = $serializer->serialize([$film, "code" => 201], 'json', ['groups' => 'detail_film']);
        return new Response($filmJson, Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    #[Route('/reserver/{id}', name: 'app_reserver', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function reservation(SeanceRepository $seanceRepository, \Symfony\Component\HttpFoundation\Request $request, SerializerInterface $serializer, SalleRepository $salleRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $reservation = $request->getContent();
        $newReservation = new Reservation();
        $reservation = $serializer->deserialize($reservation, Reservation::class, 'json');
        $user = $this->getUser();
        $seance = $seanceRepository->find($id);
        $salle = $salleRepository->find($seance->getSalle()->getId());
        if (empty($seance)) {
            $reservationJson = json_encode(['Code' => "404", "Erreur" => "Ce film n'existe pas"]);
            return new Response($reservationJson, Response::HTTP_NOT_FOUND);
        }
        if ($seance->getDateProjection() > new \DateTime()) {
            $reservationJson = json_encode(['Code' => '404', 'Erreur' => "Cette séance n'éxiste plus"]);
            return new Response($reservationJson, Response::HTTP_NOT_FOUND);
        }
        if ($seance->getNbPlace() < $reservation->getNbPlaces()) {
            $reservationJson = json_encode(['Code' => '400', 'Erreur' => "Cette séance n'a plus assez de place"]);
            return new Response($reservationJson, Response::HTTP_BAD_REQUEST);
        }
        $montant = $reservation->getMontant();
        $nbPlaces = $reservation->getNbPlaces();
        $dateReservation = new \DateTime();
        $newReservation->setDateReservation($dateReservation);
        $newReservation->setMontant($montant);
        $newReservation->setNbPlaces($nbPlaces);
        $newReservation->setSeance($seance);
        $newReservation->setUser($user);
        $seance->setNbPlace($seance->getNbPlace() - $nbPlaces);
        $entityManager->persist($newReservation);
        $entityManager->persist($seance);
        $entityManager->flush();
        $reservationJson = json_encode(["Code" => '200', "Message" => "La réservation a bien été effectuée pour le film " . $seance->getFilm()->getTitre() . "."]);
        return new Response($reservationJson, Response::HTTP_OK);

    }
}
