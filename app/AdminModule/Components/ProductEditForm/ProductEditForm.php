<?php

namespace App\AdminModule\Components\ProductEditForm;

use App\Model\Entities\Product;
use App\Model\Facades\CategoriesFacade;
use App\Model\Facades\ProductsFacade;
use App\Model\Facades\WheelColorsFacade;
use App\Model\Facades\WheelSizesFacade;
use Nette;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;
use Nette\SmartObject;
use Nextras\FormsRendering\Renderers\Bs4FormRenderer;
use Nextras\FormsRendering\Renderers\FormLayout;

/**
 * Class ProductEditForm
 * @package App\AdminModule\Components\ProductEditForm
 *
 * @method onFinished(string $message = '')
 * @method onFailed(string $message = '')
 * @method onCancel()
 */
class ProductEditForm extends Form{

  use SmartObject;

  /** @var callable[] $onFinished */
  public $onFinished = [];
  /** @var callable[] $onFailed */
  public $onFailed = [];
  /** @var callable[] $onCancel */
  public $onCancel = [];
  /** @var CategoriesFacade */
  private $categoriesFacade;
  /** @var WheelSizesFacade */
  private $wheelSizesFacade;
  /** @var WheelColorsFacade */
  private $wheelColorsFacade;
  /** @var ProductsFacade $productsFacade */
  private $productsFacade;

  /**
   * TagEditForm constructor.
   * @param Nette\ComponentModel\IContainer|null $parent
   * @param string|null $name
   * @param ProductsFacade $productsFacade
   * @noinspection PhpOptionalBeforeRequiredParametersInspection
   */
  public function __construct(Nette\ComponentModel\IContainer $parent = null, string $name = null, CategoriesFacade $categoriesFacade, ProductsFacade $productsFacade, WheelSizesFacade $wheelSizesFacade, WheelColorsFacade $wheelColorsFacade){
    parent::__construct($parent, $name);
    $this->setRenderer(new Bs4FormRenderer(FormLayout::VERTICAL));
    $this->categoriesFacade=$categoriesFacade;
    $this->productsFacade=$productsFacade;
    $this->wheelSizesFacade=$wheelSizesFacade;
    $this->wheelColorsFacade=$wheelColorsFacade;
    $this->createSubcomponents();
  }

