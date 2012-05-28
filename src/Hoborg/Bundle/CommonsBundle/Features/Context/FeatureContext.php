<?php

namespace Hoborg\Bundle\CommonsBundle\Features\Context;

use Hoborg\Bundle\CommonsBundle\Features\Context\Api\ResponseContext;
use Behat\BehatBundle\Context\BehatContext,
Behat\BehatBundle\Context\MinkContext;
use Behat\Behat\Context\ClosuredContextInterface,
Behat\Behat\Context\TranslatedContextInterface,
Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
Behat\Gherkin\Node\TableNode;
use Behat\Behat\Event\SuiteEvent;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Feature context.
 */
class FeatureContext extends BehatContext {

	public function __construct($parameters) {
		parent::__construct($parameters);

		$this->useContext('api_user', new Api\Direct\UserContext($parameters));
		$this->useContext('api-rest_user', new Api\Rest\UserContext($parameters));
		$this->useContext('api_response', new Api\ResponseContext($parameters));
		$this->useContext('phabric_creator', new Phabric\Creator($parameters));

		//$db = $event->getContextParameters()->getContainer()->get('doctrine')->getConnection('hoborg_cmns_identity');
		system('cat /Users/woledzki/Documents/hoborg-workspace/HoborgCommons/src/Hoborg/Bundle/CommonsBundle/Resources/database/fixtures/hoborg-commons-clean.sql | /usr/local/mysql-5.5.9-osx10.6-x86/bin/mysql -uhoborg_dev -phoborg hoborg_test');
	}

	/** @BeforeSuite */
	public static function setup(SuiteEvent $event) {
		// cleanup DB
		//$sql = 'TRUNCATE TABLE user; TRUNCATE TABLE user_token';
		//$db->fetchAll($sql);
	}

	/** @AfterSuite */
	public static function teardown(SuiteEvent $event) {
	}

}
