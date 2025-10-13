<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // Obtenez la session pour suivre les tentatives de connexion
        $session = $request->getSession();
        $loginAttempts = $session->get('login_attempts', 0);
        $lockoutTime = $session->get('lockout_time');

        // Vérifier si le compte est verrouillé
        if ($loginAttempts >= 3 && $lockoutTime && time() < $lockoutTime) {
            return $this->render('security/locked.html.twig', [
                'message' => 'Votre compte est temporairement verrouillé. Veuillez réessayer plus tard.',
            ]);
        }

        // Obtenez l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // Dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // Si la connexion échoue
        if ($error) {
            $session->set('login_attempts', ++$loginAttempts);
            if ($loginAttempts >= 3) {
                // Verrouiller le compte pour 1 minute
                $session->set('lockout_time', time() + 60); // 1 minute
            }
        } else {
            // Réinitialiser après une connexion réussie
            $session->remove('login_attempts');
            $session->remove('lockout_time');
        }

        // Rendre le template de connexion avec les informations nécessaires
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'login_attempts' => $loginAttempts,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode peut rester vide. Elle sera interceptée par le pare-feu.
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
