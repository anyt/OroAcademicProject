<?php

namespace Anyt\BugTrackerBundle\EventListener;

use Anyt\BugTrackerBundle\IssueCodeGenerator\GeneratorInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Anyt\BugTrackerBundle\Entity\Issue;

/**
 * Class IssueCodeListener.
 */
class IssueCodeListener
{
    /**
     * @var GeneratorInterface
     */
    private $generator;

    /**
     * IssueCodeListener constructor.
     */
    public function __construct(GeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        if ($entity instanceof Issue) {
            $code = $this->generator->generate($entity);
            $entity->setCode($code);
            $em->flush($entity);
        }
    }
}
