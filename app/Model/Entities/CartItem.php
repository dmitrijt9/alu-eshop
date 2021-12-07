<?php

namespace App\Model\Entities;

use LeanMapper\Entity;

/**
 * Class CartItem
 * @package App\Model\Entities
 * @property int $cartItemId
 * @property Product $product m:hasOne
 * @property Cart $cart m:hasOne
 * @property int $count
 */
class CartItem extends Entity implements \Nette\Security\Resource{

    /**
     * @inheritDoc
     */
    function getResourceId():string{
        return 'CartItem';
    }
}