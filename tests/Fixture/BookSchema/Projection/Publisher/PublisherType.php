<?php

namespace Honeybee\Tests\Elasticsearch2\Fixture\BookSchema\Projection\Publisher;

use Honeybee\Tests\Elasticsearch2\Fixture\BookSchema\Projection\ProjectionType;
use Trellis\Runtime\Attribute\Text\TextAttribute;

class PublisherType extends ProjectionType
{
    public function __construct()
    {
        parent::__construct(
            'Publisher',
            [
                new TextAttribute('name', $this, [ 'mandatory' => true ]),
                new TextAttribute('description', $this)
            ]
        );
    }

    public static function getEntityImplementor()
    {
        return Publisher::CLASS;
    }
}
