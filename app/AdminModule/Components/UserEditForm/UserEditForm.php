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
        $this->addText('name','Jméno a příjmení:')
            ->setRequired('Zadejte své jméno')
            ->setHtmlAttribute('maxlength',40)
            ->addRule(Form::MAX_LENGTH,'Jméno je příliš dlouhé, může mít maximálně 40 znaků.',40);
        $this->addEmail('email','E-mail')
            ->setRequired('Zadejte platný email')
            ->addRule(function(Nette\Forms\Controls\TextInput $input){
                try{
                    $this->usersFacade->getUserByEmail($input->value);
                }catch (\Exception $e){
                    //pokud nebyl uživatel nalezen (tj. je vyhozena výjimka), je to z hlediska registrace v pořádku
                    return true;
                }
                return false;
            },'Uživatel s tímto e-mailem je již registrován.');
        $this->addSelect('role', 'Role', ['admin' => 'admin', 'guest'=>'guest']);
        $this->addSubmit('ok','uložit')
            ->onClick[]=function(SubmitButton $button) {
            $values = $this->getValues('array');
            if (!empty($values['userId'])) {
                try {
                    $user = $this->usersFacade->getUser($values['userId']);
                } catch (\Exception $e) {
                    $this->onFailed('Požadovaný uživatel nebyl nalezen.');
                    return;
                }
            } else {
                $this->onFailed('Uživatele lze jen editovat.');
                return;
            }
            $user->assign($values, ['name', 'email', 'role']);
            $this->usersFacade->saveUser($user);
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
                'name'=>$values->name,
                'email'=>$values->email,
                'role'=>$values->role->roleId,
            ];
        }
        parent::setDefaults($values, $erase);
        return $this;
    }

}
