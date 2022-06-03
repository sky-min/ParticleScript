<?php
declare(strict_types = 1);

namespace skymin\ParticleScript\script;

use skymin\ParticleScript\exception\ParticleScriptException;

use function is_int;
use function is_float;
use function is_string;
use function is_array;

final class ParticleScript{

	private int|float $unit;
	private array $shape;
	

	public function __construct(array $script){
		if(!isset($script['particle'])){
			throw new ParticleScriptException("The 'particle' key required by the particle script is missing.");
		}
		if(!isset($script['shape'])){
			throw new ParticleScriptException("The 'shape' key required by the particle script is missing.");
		}
		if(!isset($script['unit'])){
			throw new ParticleScriptException("The 'unit' key required by the particle script is missing.");
		}
		$particle = $script['particle'];
		if(!is_string($particle) && !is_int($particle)){
			throw new ParticleScriptException("The 'particle' key is must be a string or int.");
		}
		if(!is_array($script['shape'])){
			throw new ParticleScriptException("The 'shape' key is must be a array");
		}
		$unit = $scirpt['unit'];
		if(!is_int($unit) && !is_float($unit)){
			throw new ParticleScriptException("The 'unit' key is must be a string or int.");
		}
	}

}