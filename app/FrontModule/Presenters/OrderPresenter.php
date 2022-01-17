<?php


namespace App\FrontModule\Presenters;


use App\FrontModule\Components\CartControl\CartControl;
use App\FrontModule\Components\OrderForm\OrderForm;
use App\FrontModule\Components\OrderForm\OrderFormFactory;
use App\Model\Entities\Order;
use App\Model\Entities\OrderItem;
use App\Model\Facades\OrderFacade;
use App\Model\Facades\ProductsFacade;
use App\Model\Facades\UsersFacade;

class OrderPresenter extends BasePresenter{

    /** @var OrderFormFactory $orderFormFactory */
    private $orderFormFactory;

    /** @var UsersFacade $usersFacade */
    private $usersFacade;

    /** @var OrderFacade $orderFacade */
    private $orderFacade;

    public function renderDefault():void {
        if($this->user->isLoggedIn()) {
            $form=$this->getComponent('orderForm');
            $form->setDefaults($this->usersFacade->getUser($this->user->getId()));
        }

        /** @var CartControl $cart */
        $cart = $this->getComponent('cart');
        $this->template->cart=$cart->getCart();
    }

    public function renderSuccess():void {
        if($this->getParameter('orderId') !== null) {
            $order = $this->orderFacade->getOrderById($this->getParameter('orderId'));
            $this->template->order = $order;
        } else {
            throw new \Error("Taková objednávka neexistuje", 404);
        }


    }

    public function createComponentOrderForm() {
        $form = $this->orderFormFactory->create();

        $form->onSubmit[]=function(OrderForm $form){
            /** @var CartControl $cartComp */
            $cartComp = $this->getComponent('cart');
            $cart = $cartComp->getCart();
            try{
                $order = new Order();
                $order->userEmail = $form->values->userEmail;
                $order->userName = $form->values->userName;
                $order->userAddress = $form->values->userAddress;
                $order->totalPrice = $cart->getTotalPrice();

                if ($this->user->isLoggedIn()) {
                    $order->userId = $this->user->getId();
                }
                $this->orderFacade->saveOrder($order);


                $orderItems = [];
                foreach ($cart->items as $item) {
                    $orderItem = new OrderItem();
                    $orderItem->product = $item->product;
                    $orderItem->count = $item->count;
                    $orderItem->price = $item->product->price;
                    $orderItem->order = $order;
                    $this->orderFacade->saveOrderItem($orderItem);
                }

            }catch (\Exception $e){
                $this->flashMessage('Chyba behem vytvoreni objednavky.','error');
                $this->redirect('this');
            }
            //smazani košíku
            /** @var CartControl $cart */
            $cart = $this->getComponent('cart');
            if($this->user->isLoggedIn()) {
                $cart->deleteUsersCart($this->user->getId());
            } else {
                $cart->unsetSessionCart();
            }

            $this->redirect('Order:success', ["orderId" => $order->orderId]);
        };

        $form->onCancel[]=function()use($form){
            $this->redirect('Cart:default');
        };
        return $form;
    }

    #region injections
    public function injectOrderFormFactory(OrderFormFactory $orderFormFactory):void {
        $this->orderFormFactory=$orderFormFactory;
    }
    public function injectUsersFacade(UsersFacade $usersFacade):void {
        $this->usersFacade=$usersFacade;
    }
    public function injectOrderFacade(OrderFacade $orderFacade):void {
        $this->orderFacade=$orderFacade;
    }
    #endregion injections
}