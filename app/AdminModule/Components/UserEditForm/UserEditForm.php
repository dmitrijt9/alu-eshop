<?php

namespace App\AdminModule\Components\UserEditForm;

use App\Model\Entities\User;
use App\Model\Facades\CategoriesFacade;
use App\Model\Facades\UsersFacade;
use Nette;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;
use Nette\SmartObject;
use Nextras\FormsRendering\Renderers\Bs4FormRenderer;
use Nextras\FormsRendering\Renderers\FormLayout;

/**
 * Class UserEditForm
 * @package App\AdminModule\Components\UserEditForm
 *
 * @method onFinished(string $message = '')
 * @method onFailed(string $message = '')
 * @method onCancel()
 */
class UserEditForm extends Form{

    use SmartObject;

    /** @var callable[] $onFinished */
    public $onFinished = [];
    /** @var callable[] $onFailed */
    public $onFailed = [];
    /** @var callable[] $onCancel */
    public $onCancel = [];
    /** @var CategoriesFacade */
    private $categoriesFacade;
    /** @var UsersFacade $usersFacade */
    private $usersFacade;

    /**
     * TagEditForm constructor.
     * @param Nette\ComponentModel\IContainer|null $parent
     * @param string|null $name
     * @param UsersFacade $usersFacade
     * @noinspection PhpOptionalBeforeRequiredParametersInspection
     */
    public function __construct(Nette\ComponentModel\IContainer $parent = null, string $name = null, CategoriesFacade $categoriesFacade, UsersFacade $usersFacade){
        parent::__construct($parent, $name);
        $this->setRenderer(new Bs4FormRenderer(FormLayout::VERTICAL));
        $this->categoriesFacade=$categoriesFacade;
        $this->usersFacade=$usersFacade;
        $this->createSubcomponents();
    }

    private function createSubcomponents(){
    }

    /**
     * Metoda pro nastavení výchozích hodnot formuláře
     * @param User|array|object $values
     * @param bool $erase = false
     * @return $this
     */
    public function setDefaults($values, bool $erase = false):self {
    }

}
