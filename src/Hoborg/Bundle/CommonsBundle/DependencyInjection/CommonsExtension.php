<?php
namespace Hoborg\Bundle\CommonsBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension,
	Symfony\Component\DependencyInjection\ContainerBuilder,
	Symfony\Component\DependencyInjection\Loader\YamlFileLoader,
	Symfony\Component\Config\FileLocator;

class CommonsExtension extends Extension {

	public function load(array $configs, ContainerBuilder $container) {

		$configuration = new Configuration();
		$config = $this->processConfiguration($configuration, $configs);

		if (!isset($config['connection_name'])) {
			throw new \InvalidArgumentException('The "connection_name" option must be set');
		}
		$container->setParameter('hoborg.cmns.identity.connection_name', $config['connection_name']);

		// only load default services and parameters once
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
		$loader->load('services.yml');
	}

}