<?php
declare(strict_types = 1);

namespace SimplyAdmire\Koop;

use Assert\Assertion;

final class Configuration
{

    /**
     * The base URI of the API
     *
     * @var string
     */
    private $apiBaseUrl = 'https://zoekdienst.overheid.nl/sru/Search/';

    /**
     * A possible configuration array for the guzzle client
     *
     * @var array
     */
    private $clientConfiguration = [];

    public function __construct(array $configuration = [])
    {
        // Validate and set base url
        if (isset($configuration['apiBaseUrl'])) {
            Assertion::url($configuration['apiBaseUrl'], 'apiBaseUrl has to be a valid url');
            $this->apiBaseUrl = rtrim($configuration['apiBaseUrl'], '/') . '/';
        }

        // Check if clientConfiguration is set and valid
        if (isset($configuration['clientConfiguration'])) {
            Assertion::isArray($configuration['clientConfiguration'], 'clientConfiguration has to be an array');
            $this->clientConfiguration = $configuration['clientConfiguration'];
        }
    }

    public function getClientConfiguration(): array
    {
        return $this->clientConfiguration;
    }

    public function getApiBaseUrl(): string
    {
        return $this->apiBaseUrl;
    }

}
