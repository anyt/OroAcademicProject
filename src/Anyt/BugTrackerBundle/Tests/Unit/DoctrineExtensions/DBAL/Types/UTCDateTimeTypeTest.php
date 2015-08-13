<?php


namespace Anyt\BugTrackerBundle\Tests\Unit\DoctrineExtensions\DBAL\Types;

use Anyt\BugTrackerBundle\DoctrineExtensions\DBAL\Types\UTCDateTimeType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Class UTCDateTimeTypeTest
 * @package Anyt\BugTrackerBundle\Tests\Unit\DoctrineExtensions\DBAL\Types
 */
class UTCDateTimeTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UTCDateTimeType
     */
    protected $type;

    /**
     * @var AbstractPlatform
     */
    protected $platform;

    public function assertPreConditions()
    {
        $refClass = new \ReflectionClass('Anyt\BugTrackerBundle\DoctrineExtensions\DBAL\Types\UTCDateTimeType');

        $this->type = $refClass->newInstanceWithoutConstructor();

        $this->platform = $this->getMock('Doctrine\DBAL\Platforms\AbstractPlatform');
        $this->platform->expects($this->any())->method('getDateTimeFormatString')->willReturn('Y-m-d H:i:s');
    }

    /**
     * @dataProvider dataTimeDataProvider
     * @param $dateTimeString
     * @param $dateTimeObject
     */
    public function testConvertToDatabaseValue($dateTimeString, $dateTimeObject)
    {
        $this->assertEquals($dateTimeString, $this->type->convertToDatabaseValue($dateTimeObject, $this->platform));
    }

    /**
     * @dataProvider dataTimeDataProvider
     * @param $dateTimeString
     * @param $dateTimeObject
     */
    public function testConvertToPHPValue($dateTimeString, $dateTimeObject)
    {
        $this->assertEquals($dateTimeObject, $this->type->convertToPHPValue($dateTimeString, $this->platform));
    }

    /**
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function testConvertToPHPValueConversionException()
    {
        $this->type->convertToPHPValue('false value', $this->platform);
    }

    /**
     * @return array
     */
    public function dataTimeDataProvider()
    {
        $dateTimeString = '2015-08-11 20:57:04';

        return [
            [$dateTimeString, new \DateTime($dateTimeString, new \DateTimeZone('UTC'))],
            [null, null]
        ];
    }

}
