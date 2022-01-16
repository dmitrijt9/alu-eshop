<?php

namespace App\FrontModule\Components\ReviewForm;

use Nette;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;
use Nette\SmartObject;
use Nextras\FormsRendering\Renderers\Bs4FormRenderer;
use Nextras\FormsRendering\Renderers\FormLayout;

/**
 * Class ReviewForm
 * @package App\FrontModule\Components\ReviewForm
 *
 * @method onFinished()
 * @method onCancel()
 */
class ReviewForm extends Form{

    use SmartObject;

    /** @var callable[] $onFinished */
    public $onFinished = [];
    /** @var callable[] $onCancel */
    public $onCancel = [];

    /**
     * UserRegistrationForm constructor.
     * @param Nette\ComponentModel\IContainer|null $parent
     * @param string|null $name
     */
    public function __construct(Nette\ComponentModel\IContainer $parent = null, string $name = null){
        parent::__construct($parent, $name);
        $this->setRenderer(new Bs4FormRenderer(FormLayout::VERTICAL));
        $this->createSubcomponents();
    }

    private function createSubcomponents(){
        $this->addRadioList(
            'stars',
            'Hodnocení',
            [ '0' => 0, '1' => 1, '2' => 2, '3'=>3, '4'=>4, '5'=>5 ]
        );
        $this->addTextArea('text');
        $this->addHidden('productId');
        $this->addSubmit('publish','uložit');
    }

}
