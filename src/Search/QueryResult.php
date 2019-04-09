<?php
declare(strict_types = 1);

namespace SimplyAdmire\Koop\Search;

use Assert\Assertion;
use GuzzleHttp\ClientInterface as HttpClientInterface;
use SimplyAdmire\Koop\Client;
use SimplyAdmire\Koop\Configuration;
use SimplyAdmire\Koop\Model\Publication;

final class QueryResult implements \Iterator, \Countable
{

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Query
     */
    private $query;

    /**
     * @var array
     */
    private $options;

    /**
     * @var integer
     */
    private $index = 0;

    /**
     * @var array
     */
    private $pages = [];

    /**
     * @var integer
     */
    private $totalRows = 0;

    /**
     * @var integer
     */
    private $pageSize = 4200;

    public function __construct(Configuration $configuration, HttpClientInterface $client, Query $query, array $options = [])
    {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->query = $query;
        $this->options = $options;
        $this->pageSize = $query->getPageSize();

        $this->addPage(0);
    }

    private function addPage(int $page): void
    {
        $this->query->setOffset(
            $page * $this->pageSize
        );

        $url = $this->buildRequestUrl((string)$this->query);
        $options = $this->buildRequestConfiguration($this->options);

        $response = $this->client->request('GET', $url, $options);

        $xml = $response->getBody()->getContents();
        Assertion::notEmpty($xml, 'An error occurred while calling the API');

        $xmlObject = \simplexml_load_string($xml);
        if ($xmlObject === false || !isset($xmlObject->records)) {
            return;
        }

        $this->totalRows = \min($this->query->getLimit(), (int)$xmlObject->numberOfRecords);

        $this->pages[$page] = [];
        foreach ($xmlObject->records->children() as $xmlRecordObject) {
            $this->pages[$page][] = Publication::createFromXml($xmlRecordObject->recordData->gzd->asXML());
        }
    }

    private function buildRequestConfiguration(array $options = []): array
    {
        // Set required options
        $options['headers'] = \array_merge(
            isset($options['headers']) ? $options['headers'] : [],
            [
                'Accept' => 'application/xml'
            ]
        );

        // Some default settings that can be overwritten by feeding the configuration object with a client configuration
        $options = \array_merge(
            [
                'connect_timeout' => 2
            ],
            $options
        );

        return $options;
    }

    private function buildRequestUrl(string $path): string
    {
        return sprintf('%s?%s', $this->configuration->getApiBaseUrl(), $path);
    }

    public function current(): Publication
    {
        $page = 0;
        $index = 0;

        if ($this->index > 0) {
            $page = floor($this->index / $this->pageSize);
            $index = $this->index % $this->pageSize;
        }

        if (!isset($this->pages[$page])) {
            $this->addPage((int)$page);
        }

        return $this->pages[$page][$index];
    }

    public function next(): void
    {
        $this->index++;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return $this->index < $this->totalRows;
    }

    public function rewind(): void
    {
        $this->index = 0;
    }

    public function count(): int
    {
        return $this->totalRows;
    }

}
