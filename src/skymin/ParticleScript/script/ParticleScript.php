<?php
declare(strict_types = 1);

namespace skymin\ParticleScript\script;

use skymin\ParticleScript\exception\ParticleScriptException;

final class ParticleScript{

	private array $data

	public function __construct(array $data){
		$this->load($data);
	}

	private function load(array $data) : void{
		
	}

}