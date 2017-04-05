<?php

namespace Honeybee\Elasticsearch2\Storage;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use Honeybee\Infrastructure\Config\Settings;
use Honeybee\Infrastructure\Config\SettingsInterface;
use Honeybee\Infrastructure\DataAccess\Storage\StorageWriterInterface;

abstract class ElasticsearchStorageWriter extends ElasticsearchStorage implements StorageWriterInterface
{
    protected function writeData($identifier, array $data, SettingsInterface $settings = null)
    {
        $data = [
            'index' => $this->getIndex(),
            'type' => $this->getType(),
            'id' => $identifier,
            'body' => $data
        ];

        $this->connector->getConnection()->index(array_merge($data, $this->getParameters('index')));
    }

    protected function writeBulk(array $documents, SettingsInterface $settings = null)
    {
        // @todo support bulk parameters and batching
        $index = $this->getIndex();
        $type = $this->getType();

        $data = [];
        foreach ($documents as $identifier => $document) {
            $data['body'][] = [
                'index' => [
                    '_index' => $index,
                    '_type' => $type,
                    '_id' => $identifier
                ]
            ];
            $data['body'][] = $document;
        }

        $this->connector->getConnection()->bulk($data);
    }

    public function delete($identifier, SettingsInterface $settings = null)
    {
        if (!is_string($identifier) || empty($identifier)) {
            $this->logger->debug(__METHOD__ . ' - Ignoring invalid identifier.');
            return;
        }

        try {
            $data = [
                'index' => $this->getIndex(),
                'type' => $this->getType(),
                'id' => $identifier
            ];

            /*
             * @todo atm deleting a document when the index does not exists triggers the index to be created.
             * this is a baad sideeffect in Elasticsearch. remove this code as soon as the behaviour is fixed.
             * @link https://github.com/elastic/elasticsearch/issues/15451
             */

            // override potential refresh on read
            $connection = $this->connector->getConnection();
            $getParameters = $this->getParameters('get');
            $getParameters['refresh'] = false;
            $connection->get(array_merge($data, $getParameters));

            $connection->delete(array_merge($data, $this->getParameters('delete')));
        } catch (Missing404Exception $error) {
            error_log(__METHOD__ . ' - ' . $error->getMessage());
        }
    }
}
