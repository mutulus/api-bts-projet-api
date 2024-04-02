<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\CategorieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class UserController extends AbstractController
{
    #[Route('/register', name: 'app_user_register',methods: ['POST'])]
    public function register(\Symfony\Component\HttpFoundation\Request $request, SerializerInterface $serializer,EntityManagerInterface $entityManager,UserPasswordHasherInterface $hasher,ValidatorInterface $validator): Response
    {
        $userBDD=new User();
        $bodyrequest = $request->getContent();
        $user = $serializer->deserialize($bodyrequest, User::class, 'json');
        $userBDD->setRoles($user->getRoles());
        $userBDD->setEmail($user->getEmail());
        $userBDD->setPassword($hasher->hashPassword($user,$user->getPassword()));
        $erreurs=$validator->validate($userBDD);
        if (count($erreurs)!=0){
            echo $erreurs;
        }else {
            $entityManager->persist($userBDD);
            $entityManager->flush();
            $userJson=$serializer->serialize($userBDD,'json',['groups'=>'user_info']);
            return new Response( $userJson,Response::HTTP_CREATED, ["content-type" => "application/json"]);
        }

    }
}
