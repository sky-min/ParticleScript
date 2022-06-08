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

use function is_int;
use function is_float;
use function is_string;
use function is_array;
use function array_merge;
use function count;
use function sin;
use function cos;
use function deg2rad;

final class ParticleScript{

	private array $data;

	public function __construct(
		private ParticleScriptFile $file,
		private string $name,
		array $data
	){
		if(!isset($data['shape'])){
			$this->error(ScriptExceptionMessage::REQUIRE_SHAPE);
		}
		if(!isset($data['unit'])){
			$this->error(ScriptExceptionMessage::REQUIRE_UNIT);
		}
		if(!is_array($data['shape'])){
			$this->error(ScriptExceptionMessage::TYPE_SHAPE);
		}
		$unit = $data['unit'];
		if(!is_int($unit) && !is_float($unit)){
			$this->error(ScriptExceptionMessage::TYPE_UNIT);
		}
		if(isset($data['extends']) && !is_array($data['extends'])){
			$this->error(ScriptExceptionMessage::TYPE_EXTENDS);
		}
		if(isset($data['offset'])){
			if(!is_array($data['offset'])){
				$this->error(ScriptExceptionMessage::TYPE_OFFSET);
			}
			foreach($data['offset'] as $value){
				if(!is_int($value) && !is_float($value)){
					$this->error(ScriptExceptionMessage::TYPE_OFFSET);
				}
			}
		}
		$this->data = $data;
	}

	private function error(string $message) : void{
		throw new ParticleScriptException("{$this->file->getFileName()}[scripts][{$this->name}]: " . $message);
	}

	public function getName() : string{
		return $this->name;
	}

	/** @return LevelEventPacket[]|SpawnParticleEffectPacket[] */
	public function encode(
		Vector3 $pos,
		float $yaw = 0.0,
		float $pitch = 0.0
	) : array{
		$data = $this->data;
		$result = [];
		$file = $this->file;
		if(isset($data['extends'])){
			foreach($data['extends'] as $name){
				if(!is_string($name)){
					$this->error(ScriptExceptionMessage::TYPE_EXTENDS);
				}
				$script = $file->getScript($name);
				if($script !== null){
					$result = array_merge($result, $script->encode($pos, $yaw, $pitch));
				}
			}
		}
		if(isset($data['offset'])){
			$offset = $data['offset'];
			$pos->add($offset[0], $offset[1] ?? 0, $offset[2] ?? 0);
		}
		$unit = $data['unit'];
		$yaw = deg2rad($yaw);
		$pitch = deg2rad($pitch);
		$ysin = sin($yaw);
		$ycos = cos($yaw);
		$psin = sin($pitch);
		$pcos = cos($pitch);
		$x_center = count($data['shape']) / 2 - 0.5;
		foreach($data['shape'] as $x => $y_shape){
			if(!is_array($y_shape)){
				$this->error(ScriptExceptionMessage::TYPE_SHAPE);
			}
			$y_center = (count($y_shape) / 2) - 0.5;
			foreach($y_shape as $y => $particle){
				if(!is_string($particle)) continue;
				$particle = $file->getParticle($particle);
				if($particle === null) continue;
				$dx = ($x - $x_center) * $unit;
				$dy = ($y - $y_center) * $unit;
				$dz = $dy * $psin;
				$particle = $particle->encode($pos->add(
					$dx * $ycos + $dz * $ysin,
					$dy * $pcos,
					$dx * -$ysin + $dz * $ycos
				));
				$result[] = $particle;
			}
		}
		return $result;
	}

}