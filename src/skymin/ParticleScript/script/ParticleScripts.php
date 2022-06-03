<?php
declare(strict_types = 1);

namespace skymin\ParticleScript;

use skymin\ParticleScript\exception\ParticleScriptException;

use pocketmine\math\Vector3;

use function is_string;

final class ParticleScripts{

	/** @var ParticleScript[] */
	private array $scripts;

	public function __construct(array $data){
		foreach($data as $name => $script){
			if(!is_string($name)){
				throw new ParticleScriptException("The particle script name must be a string.");
			}
			$this->scripts[$name] = new ParticleScript($script);
		}
	}

	public function syncEncode(
		string $scriptName,
		Vector3 $pos,
		null|int|array $data = null,
		float $yaw = 0.0,
		float $pitch = 0.0,
		float $roll = 0.0
	) : array{
		
	}

	public function asyncEncode(
		\Closure $callBack,
		string $scriptName,
		Vector3 $pos,
		null|int|array $data = null,
		float $yaw = 0.0,
		float $pitch = 0.0,
		float $roll = 0.0
	) : void{
		
	}

}