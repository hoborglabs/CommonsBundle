<?php
namespace Hoborg\Bundle\CommonsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

// these import the "@Route" annotations
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class IndexController extends Controller {

    /**
     * @Route("/", name="_demo")
     */
	public function indexAction() {
		return new Response('test');
	}
}