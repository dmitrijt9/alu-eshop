<?php

namespace App\FrontModule\Components\OrderForm;

use App\AdminModule\Components\CategoryEditForm\CategoryEditForm;
use App\Model\Entities\Category;
use App\Model\Entities\User;
use Nette;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;
use Nette\SmartObject;
use Nextras\FormsRendering\Renderers\Bs5FormRenderer;
use Nextras\FormsRendering\Renderers\FormLayout;

/**
 * Class OrderForm
 * @package App\FrontModule\Components\OrderForm
 *
 * @method onFinished()
 * @method onCancel()
 */
class OrderForm extends Form{

    use SmartObject;

    /** @var callable[] $onFinished */
    public $onFinished = [];
    /** @var callable[] $onCancel */
    public $onCancel = [];

    /**
     * OrderForm constructor.
     * @param Nette\ComponentModel\IContainer|null $parent
     * @param string|null $name
     */
    public function __construct(Nette\ComponentModel\IContainer $parent = null, string $name = null){
        parent::__construct($parent, $name);
        $this->setRenderer(new Bs5FormRenderer(FormLayout::VERTICAL));
        $this->createSubcomponents();
    }

    private function createSubcomponents(){
        $this->addEmail('userEmail', "Váš e-mail")->setRequired('Zadejte platný email');
        $this->addText('userName', "Vaše jméno a příjmení")
            ->setRequired('Zadejte své jméno')
            ->setHtmlAttribute('maxlength',40)
            ->addRule(Form::MAX_LENGTH,'Jméno je příliš dlouhé, může mít maximálně 40 znaků.',40);
        $this->addText('userAddress', "Vaše adresa")->setRequired('Zadejte platnou adresu');
        $this->addSubmit('sendOrder','Odeslat objednávku')
            ->onClick[]=function(SubmitButton $button){
            $this->onFinished();
        };

        $this->addSubmit('cancel','zrušit')
            ->setValidationScope([])
            ->onClick[]=function(SubmitButton $button){
            $this->onCancel();
        };
    }
    /**
     * Metoda pro nastavení výchozích hodnot formuláře
     * @param User|array|object $values
     * @param bool $erase = false
     * @return $this
     */
    public function setDefaults($values, bool $erase = false):self {
        if ($values instanceof User){
            $values = [
                'userEmail'=>$values->email,
                'userName'=>$values->name
            ];
        }
        parent::setDefaults($values, $erase);
        return $this;
    }


}
