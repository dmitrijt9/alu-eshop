<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\WheelSizeEditForm\WheelSizeEditForm;
use App\AdminModule\Components\WheelSizeEditForm\WheelSizeEditFormFactory;
use App\Model\Facades\WheelSizesFacade;

/**
 * Class WheelSizePresenter
 * @package App\AdminModule\Presenters
 */
class WheelSizePresenter extends BasePresenter{
  /** @var WheelSizesFacade $wheelSizesFacade */
  private $wheelSizesFacade;
  /** @var WheelSizeEditFormFactory $wheelSizeEditFormFactory */
  private $wheelSizeEditFormFactory;

  /**
   * Akce pro vykreslení seznamu velikosti
   */
  public function renderDefault():void {
    $this->template->wheelSizes=$this->wheelSizesFacade->findWheelSizes(['order'=>'size']);
  }

  /**
   * Akce pro úpravu jedné velikosti
   * @param int $id
   * @throws \Nette\Application\AbortException
   */
  public function renderEdit(int $id):void {
    try{
      $wheelSize=$this->wheelSizesFacade->getWheelSize($id);
    }catch (\Exception $e){
      $this->flashMessage('Požadovaná velikost nebyla nalezena.', 'error');
      $this->redirect('default');
    }
    $form=$this->getComponent('wheelSizeEditForm');
    $form->setDefaults($wheelSize);
    $this->template->wheelSize=$wheelSize;
  }

  /**
   * Akce pro smazání velikosti
   * @param int $id
   * @throws \Nette\Application\AbortException
   */
  public function actionDelete(int $id):void {
    try{
      $wheelSize=$this->wheelSizesFacade->getWheelSize($id);
    }catch (\Exception $e){
      $this->flashMessage('Požadovaná velikost nebyla nalezena.', 'error');
      $this->redirect('default');
    }

    if (!$this->user->isAllowed($wheelSize,'delete')){
      $this->flashMessage('Tuto velikost není možné smazat.', 'error');
      $this->redirect('default');
    }

    if ($this->wheelSizesFacade->deleteWheelSize($wheelSize)){
      $this->flashMessage('Velikost byla smazána.', 'info');
    }else{
      $this->flashMessage('Tuto velikost není možné smazat.', 'error');
    }

    $this->redirect('default');
  }

  /**
   * Formulář na editaci velikosti
   * @return WheelSizeEditForm
   */
  public function createComponentWheelSizeEditForm():WheelSizeEditForm {
    $form = $this->wheelSizeEditFormFactory->create();
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
  public function injectWheelSizesFacade(WheelSizesFacade $wheelSizesFacade){
    $this->wheelSizesFacade=$wheelSizesFacade;
  }
  public function injectWheelSizeEditFormFactory(WheelSizeEditFormFactory $wheelSizeEditFormFactory){
    $this->wheelSizeEditFormFactory=$wheelSizeEditFormFactory;
  }
  #endregion injections

}
