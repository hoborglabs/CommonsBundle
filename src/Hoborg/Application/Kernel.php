<?php
namespace Hoborg\Application;

use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends \Symfony\Component\HttpKernel\Kernel {

	protected $cacheDir = null;

	protected $hostDir = null;

	/**
	 * @see Symfony\Component\HttpKernel.KernelInterface::registerBundles()
	 */
	public function registerBundles() {

		if (empty($this->hostDir)) {
			$this->hostDir = realpath(__DIR__ . '/../');
		}

		return array();
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

	public function getHostDir() {
		return $this->hostDir;
	}

	/**
	 * @see Symfony\Component\HttpKernel.Kernel::getCacheDir()
	 */
	public function getCacheDir() {
		return $this->hostDir . '/cache/' . $this->getEnvironment();
	}

	/**
	 * @see Symfony\Component\HttpKernel.Kernel::getLogDir()
	 */
	public function getLogDir() {
		return $this->hostDir . '/logs/' . $this->getEnvironment();
	}

	public function getConfDir() {
		if (empty($this->hostDir) || !realpath($this->hostDir . '/conf')) {
			throw new Error("configuration folder `{$this->hostDir}/conf` not readable");
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