  private function createSubcomponents(){
    $productId=$this->addHidden('productId');
    $this->addText('title','N??zev produktu')
      ->setRequired('Mus??te zadat n??zev produktu')
      ->setMaxLength(100);

    $this->addText('url','URL produktu')
      ->setMaxLength(100)
      ->addFilter(function(string $url){
        return Nette\Utils\Strings::webalize($url);
      })
      ->addRule(function(Nette\Forms\Controls\TextInput $input)use($productId){
        try{
          $existingProduct = $this->productsFacade->getProductByUrl($input->value);
          return $existingProduct->productId==$productId->value;
        }catch (\Exception $e){
          return true;
        }
      },'Zvolen?? URL je ji?? obsazena jin??m produktem');

    #region kategorie
    $categories=$this->categoriesFacade->findCategories();
    $categoriesArr=[];
    foreach ($categories as $category){
      $categoriesArr[$category->categoryId]=$category->title;
    }
    $this->addSelect('categoryId','Kategorie',$categoriesArr)
      ->setPrompt('--vyberte kategorii--')
      ->setRequired(false);
    #endregion kategorie
    #region velikost
    $wheelSizes=$this->wheelSizesFacade->findWheelSizes();
    $wheelSizesArr=[];
    foreach ($wheelSizes as $wheelSize){
      $wheelSizesArr[$wheelSize->wheelSizeId]=$wheelSize->size;
    }
    $this->addSelect('wheelSizeId','Velikost',$wheelSizesArr)
      ->setPrompt('--vyberte velikost--')
      ->setRequired(false);
    #endregion velikost

    #region barva
    $wheelColors=$this->wheelColorsFacade->findWheelColors();
    $wheelColorsArr=[];
    foreach ($wheelColors as $wheelColor){
      $wheelColorsArr[$wheelColor->wheelColorId]=$wheelColor->color;
    }
    $this->addSelect('wheelColorId','Barva',$wheelColorsArr)
      ->setPrompt('--vyberte barvu--')
      ->setRequired(false);
    #endregion barva

    $this->addTextArea('description', 'Popis produktu')
      ->setRequired('Zadejte popis produktu.');

    $this->addText('price', 'Cena')
      ->setHtmlType('number')
      ->setRequired('Mus??te zadat cenu produktu')
        ->addCondition(Form::FILLED)
    ->addRule(Form::NUMERIC, 'Cena mus?? b??t v ????seln??m form??tu');//tady by mohly b??t dal???? kontroly pro min, max atp.

    $this->addCheckbox('available', 'Nab??zeno ke koupi')
      ->setDefaultValue(true);

    #region obr??zek
    $photoUpload=$this->addUpload('photo','Fotka produktu');
    //pokud nen?? zadan?? ID produktu, je nahr??n?? fotky povinn??
    $photoUpload //vy??adov??n?? nahr??n?? souboru, pokud nen?? zn??m?? productId
      ->addConditionOn($productId, Form::EQUAL, '')
        ->setRequired('Pro ulo??en?? nov??ho produktu je nutn?? nahr??t jeho fotku.');

    $photoUpload //limit pro velikost nahr??van??ho souboru
      ->addRule(Form::MAX_FILE_SIZE, 'Nahran?? soubor je p????li?? velk??', 1000000);

    $photoUpload //kontrola typu nahran??ho souboru, pokud je nahran??
      ->addCondition(Form::FILLED)
        ->addRule(function(Nette\Forms\Controls\UploadControl $photoUpload){
          $uploadedFile = $photoUpload->value;
          if ($uploadedFile instanceof Nette\Http\FileUpload){
            $extension=strtolower($uploadedFile->getImageFileExtension());
            return in_array($extension,['jpg','jpeg','png']);
          }
          return false;
        },'Je nutn?? nahr??t obr??zek ve form??tu JPEG ??i PNG.');
    #endregion obr??zek

    $this->addSubmit('ok','ulo??it')
      ->onClick[]=function(SubmitButton $button){
        $values=$this->getValues('array');
        if (!empty($values['productId'])){
          try{
            $product=$this->productsFacade->getProduct($values['productId']);
          }catch (\Exception $e){
            $this->onFailed('Po??adovan?? produkt nebyl nalezen.');
            return;
          }
        }else{
          $product=new Product();
        }
        $product->assign($values,['title','url','description','available']);
        $product->price=floatval($values['price']);
        $this->productsFacade->saveProduct($product);
        $this->setValues(['productId'=>$product->productId]);

        //ulo??en?? fotky
        if (($values['photo'] instanceof Nette\Http\FileUpload) && ($values['photo']->isOk())){
          try{
            $this->productsFacade->saveProductPhoto($values['photo'], $product);
          }catch (\Exception $e){
            $this->onFailed('Produkt byl ulo??en, ale nepoda??ilo se ulo??it jeho fotku.');
          }
        }

        // ulozeni kategorie
        if (!empty($values['categoryId'])) {
            try{
               $category = $this->categoriesFacade->getCategory($values['categoryId']);
               if (!$category) {
                   throw new \Exception("Not found");
               }
               $product->category = $category;
               $this->productsFacade->saveProduct($product);
            }catch (\Exception $e){
                $this->onFailed('Nepodarilo se ziskat kategorii.');
            }
        }

        // ulozeni valikosti
        if (!empty($values['wheelSizeId'])) {
            try{
                $wheelSize = $this->wheelSizesFacade->getWheelSize($values['wheelSizeId']);
                if (!$wheelSize) {
                    throw new \Exception("Not found");
                }
                $product->wheelSize = $wheelSize;
                $this->productsFacade->saveProduct($product);
            }catch (\Exception $e){
                $this->onFailed('Nepodarilo se ziskat velikost.');
            }
        }

        // ulozeni barvy
        if (!empty($values['wheelColorId'])) {
            try{
                $wheelColor = $this->wheelColorsFacade->getWheelColor($values['wheelColorId']);
                if (!$wheelColor) {
                    throw new \Exception("Not found");
                }
                $product->wheelColor = $wheelColor;
                $this->productsFacade->saveProduct($product);
            }catch (\Exception $e){
                $this->onFailed('Nepodarilo se ziskat barvu.');
            }
        }

        $this->onFinished('Produkt byl ulo??en.');
      };
    $this->addSubmit('storno','zru??it')
      ->setValidationScope([$productId])
      ->onClick[]=function(SubmitButton $button){
        $this->onCancel();
      };
  }

  /**
   * Metoda pro nastaven?? v??choz??ch hodnot formul????e
   * @param Product|array|object $values
   * @param bool $erase = false
   * @return $this
   */
  public function setDefaults($values, bool $erase = false):self {
    if ($values instanceof Product){
      $values = [
        'productId'=>$values->productId,
        'categoryId'=>$values->category?$values->category->categoryId:null,
        'wheelSizeId'=>$values->wheelSize?$values-> wheelSize->wheelSizeId:null,
        'wheelColorId'=>$values->wheelColor?$values->wheelColor->wheelColorId:null,
        'title'=>$values->title,
        'url'=>$values->url,
        'description'=>$values->description,
        'price'=>$values->price
      ];
    }
    parent::setDefaults($values, $erase);
    return $this;
  }

}
