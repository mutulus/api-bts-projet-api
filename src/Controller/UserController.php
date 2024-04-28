<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\CategorieRepository;
use App\Repository\FilmRepository;
use App\Repository\UserRepository;
use App\Services\Validateurs;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class UserController extends AbstractController
{
    #[Route('/register', name: 'app_user_register', methods: ['POST'])]
    public function register(\Symfony\Component\HttpFoundation\Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher, ValidatorInterface $validator, UserRepository $userRepository, Validateurs $validateurs): Response
    {

        $userBDD = new User();
        $bodyrequest = $request->getContent();
        $user = $serializer->deserialize($bodyrequest, User::class, 'json');
        $userBDD->setRoles(['ROLE_USER']);
        $userBDD->setEmail($user->getEmail());
        $userBDD->setPassword($hasher->hashPassword($user, $user->getPassword()));
        $erreurs = $validator->validate($userBDD);

        if (count($erreurs) != 0 or !filter_var($userBDD->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $userJson = json_encode(["Code" => "400", "Erreur" => "L'email est invalide"]);
            return new Response($userJson, Response::HTTP_BAD_REQUEST, ['content-type' => 'Application/json']);
        }
        if ($userRepository->findOneBy(['email' => $user->getEmail()])) {
            $userJson = json_encode(["Code" => "400", "Erreur" => "L'email est déjà utilisé"]);
            return new Response($userJson, Response::HTTP_BAD_REQUEST, ['content-type' => 'Application/json']);
        }
        if (!$validateurs->verifMdp($user->getPassword())) {
            $userJson = json_encode(["Code" => "400", "Erreur" => "Le mot de passe n'est pas assez complexe"]);
            return new Response($userJson, Response::HTTP_BAD_REQUEST, ['content-type' => 'Application/json']);
        }
        $entityManager->persist($userBDD);
        $entityManager->flush();
        $userJson = $serializer->serialize([$userBDD, "code" => 201], 'json', ['groups' => 'user_info']);
        return new Response($userJson, Response::HTTP_CREATED, ["content-type" => "application/json"]);

    }
//    #[Route('/login', name: 'app_user_login', methods: ['POST'])]
//    public function login(\Symfony\Component\HttpFoundation\Request $request, SerializerInterface $serializer,UserRepository $userRepository,ValidatorInterface $validator,UserPasswordHasherInterface $hasher,JWTTokenManagerInterface $JWTManager): Response
//    {
//        $bodyrequest = $request->getContent();
//        $userLog = $serializer->deserialize($bodyrequest, User::class, 'json');
//        $user = $userRepository->findOneBy(['email' => $userLog->getEmail()]);
//        $erreurs = $validator->validate($userLog);
//        if (count($erreurs) != 0 or !filter_var($userLog->getEmail(), FILTER_VALIDATE_EMAIL)) {
//            $userJson = json_encode(["Code" => "400", "Erreur" => "L'email n'est pas au bon format"]);
//            return new Response($userJson, Response::HTTP_BAD_REQUEST, ['content-type' => 'Application/json']);
//        }
//        if ($user === null) {
//            $userJson = json_encode(["Code" => "400", "Erreur" => "L'email est invalide"]);
//            return new Response($userJson, Response::HTTP_BAD_REQUEST, ['content-type' => 'Application/json']);
//        }
//        if (!$hasher->isPasswordValid($userLog->getPassword(), $user->getPassword())) {
//            $userJson = json_encode(["Code" => "400", "Erreur" => "Le mot de passe est invalide"]);
//            return new Response($userJson, Response::HTTP_BAD_REQUEST, ['content-type' => 'Application/json']);
//        }
//        $userJson = $serializer->serialize([ "code" => 200], 'json');
//        $reponse = new Response($userJson, Response::HTTP_OK, ['content-type' => 'application/json']);
//        $reponse->headers->set('Authorization', 'JWT: '.$JWTManager->create($userLog));
//        return $reponse;
//    }
}
