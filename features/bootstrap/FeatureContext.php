<?php

if(file_exists(stream_resolve_include_path('mink/autoload.php'))){
    require_once 'mink/autoload.php';
} elseif(file_exists(__DIR__.'/../../behat/mink.phar')){
    require_once __DIR__.'/../../behat/mink.phar';
} else throw new Exception('There is no mink distro available on your system');

//require_once('vendor/common-contexts/Behat/CommonContexts/WebApiContext.php');
use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\Mink\Behat\Context\MinkContext;



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

//      $this->useContext('api', new \Behat\CommonContexts\WebApiContext($parameters['base_api_url']));
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
     * @Given /^There is and admin account$/
     */
    public function thereIsAndAdminAccount()
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

}