<?php

namespace App\Model\Entities;

use LeanMapper\Entity;

/**
 * Class WheelColor
 * @package App\Model\Entities
 * @property int $wheelColorId
 * @property string $color
 * @property string $description
 */
class WheelColor extends Entity implements \Nette\Security\Resource{

  /**
   * @inheritDoc
   */
  function getResourceId():string{
    return 'WheelColor';
  }
}