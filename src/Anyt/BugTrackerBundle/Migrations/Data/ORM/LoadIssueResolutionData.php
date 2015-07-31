<?php

namespace OroCRM\Bundle\CaseBundle\Migrations\Data\ORM;

use Anyt\BugTrackerBundle\Entity\Issue;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Anyt\BugTrackerBundle\Entity\IssueResolution;

class LoadIssueResolutionData extends AbstractFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $priorityRepository = $manager->getRepository('AnytBugTrackerBundle:IssueResolution');

        $types = IssueResolution::getTypes();

        foreach ($types as $weight => $resolutionType) {
            /** @var IssueResolution $IssueResolution */
            $IssueResolution = $priorityRepository->findOneBy(array('name' => $resolutionType));
            if (!$IssueResolution) {
                $IssueResolution = new IssueResolution();
                $IssueResolution
                    ->setName($resolutionType)
                    ->setTitle(ucfirst($resolutionType));
            }

            $manager->persist($IssueResolution);
        }


        $manager->flush();
    }
}
