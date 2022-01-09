<?php

namespace App\Model\Entities;

use LeanMapper\Entity;

/**
 * Class WheelSize
 * @package App\Model\Entities
 * @property int $wheelSizeId
 * @property int $size
 * @property string|null $description
 */
class WheelSize extends Entity implements \Nette\Security\Resource{

  /**
   * @inheritDoc
   */
  function getResourceId():string{
    return 'WheelSize';
  }
}