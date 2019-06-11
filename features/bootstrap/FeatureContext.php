<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    protected $response;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given I am an unauthenticated user
     */
    public function iAmAnUnauthenticatedUser()
    {
        return true;
    }

    /**
     * @When I request a list of announcements from :arg1
     */
    public function iRequestAListOfAnnouncementsFrom($arg1)
    {
        $client = new GuzzleHttp\Client(['base_uri' => $arg1]);

        $this->response = $client->get('/announcements/');

        $responseCode = $this->response->getStatusCode();

        if ($responseCode != 200) {
            throw new Exception("Expected a 200, but received " . $responseCode);
        }
    }

    /**
     * @Then The results should include an announcement with ID :arg1
     */
    public function theResultsShouldIncludeAnAnnouncementWithId($arg1)
    {
        $announcements = json_decode($this->response->getBody(), true);

        foreach ($announcements['_embedded']['announcement'] as $announcement) {
            if ($announcement['id'] == $arg1) {
                return true;
            }
        }

        throw new Exception("Expected to find announcement " . $arg1 . " but didn't.");
    }
}
