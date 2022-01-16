<?php

namespace App\AdminModule\Presenters;

use App\Model\Facades\OrderFacade;
use Nette\Application\UI\Form;


/**
 * Class ProductPresenter
 * @package App\AdminModule\Presenters
 */
class OrderPresenter extends BasePresenter
{
    /** @var OrderFacade $orderFacade */
    private $orderFacade;

    /**
     * Akce pro vykreslení seznamu produktů
     */
    public function renderDefault(): void
    {
        $this->template->orders = $this->orderFacade->findCategories(['order' => 'last_modified']);
    }

    public function createComponentOrderForm(): Form
    {
        $form = new Form;
        $form->addSelect(
            'status',
            'status',
            [
                'NEW' => 'Nová',
                'ACCEPTED' => 'Přijatá',
                'OUT' => 'Odeslaná',
                'CLOSED' => 'Uzavřená',
                'DECLINED' => 'Odmítnutá'
            ]
        );
        $form->addSubmit('changeStatus', 'Změnit stav');
        $form->addHidden('orderId');
        $form->onSubmit[]=function($form) {
            try {
                $values = $form->getValues();
                $order = $this->orderFacade->getOrderById($values['orderId']);
                if(!$order->canChangeStatusTo($values['status'])) {
                    $this->flashMessage('Tato změna stavu není povolena', 'warning');
                    return;
                }
                $order->assign($values, ['status']);
                $this->orderFacade->saveOrder($order);
                $this->flashMessage('Změna byla uložena.');
            } catch(\Error $e) {
                $this->flashMessage('Změnu stavu se nepodařilo uložit', 'error');
            }
        };
        return $form;
    }

    /**
     * Akce pro úpravu jednoho produktu
     * @param int $id
     * @throws \Nette\Application\AbortException
     */
    public function renderEdit(int $id): void
    {
        try {
            $order = $this->orderFacade->getOrderById($id);
        } catch (\Exception $e) {
            $this->flashMessage('Požadovaný produkt nebyl nalezen.', 'error');
            $this->redirect('default');
        }
        if (!$this->user->isAllowed($order, 'edit')) {
            $this->flashMessage('Požadovaný produkt nemůžete upravovat.', 'error');
            $this->redirect('default');
        }

        $this->template->order = $order;
        $this->template->orderState = $order->getState();
    }

    #region injections
    public function injectOrderFacade(OrderFacade $orderFacade){
        $this->orderFacade=$orderFacade;
    }
    #endregion injections
}
