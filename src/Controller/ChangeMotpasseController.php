<?php

// src/Controller/ChangeMotpasseController.php
namespace App\Controller;

use App\Form\ModificationMotPasseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ChangeMotpasseController extends AbstractController
{
    #[Route('/change/motpasse', name: 'app_change_motpasse')]
    public function index(Request $request, EntityManagerInterface $em, Security $security, UserPasswordHasherInterface $hasher): Response
    {
        $user = $security->getUser(); // Récupérer l'utilisateur connecté

        // Vérifiez si l'utilisateur est connecté
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour changer votre mot de passe.');
            return $this->redirectToRoute('app_login'); // Redirige vers la page de connexion
        }

      // Créer le formulaire pour modifier le mot de passe
      $form = $this->createForm(ModificationMotPasseType::class);
      $form->handleRequest($request); // Gérer la requête pour le formulaire

      // Vérifiez si le formulaire est soumis et valide
      if ($form->isSubmitted() && $form->isValid()) {
          // Récupérer les données du formulaire
          $data = $form->getData();
          $newPassword = $data['newPassword']; // Nouveau mot de passe

            // Vérifiez si le nouvel mot de passe n'est pas le même que l'ancien
            if (password_verify($newPassword, $user->getPassword())) {
                $this->addFlash('error', 'Vous ne pouvez pas réutiliser votre ancien mot de passe.');
                return $this->redirectToRoute('app_change_motpasse'); 
            }

           // Hacher le nouveau mot de passe et le mettre à jour dans l'utilisateur
           $hashedPassword = $hasher->hashPassword($user, $newPassword);
           $user->setPassword($hashedPassword);

           // Persiste les changements dans la base de données
           $em->persist($user);
           $em->flush();

           // Ajouter un message de succès et rediriger vers la page d'accueil
           $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
           return $this->redirectToRoute('app_accueil'); // Rediriger vers la page d'accueil ou une autre page
       }

       // Rendre la page avec le formulaire
       return $this->render('change_motpasse/index.html.twig', [
           'form' => $form->createView(), // Passer le formulaire à la vue
       ]);
   }
}
