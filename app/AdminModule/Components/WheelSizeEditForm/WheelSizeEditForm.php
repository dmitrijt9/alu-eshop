<?php

namespace App\AdminModule\Components\WheelSizeEditForm;

use App\Model\Entities\WheelSize;
use App\Model\Facades\WheelSizesFacade;
use Nette;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;
use Nette\SmartObject;
use Nextras\FormsRendering\Renderers\Bs4FormRenderer;
use Nextras\FormsRendering\Renderers\FormLayout;

/**
 * Class WheelSizeEditForm
 * @package App\AdminModule\Components\WheelSizeEditForm
 *
 * @method onFinished(string $message = '')
 * @method onFailed(string $message = '')
 * @method onCancel()
 */
class WheelSizeEditForm extends Form{

  use SmartObject;

  /** @var callable[] $onFinished */
  public $onFinished = [];
  /** @var callable[] $onFailed */
  public $onFailed = [];
  /** @var callable[] $onCancel */
  public $onCancel = [];
  /** @var WheelSizesFacade $wheelSizesFacade */
  private $wheelSizesFacade;

  /**
   * TagEditForm constructor.
   * @param Nette\ComponentModel\IContainer|null $parent
   * @param string|null $name
   * @param WheelSizesFacade $wheelSizesFacade
   * @noinspection PhpOptionalBeforeRequiredParametersInspection
   */
  public function __construct(Nette\ComponentModel\IContainer $parent = null, string $name = null, WheelSizesFacade $wheelSizesFacade){
    parent::__construct($parent, $name);
    $this->setRenderer(new Bs4FormRenderer(FormLayout::VERTICAL));
    $this->wheelSizesFacade=$wheelSizesFacade;
    $this->createSubcomponents();
  }

  private function createSubcomponents(){
    $wheelSizeId=$this->addHidden('wheelSizeId');
    $this->addInteger('size','Velikost')
      ->setRequired('Musíte zadat velikost');
    $this->addTextArea('description','Popis velikosti')
      ->setRequired(false);
    $this->addSubmit('ok','uložit')
      ->onClick[]=function(SubmitButton $button){
        $values=$this->getValues('array');
        if (!empty($values['wheelSizeId'])){
          try{
            $wheelSize=$this->wheelSizesFacade->getWheelSize($values['wheelSizeId']);
          }catch (\Exception $e){
            $this->onFailed('Požadovaná velikost nebyla nalezena.');
            return;
          }
        }else{
          $wheelSize=new WheelSize();
        }
        $wheelSize->assign($values,['size','description']);
        $this->wheelSizesFacade->saveWheelSize($wheelSize);
        $this->setValues(['wheelSizeId'=>$wheelSize->wheelSizeId]);
        $this->onFinished('Velikost byla uložena.');
      };
    $this->addSubmit('storno','zrušit')
      ->setValidationScope([$wheelSizeId])
      ->onClick[]=function(SubmitButton $button){
        $this->onCancel();
      };
  }

  /**
   * Metoda pro nastavení výchozích hodnot formuláře
   * @param WheelSize|array|object $values
   * @param bool $erase = false
   * @return $this
   */
  public function setDefaults($values, bool $erase = false):self {
    if ($values instanceof WheelSize){
      $values = [
        'wheelSizeId'=>$values->wheelSizeId,
        'title'=>$values->size,
        'description'=>$values->description
      ];
    }
    parent::setDefaults($values, $erase);
    return $this;
  }

}