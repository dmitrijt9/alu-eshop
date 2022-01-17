<?php

namespace App\AdminModule\Presenters;

use App\Model\Facades\OrderFacade;

class DashboardPresenter extends BasePresenter{

    /** @var OrderFacade $orderFacade */
    private $orderFacade;

    public function renderDefault(): void
    {
        $this->template->newOrders = $this->orderFacade->findOrders(['status' => 'NEW']);
    }

    #region injections
    public function injectOrderFacade(OrderFacade $orderFacade){
        $this->orderFacade=$orderFacade;
    }
    #endregion injections

}
