<?php


namespace Anyt\BugTrackerBundle\ImportExport\TemplateFixture;

use Anyt\BugTrackerBundle\Entity\IssueResolution;
use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;

class IssueResolutionFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'Anyt\BugTrackerBundle\Entity\IssueResolution';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData('incomplete');
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new IssueResolution();
    }

    /**
     * @param string $key
     * @param IssueResolution $entity
     */
    public function fillEntityData($key, $entity)
    {
        switch ($key) {
            case 'incomplete':
                $entity
                    ->setName($key)
                    ->setTitle(strtoupper($key));

                return;

        }

        parent::fillEntityData($key, $entity);
    }
}