<?php


namespace Anyt\BugTrackerBundle\TestsDoctrineExtensions\DBAL\Types;

use Anyt\BugTrackerBundle\DoctrineExtensions\DBAL\Types\UTCDateTimeType;

class UTCDateTimeTypeTest extends \PHPUnit_Framework_TestCase
{

    const DATE_FORMATTED = '2015-07-31 12:30:30';

    private $platform;

    /**
     * @var UTCDateTimeType
     */
    private $object;

    protected function setUp()
    {
        $this->platform = $this->getMock('Doctrine\DBAL\Platforms\AbstractPlatform');
        $this->platform->expects($this->any())
            ->method('getDateTimeFormatString')
            ->will($this->returnValue('Y-m-d H:i:s'));

        $reflection = new \ReflectionClass('Anyt\BugTrackerBundle\DoctrineExtensions\DBAL\Types\UTCDateTimeType');

        $this->object = $reflection->newInstanceWithoutConstructor();

    }

    public function testConvertToDatabaseValue()
    {
        $date = new \DateTime(self::DATE_FORMATTED, new \DateTimeZone('UTC'));
        $value = $this->object->convertToDatabaseValue($date, $this->platform);
        $this->assertEquals(self::DATE_FORMATTED, $value);

        $value = $this->object->convertToDatabaseValue(null, $this->platform);
        $this->assertNull($value);


    }

    public function testConvertToPHPValue()
    {
        $date = new \DateTime(self::DATE_FORMATTED, new \DateTimeZone('UTC'));
        $value = $this->object->convertToPHPValue(self::DATE_FORMATTED, $this->platform);
        $this->assertEquals($date, $value);

        $value = $this->object->convertToPHPValue(null, $this->platform);
        $this->assertNull($value);
    }
}