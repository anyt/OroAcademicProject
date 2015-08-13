<?php

namespace Anyt\BugTrackerBundle\Migrations\Data\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Anyt\BugTrackerBundle\Entity\IssuePriority;

class LoadIssuePriorityData extends AbstractFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $priorityRepository = $manager->getRepository('AnytBugTrackerBundle:IssuePriority');

        $types = [
            IssuePriority::TYPE_BLOCKER,
            IssuePriority::TYPE_CRITICAL,
            IssuePriority::TYPE_MAJOR,
            IssuePriority::TYPE_TRIVIAL,
        ];

        foreach ($types as $weight => $priorityType) {
            /** @var IssuePriority $issuePriority */
            $issuePriority = $priorityRepository->findOneBy(['name' => $priorityType]);
            if (!$issuePriority) {
                $issuePriority = new IssuePriority();
                $issuePriority
                    ->setName($priorityType)
                    ->setTitle(ucfirst($priorityType))
                    ->setWeight($weight);
            }

            $manager->persist($issuePriority);
        }

        $manager->flush();
    }
}
