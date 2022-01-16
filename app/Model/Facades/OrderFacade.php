<?php

namespace App\Model\Facades;

use App\Model\Entities\Order;
use App\Model\Entities\OrderItem;
use App\Model\Repositories\OrderItemRepository;
use App\Model\Repositories\OrderRepository;

class OrderFacade{
    /** @var OrderRepository $cartRepository */
    private $orderRepository;
    /** @var OrderItemRepository $orderItemRepository */
    private $orderItemRepository;

    /**
     * Metoda vracející objednavku podle orderId
     * @param int $id
     * @return Order
     * @throws \Exception
     */
    public function getOrderById(int $id):Order {
        return $this->orderRepository->find($id);
    }

    /**
     * Metoda pro uložení položky v objednavce
     * @param OrderItem $orderItem
     */
    public function saveOrderItem(OrderItem $orderItem){
        $this->orderItemRepository->persist($orderItem);
    }

    /**
     * Metoda pro uložení objednavky
     * @param Order $order
     */
    public function saveOrder(Order $order){
        $order->lastModified = new \DateTime();
        $this->orderRepository->persist($order);
    }


    public function __construct(OrderRepository $orderRepository, OrderItemRepository $orderItemRepository){
        $this->orderRepository=$orderRepository;
        $this->orderItemRepository=$orderItemRepository;
    }
}