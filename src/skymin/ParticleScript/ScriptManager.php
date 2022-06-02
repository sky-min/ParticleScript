<?php
declare(strict_types = 1);

namespace skymin\ParticleScript;

use skymin\ParticleScript\script\ParticleScript;
use skymin\ParticleScript\exception\ParticleScriptException;

use function in_array;
use function yaml_parse;
use function preg_replace;
use function file_exists;
use function file_get_contents;

final class ScriptManager{

	/** @var ParticleScript[] */
	private static array $scripts = [];

	public static function register(string $fileName) : void{
		if(isset(self::$scripts[$fileName])){
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
		if(!isset($content['declare']) || $content['declare'] !== 'particle_script'){
			throw new ParticleScriptException("The $fileName file is not particle script.");
		}
		self::$scripts[$fileName] = new ParticleScriptException($content);
	}

	public static function getScript(string $fileName) : ParticleScript{
		if(isset(self::$scripts[$fileName])){
			return self::$scripts[$fileName];
		}else{
			throw new ParticleScriptException("The $fileName file is unregistered particle script.")
		}
	}

	private static function parseYaml(string $content) : mixed{
		return yaml_parse(preg_replace('#^( *)(y|Y|yes|Yes|YES|n|N|no|No|NO|true|True|TRUE|false|False|FALSE|on|On|ON|off|Off|OFF)( *)\:#m', "$1\"$2\"$3:", $content));
	}

}