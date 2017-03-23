<?php
declare(strict_types = 1);

namespace SimplyAdmire\Koop;

use GuzzleHttp\ClientInterface as HttpClientInterface;
use SimplyAdmire\Koop\Search\Query;
use SimplyAdmire\Koop\Search\QueryResult;

final class Client
{

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var
     */
    private $client;

    /**
     * @param Configuration $configuration
     * @param HttpClientInterface|null $client
     */
    public function __construct(Configuration $configuration, HttpClientInterface $client = null)
    {
        $this->configuration = $configuration;

        if ($client === null) {
            $this->client = new \GuzzleHttp\Client($this->configuration->getClientConfiguration());
        } else {
            $this->client = $client;
        }
    }

    public function execute(Query $query, array $options = []): QueryResult
    {
        return new QueryResult($this->configuration, $this->client, $query, $options);
    }

}
