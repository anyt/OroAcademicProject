<?php


namespace Anyt\BugTrackerBundle\ImportExport\TemplateFixture;

use Anyt\BugTrackerBundle\Entity\IssuePriority;
use Anyt\BugTrackerBundle\Entity\IssueResolution;
use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;
use Anyt\BugTrackerBundle\Entity\Issue;

class IssueFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'Anyt\BugTrackerBundle\Entity\Issue';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData('Issue__summary_1');
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new Issue();
    }

    /**
     * @param string $key
     * @param Issue $entity
     */
    public function fillEntityData($key, $entity)
    {
        $userRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\UserBundle\Entity\User');
        $resolutionRepo = $this->templateManager
            ->getEntityRepository('Anyt\BugTrackerBundle\Entity\IssueResolution');
        $priorityRepo = $this->templateManager
            ->getEntityRepository('Anyt\BugTrackerBundle\Entity\IssuePriority');

        $user = $userRepo->getEntity('John Doo');
        switch ($key) {
            case 'Issue__summary_1':
                $entity
                    ->setSummary($key)
                    ->setCode('ISSUE-1')
                    ->setDescription('test')
                    ->setAssignee($user)
                    ->setOwner($user)
                    ->setType(Issue::TYPE_STORY)
                    ->setStatus(Issue::STATUS_OPEN)
                    ->addCollaborator($user)
                    ->setPriority($priorityRepo->getEntity('major'))
                    ->setResolution($resolutionRepo->getEntity('incomplete'))
                    ->setCreated(new \DateTime())
                    ->setUpdated(new \DateTime());

                return;

        }

        parent::fillEntityData($key, $entity);
    }
}