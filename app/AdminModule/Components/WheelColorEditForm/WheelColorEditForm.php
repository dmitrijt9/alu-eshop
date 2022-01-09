<?php

namespace App\AdminModule\Components\WheelColorEditForm;

use App\Model\Entities\WheelColor;
use App\Model\Facades\WheelColorsFacade;
use Nette;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;
use Nette\SmartObject;
use Nextras\FormsRendering\Renderers\Bs4FormRenderer;
use Nextras\FormsRendering\Renderers\FormLayout;

/**
 * Class WheelColorEditForm
 * @package App\AdminModule\Components\WheelColorEditForm
 *
 * @method onFinished(string $message = '')
 * @method onFailed(string $message = '')
 * @method onCancel()
 */
class WheelColorEditForm extends Form{

  use SmartObject;

  /** @var callable[] $onFinished */
  public $onFinished = [];
  /** @var callable[] $onFailed */
  public $onFailed = [];
  /** @var callable[] $onCancel */
  public $onCancel = [];
  /** @var WheelColorsFacade $wheelColorsFacade */
  private $wheelColorsFacade;

  /**
   * TagEditForm constructor.
   * @param Nette\ComponentModel\IContainer|null $parent
   * @param string|null $name
   * @param WheelColorsFacade $wheelColorsFacade
   * @noinspection PhpOptionalBeforeRequiredParametersInspection
   */
  public function __construct(Nette\ComponentModel\IContainer $parent = null, string $name = null, WheelColorsFacade $wheelColorsFacade){
    parent::__construct($parent, $name);
    $this->setRenderer(new Bs4FormRenderer(FormLayout::VERTICAL));
    $this->wheelColorsFacade=$wheelColorsFacade;
    $this->createSubcomponents();
  }

  private function createSubcomponents(){
    $wheelColorId=$this->addHidden('wheelColorId');
    $this->addText('color','Barva')
      ->setRequired('Musíte zadat barvu');
    $this->addTextArea('description','Popis barvy')
      ->setRequired(false);
    $this->addSubmit('ok','uložit')
      ->onClick[]=function(SubmitButton $button){
        $values=$this->getValues('array');
        if (!empty($values['wheelColorId'])){
          try{
            $wheelColor=$this->wheelColorsFacade->getWheelColor($values['wheelColorId']);
          }catch (\Exception $e){
            $this->onFailed('Požadovaná barva nebyla nalezena.');
            return;
          }
        }else{
          $wheelColor=new WheelColor();
        }
        $wheelColor->assign($values,['color','description']);
        $this->wheelColorsFacade->saveWheelColor($wheelColor);
        $this->setValues(['wheelColorId'=>$wheelColor->wheelColorId]);
        $this->onFinished('Barva byla uložena.');
      };
    $this->addSubmit('storno','zrušit')
      ->setValidationScope([$wheelColorId])
      ->onClick[]=function(SubmitButton $button){
        $this->onCancel();
      };
  }

  /**
   * Metoda pro nastavení výchozích hodnot formuláře
   * @param WheelColor|array|object $values
   * @param bool $erase = false
   * @return $this
   */
  public function setDefaults($values, bool $erase = false):self {
    if ($values instanceof WheelColor){
      $values = [
        'wheelColorId'=>$values->wheelColorId,
        'color'=>$values->color,
        'description'=>$values->description
      ];
    }
    parent::setDefaults($values, $erase);
    return $this;
  }

}