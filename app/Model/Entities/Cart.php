<?php

namespace App\Model\Entities;

use LeanMapper\Entity;

/**
 * Class Cart
 * @package App\Model\Entities
 * @property int $cartId
 * @property int|null $userId = null
 * @property CartItem[] $items m:belongsToMany
 * @property \DateTime|null $lastModified
 *
 * @method addToItems(CartItem $cartItem)
 * @method removeFromItems(CartItem $cartItem)
 * @method removeAllItems()
 */
class Cart extends Entity implements \Nette\Security\Resource{

    /**
     * @inheritDoc
     */
    function getResourceId():string{
        return 'Cart';
    }

    public function updateCartItems(){
        $this->row->cleanReferencingRowsCache('cart_item');//smažeme cache, aby se položky v košíku znovu načetly z DB bez nutnosti načtení celého košíku
    }

    public function getTotalCount() {
        $totalCount = 0;

        if (!empty($this->items)) {
            foreach ($this->items as $item) {
                $totalCount+=$item->count;
            }
        }

        return $totalCount;
    }

    public function getTotalPrice() {
        $totalPrice = 0;

        if (!empty($this->items)) {
            foreach ($this->items as $item) {
                $totalPrice+=$item->product->price*$item->count;
            }
        }

        return $totalPrice;
    }
}