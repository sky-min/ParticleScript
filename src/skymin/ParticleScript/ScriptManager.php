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

namespace skymin\ParticleScript;

use skymin\ParticleScript\script\ParticleScriptFile;
use skymin\ParticleScript\exception\ParticleScriptException;

use function in_array;
use function yaml_parse;
use function preg_replace;
use function file_exists;
use function file_get_contents;

final class ScriptManager{

	/** @var ParticleScriptFile[] */
	private static array $scriptFiles = [];

	public static function registerFile(string $fileName) : void{
		if(isset(self::$scriptFiles[$fileName])){
			throw new ParticleScriptException("The $fileName file is already registered particle script.");
		}
		if(!file_exists($fileName)){
			throw new ParticleScriptException("The $fileName file does not exist.");
		}
		$content = file_get_contents($fileName);
		if($content === false){
			throw new ParticleScriptException("Could not load the $fileName file.");
		}
		$content = self::parseYaml($content);
		if(!is_array($content)){
			throw new ParticleScriptException("Failed to load the $fileName file.");
		}
		if(!isset($content['particle_scripts'])){
			throw new ParticleScriptException("The $fileName file is not particle script.");
		}
		self::$scriptFiles[$fileName] = new ParticleScriptFile($fileName, $content['particle_scripts']);
	}

	public static function getScriptFile(string $fileName) : ParticleScriptFile{
		if(isset(self::$scriptFiles[$fileName])){
			return self::$scriptFiles[$fileName];
		}else{
			throw new ParticleScriptException("The $fileName file is unregistered particle script.");
		}
	}

	private static function parseYaml(string $content) : mixed{
		return yaml_parse(preg_replace('#^( *)(y|Y|yes|Yes|YES|n|N|no|No|NO|true|True|TRUE|false|False|FALSE|on|On|ON|off|Off|OFF)( *)\:#m', "$1\"$2\"$3:", $content));
	}

}