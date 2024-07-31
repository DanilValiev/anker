<?php

namespace App\Shared\Doctrine\Compatibility\CustomTypes;

use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeTzType;

/**
 * DateTime type saving additional timezone information (with microsecond retrieval support).
 * convertToDatabaseValue crops microseconds
 *
 * @see https://www.doctrine-project.org/projects/doctrine-dbal/en/2.10/reference/known-vendor-issues.html#datetime-datetimetz-and-time-types
 */
class DateTimeTzWithMicrosecondsType extends DateTimeTzType
{
    const DATE_FORMAT = 'Y-m-d H:i:s.uO';

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'datetimetz_ms';
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'TIMESTAMP WITHOUT TIME ZONE';
    }

    /**
     * {@inheritdoc}
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return $value->format(self::DATE_FORMAT);
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'DateTime']);
    }

    /**
     * {@inheritdoc}
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof DateTimeInterface) {
            return $value;
        }

        $val = DateTime::createFromFormat(self::DATE_FORMAT, $value);
        if (!$val) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeTzFormatString()
            );
        }

        return $val;
    }
}
