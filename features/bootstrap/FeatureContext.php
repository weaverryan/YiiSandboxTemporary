<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\MinkContext;
use Behat\YiiExtension\Context\YiiAwareContextInterface;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends MinkContext implements YiiAwareContextInterface
{
    private $yii;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param   array   $parameters     context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
    }

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        User::model()->deleteAll();
    }

    /**
     * @Given /^I switch to the namespaces frame$/
     */
    public function iSwitchToTheNamespacesFrame()
    {
        // switch to the main frame first
        $this->getSession()->switchToIframe();
        $this->getSession()->switchToIFrame('packagelist');
    }

    /**
     * @Given /^I switch to the navigation frame$/
     */
    public function switchToTheNavigationFrame()
    {
        // switch to the main frame first
        $this->getSession()->switchToIframe();
        $this->getSession()->switchToIFrame('index');
    }

    /**
     * @Given /^I switch to the main frame$/
     */
    public function switchToTheMainFrame()
    {
        // switch to the main frame first
        $this->getSession()->switchToIframe();
        $this->getSession()->switchToIFrame('main');
    }

    public function setYiiWebApplication(\CWebApplication $yii)
    {
        $this->yii = $yii;
    }

    /**
     * This allows us to override the assertion engine with our own
     */
    public function assertSession($name = null)
    {
        return new CustomWebAssert($this->getSession($name));
    }
}
