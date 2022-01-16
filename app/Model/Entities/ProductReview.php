<?php

namespace App\Model\Entities;

use LeanMapper\Entity;

/**
 * Class ProductReview
 * @package App\Model\Entities
 * @property int $productReviewId
 * @property string $text
 * @property int $stars
 * @property Product $product m:hasOne
 * @property User $user m:hasOne
 */
class ProductReview extends Entity implements \Nette\Security\Resource{

  /**
   * @inheritDoc
   */
  function getResourceId():string{
    return 'ProductReview';
  }
}
