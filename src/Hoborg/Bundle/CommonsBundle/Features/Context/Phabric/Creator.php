<?php
namespace Hoborg\Bundle\CommonsBundle\Features\Context\Phabric;

use Behat\BehatBundle\Context\BehatContext,
Behat\BehatBundle\Context\MinkContext;
use Behat\Behat\Context\ClosuredContextInterface,
Behat\Behat\Context\TranslatedContextInterface,
Behat\Behat\Exception\PendingException,
Behat\Behat\Exception\Exception;
use Behat\Gherkin\Node\PyStringNode,
Behat\Gherkin\Node\TableNode;

class Creator extends BehatContext {

	const DB_IDENTITY = 'hoborg_cmns_identity';

	protected $phabric = null;

	protected $registry = null;

	public function __construct($parameters) {
		parent::__construct($parameters);

		$phabricConfig = $this->getContainer()->getParameter('Phabric');
		$db = $this->getContainer()->get('doctrine')->getConnection(self::DB_IDENTITY);
		$datasource = new \Phabric\Datasource\Doctrine($db, $phabricConfig['entities']);
		$this->phabric = new \Phabric\Phabric($datasource);

		$this->registry = new \Phabric\Registry();

		$this->phabric->addDataTransformation(
			'MD5', function($string) {
				return md5($string);
			}
		);

		$this->phabric->addDataTransformation(
			'UNIQUEID', function($id, $phabric) {
				static $id = 0;
				return $id++;
			}
		);

		$this->setupUserEntity($phabricConfig);
	}

	public function getPhabric() {
		return $this->phabric;
	}

	public function getRegistry() {
		return $this->registry;
	}

	/**
	 * @AfterScenario
	 */
	public static function tearDown($event) {
		$event->getContext()->getSubcontext('phabric_creator')->phabric->reset();
	}

	protected function setupUserEntity($phabricConfig) {
		$user = $this->phabric->createEntity('user', $phabricConfig['entities']['User']);

		$user;
	}

	/**
	 * @Given /^a identity user exist:$/
	 */
	public function aIdentityUserExist(TableNode $users) {
		$user = $this->phabric->getEntity('user');
		$user->updateFromTable($users);
	}

}
