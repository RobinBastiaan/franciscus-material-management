<?php

namespace App\EventListener;

use App\Entity\Loan;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class DoneLoanSubscriber implements EventSubscriberInterface
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::postUpdate,
        ];
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->markAsDone('update', $args);
    }

    private function markAsDone(string $action, LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Loan) {
            return;
        }

        if ($entity->getReturnedState() === null) { // the loan is returned
            return;
        }

        $entity->setDateReturned(new DateTime('now'));
    }
}
