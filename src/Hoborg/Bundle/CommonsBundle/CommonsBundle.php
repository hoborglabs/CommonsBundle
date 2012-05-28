<?php
namespace Hoborg\Bundle\CommonsBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CommonsBundle extends Bundle {

	public function build(ContainerBuilder $container) {
		parent::build($container);
	}
}
