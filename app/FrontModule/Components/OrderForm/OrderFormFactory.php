<?php

namespace App\FrontModule\Components\OrderForm;

/**
 * Interface OrderFormFactory
 * @package App\FrontModule\Components\OrderForm
 */
interface OrderFormFactory{

    public function create():OrderForm;

}
