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
use function is_string;

final class CustomParticle{

	private const PARTICLE_TYPE_INT = 0;
	private const PARTICLE_TYPE_STRING = 1;
	private const PARTICLE_TYPE_ERROR = 2;

	private int|string $particleId;
	private int $particleType;
	private null|int|string $particleData = null;

	public function __construct(string $fileName, string $name, private array $data){
		if(!isset($data['particle'])){
			throw new ParticleScriptException("{$fileName}[particles][$name]: " . ScriptExceptionMessage::REQUIRE_PARTICLE);
		}
		$particleId = $data['particle'];
		$this->particleType = $type = match(true){
			is_int($particleId) =>  self::PARTICLE_TYPE_INT,
			is_string($particleId) => self::PARTICLE_TYPE_STRING,
			default => self::PARTICLE_TYPE_ERROR
		};
		if($type === self::PARTICLE_TYPE_ERROR){
			throw new ParticleScriptException("{$fileName}[particles][$name]: " . ScriptExceptionMessage::TYPE_PARTICLE);
		}
		if(isset($data['data'])){
			$particleData = $data['data'];
			if((is_string($particleData) && $type === self::PARTICLE_TYPE_STRING)
			|| (is_int($particleData) && $type === self::PARTICLE_TYPE_INT)){
				$this->particleData = $particleData;
			}
		}
		$this->particleId = $particleId;
	}

	public function encode(Vector3 $vector) : LevelEventPacket|SpawnParticleEffectPacket{
		return match($this->particleType){
			self::PARTICLE_TYPE_INT => LevelEventPacket::standardParticle(
				$this->particleId,
				$this->particleData ?? 0,
				$vector
			),
			self::PARTICLE_TYPE_STRING => SpawnParticleEffectPacket::create(
				DimensionIds::OVERWORLD,
				-1,
				$vector,
				$this->particleId,
				$this->particleData
			)
		};
	}

}