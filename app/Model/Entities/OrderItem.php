<?php

namespace App\Model\Entities;

use LeanMapper\Entity;

/**
 * Class OrderItem
 * @package App\Model\Entities
 * @property int $orderItemId
 * @property Product $product m:hasOne
 * @property Order $order m:hasOne
 * @property int $count = 0
 */
class OrderItem extends Entity implements \Nette\Security\Resource{

    /**
     * @inheritDoc
     */
    function getResourceId():string{
        return 'OrderItem';
    }
}