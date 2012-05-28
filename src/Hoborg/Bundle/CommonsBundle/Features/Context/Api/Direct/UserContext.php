<?php
namespace Hoborg\Bundle\CommonsBundle\Features\Context\Api\Direct;

use Behat\BehatBundle\Context\BehatContext,
Behat\BehatBundle\Context\MinkContext;
use Behat\Behat\Context\ClosuredContextInterface,
Behat\Behat\Context\TranslatedContextInterface,
Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
Behat\Gherkin\Node\TableNode;

class UserContext extends BehatContext {

	protected $lastUser = null;

	/**
	 * @Given /^I am not logged in$/
	 */
	public function iAmNotLoggedIn() {
		// nothing to do
	}

	/**
	 * @When /^I use Internal API to login with "([^"]+)" and "([^"]+)"$/
	 */
	public function iUseInternalApiToLoginWithAnd($login, $password) {
		$container = $this->getContainer();

		// save this response
		$user = $container->get('hoborg.identity')->login($login, $password);
		$userArray = array();
		if (!empty($user)) {
			$userArray = $user->toArray();
		}

		// save resposne array.
		$this->getMainContext()->getSubcontext('api_response')->response = $userArray;

		// save loggd user
		$registry = $this->getMainContext()->getSubcontext('phabric_creator')->getRegistry();
		$registry->add('user_by_login', $login, $user);
	}


	/**
	 * @When /^I use Internal API to logout(?: user)? "([^"]+)"$/
	 */
	public function iUseInternalApiToLogout($userLogin) {
		$container = $this->getContainer();

		if (empty($userLogin)) {
			throw new \Exception('Empty login strings');
		} else {
			$registry = $this->getMainContext()->getSubcontext('phabric_creator')->getRegistry();
			$user = $registry->get('user_by_login', $userLogin);
			if (empty($user)) {
				throw new \Exception("User [$userLogin] not found in registry.");
			}

			$response = $container->get('hoborg.identity')->logout($user->getToken());
		}
	}


	/**
	 * @When /^I use Internal API to request user "([^"]*)"$/
	 */
	public function iUseInternalApiToRequestUser($userLogin) {
		$container = $this->getContainer();
		$user = $container->get('hoborg.identity')->getUserByLogin($userLogin);

		// save this response
		$userArray = array();
		if (!empty($user)) {
			$userArray = $user->toArray();
		}
		$this->getMainContext()->getSubcontext('api_response')->response = $userArray;
	}
}
