<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;


final class UtilisateurController extends AbstractController
{
    #[Route('/liste-utilisateurs', name: 'app_utilisateur')]
    public function listeUtilisateurs(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('utilisateur/liste-utilisateurs.html.twig', [
            'users' => $users
        ]);
    }
    #[Route('/profil', name: 'app_profil')]
    public function profil(): Response
    {
        $utilisateur = $this->getUser();
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('utilisateur/profil.html.twig', [
            'utilisateur' => $utilisateur
        ]);
    }
}
