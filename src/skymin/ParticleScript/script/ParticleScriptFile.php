<?php
declare(strict_types = 1);

namespace skymin\ParticleScript;

use skymin\ParticleScript\exception\{
	ScriptExceptionMessage
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
				throw new ParticleScriptException(ScriptExceptionMessage::SCRIPT_NAME . "\nError file is $this->fileName");
			}
			$this->scripts[$name] = new ParticleScript($this, $name, $script);
		}
	}

	public function getFileName() : string{
		return $this->fileName;
	}

	public function getScript(string $name) : ?ParticleScript{
		return $this->scripts[$name] ?? null
	}

}