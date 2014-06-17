<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\Mink\Mink,
    Behat\Mink\Session,
    Behat\Mink\Driver\Selenium2Driver,
    Behat\MinkExtension\Context\MinkContext;

use Selenium\Client as SeleniumClient;
//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

class GuiContext extends MinkContext
{
    /**
     * @When /^wait (\d+) seconds?$/
     */
    public function waitSeconds($seconds)
    {
        $this->getSession()->wait(1000*$seconds);
    }
}
/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    private $kernel;
    private $parameters; 
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->useContext('gui', new GuiContext($parameters));
    }
    /**
     * @When /^I search for "([^"]*)"$/
     */
    public function iSearchFor($arg1)
    {
        $this->fillField('Search Drupal.org', $arg1);
        $this->pressButton('Search');
    }
}
