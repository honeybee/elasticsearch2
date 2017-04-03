<?php

namespace Honeybee\Elasticsearch2\DataAccess\Storage\Projection;

use Assert\Assertion;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Honeybee\Elasticsearch2\DataAccess\Storage\ElasticsearchStorage;
use Honeybee\Infrastructure\Config\ConfigInterface;
use Honeybee\Infrastructure\Config\Settings;
use Honeybee\Infrastructure\Config\SettingsInterface;
use Honeybee\Infrastructure\DataAccess\Connector\ConnectorInterface;
use Honeybee\Infrastructure\DataAccess\Storage\StorageReaderInterface;
use Honeybee\Infrastructure\DataAccess\Storage\StorageReaderIterator;
use Honeybee\Projection\ProjectionTypeMap;
use Psr\Log\LoggerInterface;

class ProjectionReader extends ElasticsearchStorage implements StorageReaderInterface
{
    const READ_ALL_LIMIT = 10;

    protected $offset = 0;

    protected $projection_type_map;

    public function __construct(
        ConnectorInterface $connector,
        ConfigInterface $config,
        LoggerInterface $logger,
        ProjectionTypeMap $projection_type_map
    ) {
        parent::__construct($connector, $config, $logger);

        $this->projection_type_map = $projection_type_map;
    }

    public function readAll(SettingsInterface $settings = null)
    {
        $settings = $settings ?: new Settings;

        $data = [];

        $default_limit = $this->config->get('limit', self::READ_ALL_LIMIT);
        $limit = $settings->get('limit', $default_limit);

        $query_params = [
            'index' => $this->getIndex(),
            'type' => $this->getType(),
            'size' => $limit,
            'body' => [ 'query' => [ 'match_all' => [] ] ]
        ];

        if (!$settings->get('first', true)) {
            if (!$this->offset) {
                return $data;
            }
            $query_params['from'] = $this->offset;
        }

        $raw_result = $this->connector->getConnection()->search($query_params);

        $result_hits = $raw_result['hits'];
        foreach ($result_hits['hits'] as $data_row) {
            $data[] = $this->createResult($data_row['_source']);
        }

        if ($result_hits['total'] === $this->offset + 1) {
            $this->offset = 0;
        } else {
            $this->offset += $limit;
        }

        return $data;
    }

    public function read($identifier, SettingsInterface $settings = null)
    {
        try {
            $result = $this->connector->getConnection()->get(
                [
                    'index' => $this->getIndex(),
                    'type' => $this->getType(),
                    'id' => $identifier
                ]
            );
        } catch (Missing404Exception $missing_error) {
            return null;
        }

        return $this->createResult($result['_source']);
    }

    public function getIterator()
    {
        return new StorageReaderIterator($this);
    }

    private function createResult(array $result_data)
    {
        Assertion::notEmptyKey($result_data, self::OBJECT_TYPE);

        return $this->projection_type_map
            ->getItem($result_data[self::OBJECT_TYPE])
            ->createEntity($result_data);
    }
}
