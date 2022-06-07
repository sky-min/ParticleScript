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

namespace skymin\ParticleScript\exception;

final class ScriptExceptionMessage{

	public const SCRIPT_NAME = "The particle script name must be a string.";

	public const REQUIRE_PARTICLE = "The 'particle' key required by the particle script is missing.";
	public const REQUIRE_SHAPE = "The 'shape' key required by the particle script is missing.";
	public const REQUIRE_UNIT = "The 'unit' key required by the particle script is missing.";

	public const TYPE_PARTICLE = "The 'particle' key must is a string or int.";
	public const TYPE_SHAPE = "The 'shape' key must is a 2D array.";
	public const TYPE_UNIT = "The 'unit' key must is a float or int.";
	public const TYPE_EXTENDS = "The 'extends' key must is one-dimensional array of strings.";
	public const TYPE_OFFSET = "The 'offset' key must is one-dimensional array of int or float.";
	public const TYPE_MOLANG = "The 'molang' key must is string.";
	public const TYPE_LEVELDATA = "The 'leveldata' key must is int.";

}