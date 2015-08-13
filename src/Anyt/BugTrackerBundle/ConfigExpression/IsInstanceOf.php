<?php


namespace Anyt\BugTrackerBundle\ConfigExpression;

use Oro\Bundle\WorkflowBundle\Model\Condition\AbstractComparison;

class IsInstanceOf extends AbstractComparison
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'instance_of';
    }

    /**
     * {@inheritdoc}
     */
    protected function doCompare($left, $right)
    {
        return $left instanceof $right;
    }
}
