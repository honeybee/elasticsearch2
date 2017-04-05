<?php

return [
    'raw_result' => [
        '_index' => 'testing.honeybee_cmf-projection_fixtures_20160519222937',
        '_type' => 'honeybee_cmf-projection_fixtures-book-standard',
        '_id' => 'honeybee_cmf.projection_fixtures.book-a726301d-dbae-4fb6-91e9-a19188a17e71-de_DE-1',
        '_version' => 1,
        'found' => true,
        '_source' => [
            '@type' => 'honeybee_cmf.projection_fixtures.book::projection.standard',
            'identifier' => 'honeybee_cmf.projection_fixtures.book-a726301d-dbae-4fb6-91e9-a19188a17e71-de_DE-1',
            'revision' => 1,
            'uuid' => 'a726301d-dbae-4fb6-91e9-a19188a17e71',
            'language' => 'de_DE',
            'version' => 1,
            'created_at' => '2016-03-27T10:52:37.371793+00:00',
            'modified_at' => '2016-03-27T10:52:37.371793+00:00',
            'workflow_state' => 'edit',
            'workflow_parameters' => [],
            'metadata' => [],
            'title' => 'Catch 22'
        ]
    ],
    'invalid_result' => [
        '_index' => 'testing.honeybee_cmf-projection_fixtures_20160519222937',
        '_type' => 'honeybee_cmf-projection_fixtures-book-unknown',
        '_id' => 'honeybee_cmf.projection_fixtures.book-a726301d-dbae-4fb6-91e9-a19188a17e71-de_DE-1',
        '_version' => 1,
        'found' => true,
        '_source' => [
            '@type' => 'honeybee_cmf.projection_fixtures.book::projection.unknown',
            'identifier' => 'honeybee_cmf.projection_fixtures.book-a726301d-dbae-4fb6-91e9-a19188a17e71-de_DE-1',
            'revision' => 1,
            'uuid' => 'a726301d-dbae-4fb6-91e9-a19188a17e71',
            'language' => 'de_DE',
            'version' => 1,
            'created_at' => '2016-03-27T10:52:37.371793+00:00',
            'modified_at' => '2016-03-27T10:52:37.371793+00:00',
            'workflow_state' => 'edit',
            'workflow_parameters' => [],
            'metadata' => [],
            'title' => 'Catch 22'
        ]
    ]
];
