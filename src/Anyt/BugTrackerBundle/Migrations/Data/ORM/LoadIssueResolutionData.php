<?php

namespace OroCRM\Bundle\CaseBundle\Migrations\Data\ORM;

use Anyt\BugTrackerBundle\Entity\Issue;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Anyt\BugTrackerBundle\Entity\IssueResolution;

class LoadIssueResolutionData extends AbstractFixture
{

    protected static $types = [
        IssueResolution::TYPE_UNSEROLVED => 'Unresolved',
        IssueResolution::TYPE_FIXED => 'Fixed',
        IssueResolution::TYPE_WONT_FIX => 'Won\'t fix',
        IssueResolution::TYPE_DUPLICATE => 'Duplicate',
        IssueResolution::TYPE_INCOMPLETE => 'Incomplete',
        IssueResolution::TYPE_CANNOT_REPRODUCE => 'Cannot reproduce',
        IssueResolution::TYPE_DONE => 'Done',
        IssueResolution::TYPE_WONT_DO => 'Won\'t do',
    ];

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $priorityRepository = $manager->getRepository('AnytBugTrackerBundle:IssueResolution');

        $types = self::$types;

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
