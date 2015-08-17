<?php


namespace Anyt\BugTrackerBundle\ImportExport\TemplateFixture;

use Anyt\BugTrackerBundle\Entity\IssuePriority;
use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;

class IssuePriorityFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'Anyt\BugTrackerBundle\Entity\IssuePriority';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData('major');
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new IssuePriority();
    }

    /**
     * @param string $key
     * @param IssuePriority $entity
     */
    public function fillEntityData($key, $entity)
    {
        switch ($key) {
            case 'major':
                $entity
                    ->setName($key)
                    ->setTitle(strtoupper($key))
                    ->setWeight(1);

                return;

        }

        parent::fillEntityData($key, $entity);
    }
}