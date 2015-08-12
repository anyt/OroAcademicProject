<?php

namespace Anyt\BugTrackerBundle\DoctrineExtensions\DBAL\Types;

use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

class UTCDateTimeType extends DateTimeType
{
    /**
     * @var null|\DateTimeZone
     */
    private static $utc = null;

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed|null|void
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $this->setUTC();

        $value->setTimeZone(self::$utc);

        return $value->format($platform->getDateTimeFormatString());
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return \DateTime|void
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $this->setUTC();

        $val = \DateTime::createFromFormat($platform->getDateTimeFormatString(), $value, self::$utc);

        if (!$val) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return $val;
    }

    private function setUTC()
    {
        if (is_null(self::$utc)) {
            self::$utc = new \DateTimeZone('UTC');
        }
    }
}
