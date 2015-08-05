<?php

namespace OroCRM\Bundle\CaseBundle\Migrations\Data\ORM;

use Anyt\BugTrackerBundle\Entity\Issue;
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

        $types = IssuePriority::getTypes();

        foreach ($types as $weight => $priorityType) {
            /** @var IssuePriority $issuePriority */
            $issuePriority = $priorityRepository->findOneBy(array('name' => $priorityType));
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
