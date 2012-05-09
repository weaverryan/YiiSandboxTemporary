<?php

/*
 * Temporarily forcing the use of the PHAR for ease
 */
/*
if(file_exists(stream_resolve_include_path('mink/autoload.php'))){
    require_once 'mink/autoload.php';
} elseif(file_exists(__DIR__.'/../../behat/mink.phar')){
    require_once __DIR__.'/../../mink.phar';
} else throw new Exception('There is no mink distro available on your system');
*/
require_once __DIR__.'/../../mink.phar';

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\Mink\Behat\Context\MinkContext;

use Behat\Behat\Context\Step\When,
	Behat\Behat\Context\Step\Given,
	Behat\Behat\Context\Step\Then;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends \Behat\Mink\Behat\Context\MinkContext
{

    static protected  $yiiApp;
    public $currentUser;

    /**
     * @Then /^I should be authenticated$/
     */
    public function iShouldBeAuthenticated()
    {
        return new \Behat\Behat\Context\Step\Then("I should see \"Logout\"");
    }
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param   array   $parameters     context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
    }

    /**
     * @BeforeSuite
     */
    public static function instantiateYii()
    {
        // Instantiate yii to make autoload work
        self::getYii();
    }
    /**
     * @BeforeScenario
     */
    public function clearUsers()
    {


        //Delete all users from the table to make sure the db is clean
        User::model()->deleteAll();
    }

    /**
     * @Given /^There is an admin account$/
     */
    public function thereIsAnAdminAccount()
    {

        // create our demo admin user
        $user = new User();
        $user['username'] = 'demo';
        $user['salt'] = md5(rand(0, 100000));
        $user['password'] = $user->hashPassword('demo', $user['salt']);
        $user['email'] = 'foo@foo.com';
        if (!$user->save()) {
            throw new \Exception('User was not saved!');
        }

        return $user;
    }

    /**
     * Lazy instantiation getter for yii
     * @return \CWebApplication
     */
    public static function getYii()
    {
        if(!self::$yiiApp){
            self::$yiiApp = self::createYiiApplication();
        }

        return self::$yiiApp;
    }

    /**
     * @return \CWebApplication
     */
    static private function createYiiApplication()
    {
        // change the following paths if necessary
        $yii = __DIR__.'/../../framework/yii.php';
        $config = __DIR__.'/../../protected/config/test.php';

        // remove the following line when in production mode
        defined('YII_DEBUG') or define('YII_DEBUG', true);

        require_once($yii);

        // tells Yii to not just include files, but to let other autoloaders or the include path try
        \YiiBase::$enableIncludePath = false;

        // create the application and return it
        return Yii::createWebApplication($config);
    }

    /**
     * @When /^I click logout link$/
     */
    public function iClickLogoutLink()
    {
        return new \Behat\Behat\Context\Step\When('I follow "Logout"');
    }

    /**
     * @Then /^I should not be authenticated$/
     */
    public function iShouldNotBeAuthenticated()
    {
        return array(
            new \Behat\Behat\Context\Step\Then('I should not see "Logout"'),
            new \Behat\Behat\Context\Step\Then('I should see "Login"'),
        );
    }

    /**
     * @Given /^I am authenticated$/
     */
    public function iAmAuthenticated()
    {
        if (!$this->currentUser) {
            $this->currentUser = $this->thereIsAnAdminAccount();
        }

        return array(
            new \Behat\Behat\Context\Step\Given('I am on "/site/login"'),
            new \Behat\Behat\Context\Step\When('I fill in "Username" with "demo"'),
            new \Behat\Behat\Context\Step\When('I fill in "Password" with "demo"'),
            new \Behat\Behat\Context\Step\When('I press "Login"'),
        );
    }

	/**
	 * @Then /^I should be on the "([^"]*)" blog post page$/
	 */
	public function iShouldBeOnTheBlogPostPage($title) {
		$page = $this->getSession()->getPage();
		$element = $page->find('css', $css='#content .title');
		// if (!$element) throw new Behat\Mink\Exception\ElementNotFoundException($this->getSession(), 'element', 'css', $css);
		assertNotNull($element, "Title element not found");
		// if (strpos($element->getText(), $title) === false) throw new Exception("Title $title not found");
		assertRegExp('/'.preg_quote($title).'/', $element->getText(), "Title $title not found");

		// Meta-step:
		// return array(
		// 	new Then('I should see "'.$title.'" in the "title" element'),
		// );
	}

	/**
	 * @Given /^I fill in "([^"]*)" with$/
	 */
	public function iFillInWith($field, PyStringNode $string) {
		return new When('I fill in "'.$field.'" with "'.(string)$string.'"');
	}

	/**
	 * @Given /^there is a post titled "([^"]*)"$/
	 */
	public function thereIsAPostTitled($title) {
		$post = new Post;
		$post['title'] = $title;
		$post['content'] = 'Fake content';
		$post['status'] = Post::STATUS_DRAFT;
		$post['author_id'] = $this->currentUser['id'];
		if (!$post->save()) throw new \Exception('Problem saving the post!');
	}

	/**
	 * @When /^I follow the "([^"]*)" link for post "([^"]*)"$/
	 */
	public function iFollowTheLinkForPost($link, $title) {
		$page = $this->getSession()->getPage();
		$row = $page->find('css', 'tr:contains("'.$title.'")');
		assertNotNull($row, "Row containing $title not found");
		$element = $row->findLink($link);
		assertNotNull($element, "$link link not found");
		$element->click();
	}

}
