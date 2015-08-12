<?php


namespace Anyt\BugTrackerBundle\Tests\Unit\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Anyt\BugTrackerBundle\DependencyInjection\AnytBugTrackerExtension;

class AnytBugTrackerExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $configuration = new ContainerBuilder();
        $loader        = new AnytBugTrackerExtension();
        $loader->load([], $configuration);
        $this->assertTrue($configuration instanceof ContainerBuilder);
    }
}
