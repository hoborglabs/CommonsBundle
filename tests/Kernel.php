<?php
namespace Hoborg\Commons;

use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends \Symfony\Component\HttpKernel\Kernel {

	protected $cacheDir = null;

	/**
	 * @see Symfony\Component\HttpKernel.KernelInterface::registerBundles()
	 */
	public function registerBundles() {
		$bundles = array(
			new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new \Symfony\Bundle\MonologBundle\MonologBundle(),
			new \Symfony\Bundle\TwigBundle\TwigBundle(),
			new \Symfony\Bundle\DoctrineBundle\DoctrineBundle(),
			new \Hoborg\Bundle\CommonsBundle\CommonsBundle(),
		);

		if ('test' === $this->getEnvironment()) {
			$bundles[] = new \Behat\BehatBundle\BehatBundle();
		}

		return $bundles;
	}

	/**
	 * @see Symfony\Component\HttpKernel.KernelInterface::registerContainerConfiguration()
	 */
	public function registerContainerConfiguration(LoaderInterface $loader) {
		$loader->load($this->getConfDir() . '/' . $this->getEnvironment() . '/conf.yml');
	}

	/**
	 * Sets host root dir.
	 * Use this method if you want to have multiple hosts and one instance
	 * of hoborg bundles. This Path will be used to get cache, logs and conf
	 * folder for you host.
	 *
	 * @param string $dir
	 */
	public function setHostDir($dir) {
		$this->hostDir = realpath($dir);
	}

	/**
	 * @see Symfony\Component\HttpKernel.Kernel::getCacheDir()
	 */
	public function getCacheDir() {
		if (empty($this->hostDir)) {
			return __DIR__ . '/cache/' . $this->getEnvironment();
		}

		return $this->hostDir . '/cache/' . $this->getEnvironment();
	}

	/**
	 * @see Symfony\Component\HttpKernel.Kernel::getLogDir()
	 */
	public function getLogDir() {
		if (empty($this->hostDir)) {
			return __DIR__ . '/logs';
		}

		return $this->hostDir . '/logs/' . $this->getEnvironment();
	}

	public function getConfDir() {
		if (empty($this->hostDir) || !realpath($this->hostDir . '/conf')) {
			return __DIR__ . '/conf';
		}

		return $this->hostDir . '/conf';
	}

	/**
	 * @see Symfony\Component\HttpKernel.Kernel::getKernelParameters()
	 */
	protected function getKernelParameters() {
		return array_merge(
			parent::getKernelParameters(),
			array(
				'kernel.conf_dir' => $this->getConfDir(),
				'kernel.cache' => $this->getCacheDir(),
			)
		);
	}
}