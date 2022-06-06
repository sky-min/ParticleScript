<?php
declare(strict_types = 1);

namespace skymin\ParticleScript\exception;

final class ScriptExceptionMessage{

	public const SCRIPT_NAME = "The particle script name must be a string.";

	public const REQUIRE_PARTICLE = "The 'particle' key required by the particle script is missing.";
	public const REQUIRE_SHAPE = "The 'shape' key required by the particle script is missing.";
	public const REQUIRE_UNIT = "The 'unit' key required by the particle script is missing.";

	public const TYPE_PARTICLE = "The 'particle' key must is a string or int.";
	public const TYPE_SHAPE = "The 'shape' key must is a 2D array.";
	public const TYPE_UNIT = "The 'unit' key must is a string or int.";
	public const TYPE_EXTENDS = "The 'extends' key must one-dimensional array of strings.";
	public const TYPE_OFFSET = "The 'offset' key must one-dimensional array of int or float";

}