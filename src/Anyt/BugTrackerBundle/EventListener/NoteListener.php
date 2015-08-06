<?php

namespace Anyt\BugTrackerBundle\EventListener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Anyt\BugTrackerBundle\Entity\Issue;
use Oro\Bundle\NoteBundle\Entity\Note;

/**
 * Class NoteListener.
 */
class NoteListener
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Doctrine\ORM\UnitOfWork
     */
    private $uow;

    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $this->em = $args->getEntityManager();
        $this->uow = $this->em->getUnitOfWork();

        foreach ($this->uow->getScheduledEntityInsertions() as $entity) {
            $this->updateIssue($entity);
        }

        foreach ($this->uow->getScheduledEntityUpdates() as $entity) {
            $this->updateIssue($entity);
        }

        foreach ($this->uow->getScheduledEntityDeletions() as $entity) {
            $this->updateIssue($entity);
        }
    }

    private function updateIssue($entity)
    {
        if ($entity instanceof Note && $entity->getTarget() instanceof Issue) {
            /** @var Issue $issue */
            $issue = $entity->getTarget();
            $issue->setUpdated(new \DateTime('now', new \DateTimeZone('UTC')));

            $classMetadata = $this->em->getMetadataFactory()->getMetadataFor(get_class($issue));

            $this->uow->recomputeSingleEntityChangeSet($classMetadata, $issue);
        }
    }
}
