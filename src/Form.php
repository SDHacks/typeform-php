<?php

namespace Typeform;

use GuzzleHttp;

class Form {

    /**
     * HTTP client
     *
     * @var  GuzzleHttp\Client
     */
    protected $http;

    /**
     * Typeform API key
     *
     * @var int
     */
    protected $apiKey;

    /**
     * Typeform form ID
     *
     * @var int
     */
    protected $formId;

    /**
     * Current page
     *
     * @var int
     */
    protected $page;

    /**
     * Result limit
     *
     * @var int
     */
    protected $limit;

    /**
     * Start date
     *
     * @var
     */
    protected $since;

    /**
     * End date
     *
     * @var
     */
    protected $until;

    /**
     * Whether or not to get completed results
     *
     * @var bool
     */
    protected $completed;

    /**
     * Questions
     *
     * @var []
     */
    protected $questions;

    /**
     * Responses
     *
     * @var []
     */
    protected $responses;

    /**
     * Statistics
     *
     * @var []
     */
    protected $stats;

    /**
     * @param GuzzleHttp\Client $http
     * @param $apiKey
     * @param $formId
     * @param int $limit
     * @param null $since
     * @param null $until
     * @param int $page
     * @param bool $completed
     * @param bool|false $raw
     */
    public function __construct(GuzzleHttp\Client $http, $apiKey, $formId, $limit = 50, $since = null, $until = null, $page = 0, $completed = true, $raw = false)
    {
        $this->setHttp($http);
        $this->setApiKey($apiKey);
        $this->setFormId($formId);
        $this->setSince($since);
        $this->setUntil($until);
        $this->setLimit($limit);
        $this->setPage($page);
        $this->setCompleted($completed);

        return $this->getForm($raw);
    }

    /**
     * Requests the form from Typeform
     *
     * @param bool $raw
     * @return $this|mixed
     */
    protected function getForm($raw = false)
    {
        // Calculate offset
        $offset = $this->getPage() * $this->getLimit();

        $params = [
            'key' => $this->getApiKey(),
            'offset' => $offset,
            'limit' => $this->getLimit(),
            'completed' => (string) $this->getCompleted()
        ];

        if ($this->getSince()) {
            $params['since'] = $this->getSince();
        }

        if ($this->getUntil()) {
            $params['until'] = $this->getUntil();
        }

        // Get data
        $response = $this->http->get($this->getFormId(), [
            'query' => $params
        ]);

        $body = $response->getBody();
        $data = json_decode($body, true);
        if (is_null($data) && strpos($body, 'login') !== false) {
            throw new \Exception('The supplied API key is not valid');
        }

        if ($raw) {
            return $data;
        }
        else {
            $this->initializeForm($data);
        }

        return $this;
    }

    /**
     * Initializes the form with data
     *
     * @param $data
     */
    protected function initializeForm($data)
    {
        $questionArray = [];
        foreach ($data['questions'] as $question) {
            $questionArray[$question['id']] = $question['question'];
        }
        $this->setQuestions($questionArray);
        $this->setResponses($data['responses']);
        $this->setStats($data['stats']);
    }

    /**
     * Advances form to next page
     *
     * @return Form
     */
    public function nextPage()
    {
        $page = $this->getPage();
        return $this->setPage(++$page);
    }

    /**
     * Advances to next page and return results
     *
     * @param bool $raw
     * @return $this|mixed|Form
     */
    public function getNextPage($raw = false)
    {
        // Advance to next page first
        $this->nextPage();

        return $this->getForm($raw);
    }

    /**
     * Pulls form to previous page
     *
     * @return Form
     */
    public function prevPage()
    {
        $page = $this->getPage();
        return $this->setPage(--$page);
    }

    /**
     * Goes to previous page and return results
     *
     * @param bool $raw
     * @return $this|mixed|Form
     */
    public function getPrevPage($raw = false)
    {
        // Advance to next page first
        $this->prevPage();

        return $this->getForm($raw);
    }

    /**
     * @return GuzzleHttp\Client
     */
    public function getHttp()
    {
        return $this->http;
    }

    /**
     * @param GuzzleHttp\Client $http
     */
    public function setHttp($http)
    {
        $this->http = $http;
    }

    /**
     * @return int
     */
    protected function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param $apiKey
     */
    protected function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return int
     */
    public function getFormId()
    {
        return $this->formId;
    }

    /**
     * @param int $formId
     */
    protected function setFormId($formId)
    {
        $this->formId = $formId;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     * @return $this
     */
    public function setPage($page)
    {
        if ($page < 0) {
            $this->page = 0;
        }
        else {
            $this->page = $page;
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return mixed
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param $questions
     * @return $this
     */
    protected function setQuestions($questions)
    {
        $this->questions = $questions;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * @param $responses
     * @return $this
     */
    protected function setResponses($responses)
    {
        $this->responses = $responses;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * @param mixed $stats
     * @return Form
     */
    public function setStats($stats)
    {
        $this->stats = $stats;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * @param $completed
     * @return $this
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSince()
    {
        return $this->since;
    }

    /**
     * @param $since
     * @return $this
     */
    public function setSince($since)
    {
        $this->since = $since;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUntil()
    {
        return $this->until;
    }

    /**
     * @param $until
     * @return $this
     */
    private function setUntil($until)
    {
        $this->until = $until;

        return $this;
    }


}
