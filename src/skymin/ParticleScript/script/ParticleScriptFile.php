<?php
/**
 *      _                    _       
 *  ___| | ___   _ _ __ ___ (_)_ __  
 * / __| |/ / | | | '_ ` _ \| | '_ \ 
 * \__ \   <| |_| | | | | | | | | | |
 * |___/_|\_\\__, |_| |_| |_|_|_| |_|
 *           |___/ 
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the MIT License. see <https://opensource.org/licenses/MIT>.
 * 
 * @author skymin
 * @link   https://github.com/sky-min
 * @license https://opensource.org/licenses/MIT MIT License
 * 
 *   /\___/\
 * 　(∩`・ω・)
 * ＿/_ミつ/￣￣￣/
 * 　　＼/＿＿＿/
 *
 */

declare(strict_types = 1);

namespace skymin\ParticleScript\script;

use skymin\ParticleScript\exception\{
	ScriptExceptionMessage,
	ParticleScriptException
};

use function is_array;
use function is_string;

final class ParticleScriptFile{

	/**
	 * @var ParticleScript[]
	 * @phpstan-var array<string, ParticleScript>
	 */
	private array $scripts;
	/**
	 * @var CustomParticle[]
	 * @phpstan-var array<string, CustomParticle>
	 */
	private array $particles;

	public function __construct(
		private string $fileName,
		array $data
	){
		if(!isset($data['particles']) || !isset($data['scripts'])){
			throw new ParticleScriptException($fileName . ": Requires 'particles' key and 'scripts' key");
		}
		if(!is_array($data['particles']) || !is_array($data['scripts'])){
			throw new ParticleScriptException($fileName. ": 'particles' key and 'scripts' key must is array");
		}
		foreach($data['scripts'] as $name => $script){
			if(!is_string($name)){
				throw new ParticleScriptException($this->fileName . ':' . ScriptExceptionMessage::SCRIPT_NAME);
			}
			$this->scripts[$name] = new ParticleScript($this, $name, $script);
		}
		foreach ($data['particles'] as $name => $particle){
			if(!is_string($name)){
				throw new ParticleScriptException($this->fileName . ':' . ScriptExceptionMessage::PARTICLE_NAME);
			}
			$this->particles[$name] = new CustomParticle($this->fileName, $name, $particle);
		}
	}

	public function getFileName() : string{
		return $this->fileName;
	}

	public function getParticle(string $name) : ?CustomParticle{
		return $this->particles[$name] ?? null;
	}

	public function getScript(string $name) : ?ParticleScript{
		return $this->scripts[$name] ?? null;
	}

}