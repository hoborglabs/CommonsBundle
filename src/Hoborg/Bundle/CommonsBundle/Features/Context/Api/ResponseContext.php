<?php
namespace Hoborg\Bundle\CommonsBundle\Features\Context\Api;

use Behat\BehatBundle\Context\BehatContext,
    Behat\BehatBundle\Context\MinkContext;
use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Exception\PendingException,
    Behat\Behat\Exception\Exception;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

class ResponseContext extends BehatContext {

	public $response = null;

	/**
	 * @Then /^I should get the following (.+)$/
	 */
	public function iShouldGetTheFollowing($dataType, TableNode $table) {
		switch ($dataType) {
			case 'user':
				return $this->compareUser($table);

			default:
				throw new Exception('unknown data type ' . $dataType);
		}
	}

	/**
	 * @Given /^I should (not )?get "([^"]*)" field$/
	 */
	public function iShouldGetField($not, $headerName) {
		$not = !empty($not);
		$fieldName = $this->getUserFieldNameFromHeader($headerName);

		if ($not && isset($this->response[$fieldName])) {
			throw new Exception("field [$headerName] ($fieldName) is present in response");
		}
	}

	/**
	 * @Then /^I should get "([^"]*)" response$/
	 */
	public function iShouldGetResponse($responseType) {
		switch ($responseType) {
			case 'empty':
				if (!empty($this->response)) {
					throw new Exception('Response not empty (null)');
				}
				break;

			case 'failure':
			case 'success':
				//throw new PendingException($responseType .' response comming soon...');
				break;
		}
	}

	protected function compareUser($table) {
		$user = $table->getHash();
		foreach ($user[0] as $key => $value) {
			$fieldName = $this->getUserFieldNameFromHeader($key);
			if (!isset($this->response[$fieldName])) {
				throw new Exception('User field [' . $fieldName . '] not found in response');
			}
			if ($this->response[$fieldName] != $value) {
				throw new Exception('User field ' . $fieldName . ' not match');
			}
		}

		return true;
	}

	protected function getUserFieldNameFromHeader($header) {
		$mapping = array(
			'login' => 'login',
			'first name' => 'firstName',
			'last name' => 'lastName',
			'logout' => 'logout',
			'token' => 'token',
		);
		return $mapping[strtolower($header)];
	}
}
