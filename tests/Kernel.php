<?php
use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends \Hoborg\Application\Kernel {

	/**
	 * @see Symfony\Component\HttpKernel.KernelInterface::registerBundles()
	 */
	public function registerBundles() {
		$bundles = parent::registerBundles();
		$bundles[] = new \Symfony\Bundle\FrameworkBundle\FrameworkBundle();
		$bundles[] = new \Symfony\Bundle\MonologBundle\MonologBundle();
		$bundles[] = new \Symfony\Bundle\DoctrineBundle\DoctrineBundle();

		$bundles[] = new \Hoborg\PhabricBundle\PhabricBundle();
		$bundles[] = new \Hoborg\Bundle\CommonsBundle\CommonsBundle();

		$bundles[] = new \Behat\BehatBundle\BehatBundle();

		return $bundles;
	}

	/**
	* @see Symfony\Component\HttpKernel.KernelInterface::registerContainerConfiguration()
	*/
	public function registerContainerConfiguration(LoaderInterface $loader) {
		$loader->load($this->getConfDir() . '/conf.yml');
	}

}