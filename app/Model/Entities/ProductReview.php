<?php

namespace App\Model\Entities;

use LeanMapper\Entity;

/**
 * Class ProductReview
 * @package App\Model\Entities
 * @property int $productReviewId
 * @property string $text
 * @property int $stars
 * @property-read Product $product m:hasOne
 * @property-read User $author m:hasOne
 */
class ProductReview extends Entity implements \Nette\Security\Resource{

  /**
   * @inheritDoc
   */
  function getResourceId():string{
    return 'ProductReview';
  }
}