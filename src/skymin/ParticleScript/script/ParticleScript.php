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
use pocketmine\network\mcpe\protocol\{
	LevelEventPacket,
	SpawnParticleEffectPacket
};
use pocketmine\network\mcpe\protocol\types\DimensionIds;

use function is_int;
use function is_float;
use function is_string;
use function is_array;
use function array_merge;
use function count;
use function sin;
use function cos;
use function deg2rad;
use function strtolower;

final class ParticleScript{

	private const PARTICLE_TYPE_INT = 0;
	private const PARTICLE_TYPE_STRING = 1;

	private const SHAPE_TYPE_XZ = 'xz';
	private const SHAPE_TYPE_XY = 'xy';
	private const SHAPE_TYPE_ZY = 'zy';

	private array $data;
	private int $particle_type;
	private string $shape_type = self::SHAPE_TYPE_XZ;

	public function __construct(
		private ParticleScriptFile $file,
		private string $name,
		array $data
	){
		if(!isset($data['particle'])){
			$this->error(ScriptExceptionMessage::REQUIRE_PARTICLE);
		}
		if(!isset($data['shape'])){
			$this->error(ScriptExceptionMessage::REQUIRE_SHAPE);
		}
		if(!isset($data['unit'])){
			$this->error(ScriptExceptionMessage::REQUIRE_UNIT);
		}
		$particle = $data['particle'];
		if(!is_string($particle) && !is_int($particle)){
			$this->error(ScriptExceptionMessage::TYPE_PARTICLE);
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
		if(is_string($particle)){
			$this->particle_type = self::PARTICLE_TYPE_STRING;
		}
		if(is_int($particle)){
			$this->particle_type = self::PARTICLE_TYPE_INT;
		}
		if(isset($data['molang']) && !is_string($data['molang'])){
			$this->error(ScriptExceptionMessage::TYPE_MOLANG);
		}
		if(isset($data['leveldata']) && !is_int($data['leveldata'])){
			$this->error(ScriptExceptionMessage::TYPE_LEVELDATA);
		}
		if(isset($data['shape_type'])){
			if(!is_string($data['shape_type'])){
				$this->error('todo');
			}
			$this->shape_type = match(strtolower($data['shape_type'])){
				'xz', 'x-z' => self::SHAPE_TYPE_XZ,
				'xy', 'x-y' => self::SHAPE_TYPE_XY,
				'zy', 'z-y' => self::SHAPE_TYPE_ZY,
				default => $this->error('todo')
			};
		}
		$this->data = $data;
	}

	private function error(string $message) : void{
		throw new ParticleScriptException("{$this->file->getFileName()}[{$this->name}]: " . $message);
	}

	/** @return LevelEventPacket[]|SpawnParticleEffectPacket[] */
	public function encode(
		Vector3 $pos,
		float $yaw = 0.0,
		float $pitch = 0.0
	) : array{
		$data = $this->data;
		$result = [];
		if(isset($data['extends'])){
			$file = $this->file;
			foreach($data['extends'] as $name){
				if(!is_string($name)){
					$this->error(ScriptExceptionMessage::TYPE_EXTENDS);
				}
				$script = $file->getScript($name);
				if($script instanceof ParticleScript){
					$result = array_merge($result, $script->encode($pos, $yaw, $pitch));
				}
			}
		}
		if(isset($data['offset'])){
			$offset = $data['offset'];
			$pos->add($offset[0], $offset[1] ?? 0, $offset[2] ?? 0);
		}
		$cpk = match($this->particle_type){
			self::PARTICLE_TYPE_INT => LevelEventPacket::standardParticle($data['particle'], $data['leveldata'] ?? 0, $pos),
			self::PARTICLE_TYPE_STRING => SpawnParticleEffectPacket::create(
				DimensionIds::OVERWORLD,
				-1,
				$pos,
				$data['particle'],
				$data['molang'] ?? null
			)
		};
		$shape_type = $this->shape_type;
		$unit = $data['unit'];
		$yaw = deg2rad($yaw);
		$pitch = deg2rad($pitch);
		$ysin = sin($yaw);
		$ycos = cos($yaw);
		$psin = sin($pitch);
		$pcos = cos($pitch);
		$x_center = count($data['shape']) / 2 - 0.5;
		foreach($data['shape'] as $x => $z_shape){
			if(!is_array($z_shape)){
				$this->error(ScriptExceptionMessage::TYPE_SHAPE);
			}
			$z_center = (count($z_shape) / 2) - 0.5;
			foreach($z_shape as $z => $y){
				if(!is_int($y)) continue;
				$dx = $dy = $dz = 0;
				if($shape_type === self::SHAPE_TYPE_XZ){
					$dx = ($x - $x_center) * $unit;
					$dy = $y * $unit;
					$dz = ($z - $z_center) * $unit;
				}elseif($shape_type === self::SHAPE_TYPE_XY){
					$dx = ($x - $x_center) * $unit;
					$dy = ($z - $z_center) * $unit;
					$dz = $y * $unit;
				}elseif($shape_type === self::SHAPE_TYPE_ZY){
					$dx = $y * $unit;
					$dy = ($z - $z_center) * $unit;
					$dz = ($x - $x_center) * $unit;
				}
				$pk = clone $cpk;
				$pk->position = $pos->add(
					$dx * $ycos + $dz * $ysin,
					$dy * $pcos,
					$dx * -$ysin + $dz * $ycos
				);
				$result[] = $pk;
			}
		}
		return $result;
	}

}