<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\WheelColorEditForm\WheelColorEditForm;
use App\AdminModule\Components\WheelColorEditForm\WheelColorEditFormFactory;
use App\Model\Facades\WheelColorsFacade;

/**
 * Class WheelColorPresenter
 * @package App\AdminModule\Presenters
 */
class WheelColorPresenter extends BasePresenter{
  /** @var WheelColorsFacade $wheelColorsFacade */
  private $wheelColorsFacade;
  /** @var WheelColorEditFormFactory $wheelColorEditFormFactory */
  private $wheelColorEditFormFactory;

  /**
   * Akce pro vykreslení seznamu barev
   */
  public function renderDefault():void {
    $this->template->wheelColors=$this->wheelColorsFacade->findWheelColors(['order'=>'color']);
  }

  /**
   * Akce pro úpravu jedné barvy
   * @param int $id
   * @throws \Nette\Application\AbortException
   */
  public function renderEdit(int $id):void {
    try{
      $wheelColor=$this->wheelColorsFacade->getWheelColor($id);
    }catch (\Exception $e){
      $this->flashMessage('Požadovaná barva nebyla nalezena.', 'error');
      $this->redirect('default');
    }
    $form=$this->getComponent('wheelColorEditForm');
    $form->setDefaults($wheelColor);
    $this->template->wheelColor=$wheelColor;
  }

  /**
   * Akce pro smazání barvy
   * @param int $id
   * @throws \Nette\Application\AbortException
   */
  public function actionDelete(int $id):void {
    try{
      $wheelColor=$this->wheelColorsFacade->getWheelColor($id);
    }catch (\Exception $e){
      $this->flashMessage('Požadovaná barva nebyla nalezena.', 'error');
      $this->redirect('default');
    }

    if (!$this->user->isAllowed($wheelColor,'delete')){
      $this->flashMessage('Tuto barva není možné smazat.', 'error');
      $this->redirect('default');
    }

    if ($this->wheelColorsFacade->deleteWheelColor($wheelColor)){
      $this->flashMessage('Velikost byla smazána.', 'info');
    }else{
      $this->flashMessage('Tuto barva není možné smazat.', 'error');
    }

    $this->redirect('default');
  }

  /**
   * Formulář na editaci barvy
   * @return WheelColorEditForm
   */
  public function createComponentWheelColorEditForm():WheelColorEditForm {
    $form = $this->wheelColorEditFormFactory->create();
    $form->onCancel[]=function(){
      $this->redirect('default');
    };
    $form->onFinished[]=function($message=null){
      if (!empty($message)){
        $this->flashMessage($message);
      }
      $this->redirect('default');
    };
    $form->onFailed[]=function($message=null){
      if (!empty($message)){
        $this->flashMessage($message,'error');
      }
      $this->redirect('default');
    };
    return $form;
  }

  #region injections
  public function injectWheelColorsFacade(WheelColorsFacade $wheelColorsFacade){
    $this->wheelColorsFacade=$wheelColorsFacade;
  }
  public function injectWheelColorEditFormFactory(WheelColorEditFormFactory $wheelColorEditFormFactory){
    $this->wheelColorEditFormFactory=$wheelColorEditFormFactory;
  }
  #endregion injections

}
