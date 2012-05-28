<?php
namespace Hoborg\Bundle\CommonsBundle\Mapper;

abstract class Mapper {
	protected $adapter = null;

	public function __construct($adapter) {
		$this->adapter = $adapter;
	}
}