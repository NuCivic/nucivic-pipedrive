<?php
namespace NucivicPipedrive\Pipedrive\Library;
use Curl\Curl;
use NucivicPipedrive\Pipedrive\Exceptions\APIException;

class HTTP
{
    /**
     * Endpoint for Pipedrive, HTTP or HTTPS (configurable).
     * @var string
     */
    private $endpoint;
    /**
     * The Pipedrive API token.
     * @var string
     */
    private $token;
    /**
     * Set token and endpoint.
     */
    public function __construct($token, $endpoint)
    {
        $this->token = $token;
        $this->endpoint = $endpoint;
    }
    /**
     * HTTP GET wrapper for Curl.
     * For requests without additional query parameters.
     *
     * @param string $url URL to GET request to.
     * @return mixed Response data.
     */
    public function get($url, $params = array())
    {
        $params['api_token'] = $this->token;
        $curl = new Curl();
        $curl->get($this->endpoint . $url, $params);
        $curl->close();
        return $curl->response;
    }
    /**
     * Download a file from export
     */
    public function downloadAuth($url, $path, $params = array()) {
        $params['api_token'] = $this->token;
        $curl = new Curl();
        $curl->setOpt(CURLOPT_FOLLOWLOCATION, TRUE);
        $url .= '?' . http_build_query($params);
        echo $url . "\n";
        $curl->download($url, $path);
        $curl->close();
    }
}
