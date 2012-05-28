<?php
namespace Hoborg\Bundle\CommonsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

// these import the "@Route" annotations
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class IdentityController extends Controller {

	protected $response = null;
	protected $request = null;
	protected $identityApi = null;

	/**
	 * Initialize objects shared accross all actions.
	 *
	 * @return void
	 */
	protected function init() {
		$this->identityApi = $this->get('hoborg.identity');
		$this->request = $this->getRequest();
	}

	/**
	 * Returns JSON response
	 *
	 * @param array $data
	 */
	protected function jsonSuccessResponse(array $data) {
		$this->response = new Response();

		$this->response->setContent(json_encode($data));
		$this->response->setStatusCode(200);
		$this->response->headers->set('Content-Type', 'application/json');

		return $this->response;
	}

	/**
	 * @Route("/api/cmns/identity/user/login", name="api_identity_login")
	 */
	public function loginAction() {
		$this->init();

		$login = $this->request->request->get('login');
		$password = $this->request->request->get('password');
		$user = $this->identityApi->login($login, $password);

		// log in user properly - session
		// ...

		// enrich user array
		$userArray = $user->toArray();
		$userArray['logout'] = $this->request->getUriForPath('/api/cmns/identity/user/logout');

		return $this->jsonSuccessResponse($userArray);
	}

	public function logoutAction() {
		// get user from session
		$user = $this->identityApi->getUser('wojtek.oledzki');

		// and logout
		$response = array(
			'success' => $this->identityApi->logout($user),
		);

		return $this->jsonSuccessResponse($response);
	}

	public function getUserAction($login) {
		$this->init();

		$user = $this->identityApi->getUserByLogin($login);
		$userArray = array();
		if (empty($user)) {
		} else {
			$userArray = $user->toArray();
		}

		return $this->jsonSuccessResponse($userArray);
	}
}