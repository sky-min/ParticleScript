# ParticleScript
Do you have difficulty expressing particles?

It takes a bit of hard work, but there is an easy way to do it.

## how to use
### example ParticleScriptFile
Particle script file is yaml.
```YAML
particles:
  black_dust:
    particle: 32
  green_mob:
    particle: minecraft:mobspell_emitter
    data: '[{"name":"variable.color","value":{"type":"member_array","value":[{"name":".r","value":{"type":"float","value":0}},{"name":".g","value":{"type":"float","value":1}},{"name":".b","value":{"type":"float","value":0}},{"name":".a","value":{"type":"float","value":1}}]}}]'
  red_50:
    particle: 12
    data: 500

scripts:
  test:
    unit: 1
    shape: [
      [0, 0, 'black_dust', 0, 0], 
      [0, 'black_dust', 0, 'black_dust', 0],
      ['black_dust', 0, 0, 0, 'black_dust'],
      [0, 'black_dust', 0, 'black_dust', 0],
      [0, 0, 'black_dust', 0, 0]
    ]
    extends:
      - abstract1
      - abstract2
  abstract1:
    particle: minecraft:mobspell_emitter
    unit: 0.5
    shape: [
      ['green_mob', 0, 'green_mob'],
      [0, 'green_mob', 0],
      ['green_mob', 0, 'green_mob']
    ]
  abstract2:
    unit: 0.5
    offset: [0, 0.5]
    shape: [
      [0, 0, 'red_50', 0, 0], 
      [0, 'red_50', 0, 'red_50', 0],
      ['red_50', 0, 0, 0, 'red_50'],
      [0, 'red_50', 0, 'red_50', 0],
      [0, 0, 'red_50', 0, 0]
    ]
```

### api
#### register ScriptFile
```php
use skymin\ParticleScript\ScriptManager;

ScriptManager::registerFile(string $fileName);
```
#### Load the registered script file
```php
use skymin\ParticleScript\script\ParticleScriptFile;

ScriptManager::getScriptFile(string $fileName) : ParticleScriptFile;
```

#### GetScript
```php
use skymin\ParticleScript\script\ParticleScriptFile;
use skymin\ParticleScript\script\ParticleScript;

ParticleScriptFile::getScript(string $name) : ?ParticleScript;
```

#### send Particle
```php
$pks = ScriptManager::getScriptFile($fileName)->getScript($scriptName)->encode(Vector3 $pos, float $yaw, float $pitch);
/** @var Player[] $viwers */
Server::getInstance()->broadcastPackets($viwers, $pks);
```

## testplugin
[here](https://github.com/sky-min/ParticleScriptTest)

## image
![Screenshot_20220608-233354_Minecraft](https://user-images.githubusercontent.com/81374952/172644243-424f5876-140f-4016-8d39-46116fe9e3f5.jpg)