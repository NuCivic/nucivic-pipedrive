<?php

namespace NuCivicPipedrive\Pipedrive;

/**
 * Pipedrive export classes.
 *
 * Heavily based on https://github.com/Pinvoice/pipedrive-php-api/
 */

use NuCivicPipedrive\Pipedrive\Library\DealFields;
use NuCivicPipedrive\Pipedrive\Library\Deals;
use NuCivicPipedrive\Pipedrive\Library\PersonFields;
use NuCivicPipedrive\Pipedrive\Library\Persons;
use NuCivicPipedrive\Pipedrive\Library\Pipelines;
use NuCivicPipedrive\Pipedrive\Library\Stages;

class Pipedrive
{
    public $deals;
    public $dealfields;
    public $persons;
    public $personfields;
    public $pipelines;
    public $stages;

    /**
     * Endpoint for Pipedrive, HTTP or HTTPS (configurable).
     * @var string
     */
    private $endpoint = 'http://api.pipedrive.com/v1/';

    /**
     * The Pipedrive API token.
     * @var string
     */
    private $token = null;

    /**
     * Holds the HTTP instance for HTTP requests.
     * @var object
     */
    private $http;

    /**
     * Set HTTP with endpoint and token. Create classes for each API object.
     * @param string $token Pipedrive API token.
     */
    public function __construct($token)
    {
        $this->token = $token;
        $this->http = new HTTP($this->token, $this->endpoint);

        $this->deals = new Deals($this->http);
        $this->dealfields = new DealFields($this->http);
        $this->persons = new Persons($this->http);
        $this->personfields = new PersonFields($this->http);
        $this->pipelines = new Pipelines($this->http);
        $this->stages = new Stages($this->http);
    }

    public function isAuthenticated()
    {
        $response = $this->http->get('userSettings');
        return $response->success;
    }

}
