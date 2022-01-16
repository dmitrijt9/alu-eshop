<?php

namespace App\FrontModule\Components\ProductCartForm;

use App\FrontModule\Components\CartControl\CartControl;
use App\Model\Facades\ProductsFacade;
use Nette;
use Nette\Application\UI\Form;
use Nette\SmartObject;
use Nextras\FormsRendering\Renderers\Bs4FormRenderer;
use Nextras\FormsRendering\Renderers\FormLayout;

/**
 * Class ProductCartForm
 * @package App\FrontModule\Components\ProductCartForm
 *
 * @method onFinished()
 */
class ProductCartForm extends Form{

  use SmartObject;

  /** @var CartControl $cartControl */
  private $cartControl;

  /** @var ProductsFacade $productsFacade */
  private $productsFacade;

  /**
   * ProductCartForm constructor.
   * @param Nette\ComponentModel\IContainer|null $parent
   * @param string|null $name
   */
  public function __construct(Nette\ComponentModel\IContainer $parent = null, string $name = null, ProductsFacade $productsFacade){
    parent::__construct($parent, $name);
    $this->productsFacade = $productsFacade;
    $this->setRenderer(new Bs4FormRenderer(FormLayout::HORIZONTAL));
    $this->createSubcomponents();
  }

  /**
   * Metoda pro předání komponenty košíku jako závislosti
   * @param CartControl $cartControl
   */
  public function setCartControl(CartControl $cartControl):void {
    $this->cartControl=$cartControl;
  }

  private function createSubcomponents(){
    $this->addHidden('productId');
    $this->addInteger('count','Počet kusů')
      ->addRule(Form::RANGE,'Chybný počet kusů.',[1,100]);

    $this->addSubmit('ok','přidat do košíku');
//      ->onClick[]=function(SubmitButton $button){
//        //uložení nového hesla
//        $values=$this->getValues('array');
//
//        try{
//            $product = $this->productsFacade->getProduct($values['productId']);
//            $this->cartControl->addToCart($product, $values['count']);
//        }catch (\Exception $e){
//            $this->onFailed('Zvolený produkt nebyl nalezen.');
//            return;
//        }
//        $this->onFinished('Produkt byl pridan do kosiku.');
//      };
  }

}