<?php

namespace Honeybee\Tests\Elasticsearch2\Fixture\BookSchema\Projection;

use Honeybee\Projection\ProjectionType as BaseProjectionType;

abstract class ProjectionType extends BaseProjectionType
{
    const VENDOR = 'HoneybeeCmf';

    const PACKAGE = 'ProjectionFixtures';

    public function getPackage()
    {
        return self::PACKAGE;
    }

    public function getVendor()
    {
        return self::VENDOR;
    }
}
