<?php

namespace App\FrontModule\Presenters;

use App\Model\Facades\ProductsFacade;

class HomepagePresenter extends BasePresenter{

    /** @var ProductsFacade $productsFacade */
    private $productsFacade;

    /**
     * Akce pro vykreslení seznamu produktů
     */
    public function renderDefault():void {
        $this->template->products=$this->productsFacade->findProducts(['order'=>'title']);
    }

    public function injectProductsFacade(ProductsFacade $productsFacade){
        $this->productsFacade=$productsFacade;
    }

}
