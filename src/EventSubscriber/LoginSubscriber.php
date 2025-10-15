<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Doctrine\ORM\EntityManagerInterface;

class LoginSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }

    public function onLoginSuccess(LoginSuccessEvent $event)
    {
        $user = $event->getUser();
        if (!$user) {
            return;
        }

        // Récupérer l’historique actuel
        $historique = $user->getHistoriqueConnexions() ?? [];

        // ➕ Ajouter la nouvelle date/heure avec fuseau horaire France
        $historique[] = (new \DateTime('now', new \DateTimeZone('Europe/Paris')))
            ->format('Y-m-d H:i:s');

        // Mettre à jour l’historique
        $user->setHistoriqueConnexions($historique);

        // Sauvegarder en base
        $this->em->persist($user);
        $this->em->flush();
    }
}
