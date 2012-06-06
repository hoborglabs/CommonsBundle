<?php
namespace Hoborg\Bundle\CommonsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {

	public function getConfigTreeBuilder() {

		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root('hoborg_cmns');

		$rootNode
			->children()
				->scalarNode('identity')->defaultValue(false)->end()
				->scalarNode('connection_name')->defaultValue('hoborg_cmns_identity')->end()
			->end();

		return $treeBuilder;
	}
}