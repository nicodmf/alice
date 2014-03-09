<?php

/*
 * This file is part of the Alice package.
 *
 * (c) Nelmio <hello@nelm.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nelmio\Alice\Instances\FixtureBuilders;

use Nelmio\Alice\Instances\FixtureBuilders\BuilderInterface;
use Nelmio\Alice\Instances\Fixture;
use Nelmio\Alice\Instances\Instantiators;
use Nelmio\Alice\Instances\Processor;
use Nelmio\Alice\Util\TypeHintChecker;

class BaseBuilder implements BuilderInterface {

	/**
	 * @var Processor
	 */
	protected $processor;

	/**
	 * @var TypeHintChecker
	 */
	protected $typeHintChecker;

	function __construct(Processor $processor, TypeHintChecker $typeHintChecker) {
		$this->processor       = $processor;
		$this->typeHintChecker = $typeHintChecker;
	}

	/**
	 * {@inheritDoc}
	 */
	public function canBuild($name)
	{
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function build($class, $name, array $spec)
	{
		return $this->newFixture($class, $name, $spec);
	}

	protected function newFixture($class, $name, array $spec, $valueForCurrent = null)
	{
		$instantiators = array(
			new Instantiators\Unserialize(),
			new Instantiators\ReflectionWithoutConstructor(),
			new Instantiators\ReflectionWithConstructor($this->processor, $this->typeHintChecker),
			new Instantiators\EmptyConstructor(),
		);
		
		return new Fixture($class, $name, $spec, $valueForCurrent, $this->processor, $instantiators);
	}

}