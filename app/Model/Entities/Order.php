<?php

namespace App\Model\Entities;

use LeanMapper\Entity;


/**
 * Class Order
 * @package App\Model\Entities
 * @property int $orderId
 * @property int|null $userId = null
 * @property OrderItem[] $items m:belongsToMany
 * @property \DateTime|null $lastModified
 * @property string $status = CREATED
 * @property string $userEmail
 * @property string $userName
 * @property string $userAddress
 * @property float $totalPrice
 *
 * @method addToItems(OrderItem $orderItem)
 */
class Order extends Entity implements \Nette\Security\Resource{

    private $OrderStatuses = [
        'NEW' => 1,
        'ACCEPTED' => 2,
        'DECLINED' => 9,
    ];
    /**
     * @inheritDoc
     */
    function getResourceId():string{
        return 'Order';
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
                $totalPrice+=$item->price*$item->count;
            }
        }

        return $totalPrice;
    }

    public function getState() {
        switch($this->status) {
            case 'NEW': return 'Nová';
            case 'ACCEPTED': return 'Přijatá';
            case 'DECLINED': return 'Odmítnutá';
        }
    }

    public function canChangeStatusTo(string $newStatus) {
        return $this->OrderStatuses[$this->status] < $this->OrderStatuses[$newStatus];
    }
}

