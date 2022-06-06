<?php
declare(strict_types = 1);

namespace skymin\ParticleScript\script;

use skymin\ParticleScript\exception\{
	ScriptExceptionMessage,
	ParticleScriptException
};

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\{
	LevelEventPacket,
	SpawnParticleEffectPacket
};

use function is_int;
use function is_float;
use function is_string;
use function is_array;
use function array_merge;
use function sin;
use function cos;
use function deg2rad;

final class ParticleScript{

	private array $data;

	public function __construct(
		private ParricleScriptFile $file,
		private string $name,
		array $data
	){
		if(!isset($data['particle'])){
			throw new ParticleScriptException(ScriptExceptionMessage::REQUIRE_PARTICLE);
		}
		if(!isset($data['shape'])){
			throw new ParticleScriptException(ScriptExceptionMessage::REQUIRE_SHAPE);
		}
		if(!isset($data['unit'])){
			throw new ParticleScriptException(ScriptExceptionMessage::REQUIRE_UNIT);
		}
		$particle = $data['particle'];
		if(!is_string($particle) && !is_int($particle)){
			throw new ParticleScriptException(ScriptExceptionMessage::TYPE_PARTICLE);
		}
		if(!is_array($data['shape'])){
			throw new ParticleScriptException(ScriptExceptionMessage::TYPE_SHAPE);
		}
		$unit = $scirpt['unit'];
		if(!is_int($unit) && !is_float($unit)){
			throw new ParticleScriptException(ScriptExceptionMessage::TYPE_UNIT);
		}
		if(isset($data['extends']) && !is_array($data['extends'])){
			throw new ParticleScriptException(ScriptExceptionMessage::TYPE_EXTENDS);
		}
		if(isset($data['offset']) && !is_array($data['offset'])){
			throw new ParticleScriptException(ScriptExceptionMessage::TYPE_OFFSET);
			foreach($data['offset'] as $value){
				if(!is_int($value) && !is_float($value)){
					throw new ParticleScriptException(ScriptExceptionMessage::TYPE_OFFSET);
				}
			}
		}
		$this->data = $data;
	}

	/** @return LevelEventPacket[]|SpawnParticleEffectPacket[] */
	public function encode(
		Vector3 $pos,
		float $yaw = 0.0,
		float $pitch = 0.0,
		float $roll = 0.0
	) : array{
		$data = $this->data;
		$result = [];
		if(isset($data['extends'])){
			$file = $this->file;
			foreach($data['extends'] as $name){
				if(!is_string($name)){
					throw new ParticleScriptException(ScriptExceptionMessage::TYPE_EXTENDS);
				}
				$script = $file->getScript($name);
				if($script instanceof ParticleScript){
					$result = array_merge($result, $script->encode($pos, $yaw, $pitch, $roll));
				}
			}
		}
		if(isset($data['offset'])){
			$offset = $data['offset'];
			$pos->add($offset[0], $offset[1] ?? 0, $offset[2] ?? 0);
		}
		//TODO
	}

}