<?php


namespace App\FrontModule\Presenters;


use App\FrontModule\Components\CartControl\CartControl;
use App\FrontModule\Components\OrderForm\OrderForm;
use App\FrontModule\Components\OrderForm\OrderFormFactory;

class OrderPresenter extends BasePresenter{

    /** @var OrderFormFactory $orderFormFactory */
    private $orderFormFactory;

    public function createComponentOrderForm() {
        $form = $this->orderFormFactory->create();
        $form->onSubmit[]=function(OrderForm $form){
            try{
//                $product = $this->productsFacade->getProduct($form->values->productId);
                //kontrola zakoupitelnosti
            }catch (\Exception $e){
                $this->flashMessage('Chyba behem vytvoreni objednavky.','error');
                $this->redirect('this');
            }
            //smazani košíku
            /** @var CartControl $cart */
            $cart = $this->getComponent('cart');
            $cart->unsetSessionCart();
            $this->redirect('this');
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
    #endregion injections
}