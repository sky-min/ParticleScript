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

use pocketmine\math\Vector3;

use function is_string;

final class ParticleScriptFile{

	/** @var ParticleScript[] */
	private array $scripts;

	public function __construct(
		private string $fileName,
		array $data
	){
		foreach($data as $name => $script){
			if(!is_string($name)){
				throw new ParticleScriptException($this->fileName . ':' . ScriptExceptionMessage::SCRIPT_NAME);
			}
			$this->scripts[$name] = new ParticleScript($this, $name, $script);
		}
	}

	public function getFileName() : string{
		return $this->fileName;
	}

	public function getScript(string $name) : ?ParticleScript{
		return $this->scripts[$name] ?? null;
	}

}