<?php

namespace App\FrontModule\Components\CartControl;

use App\Model\Entities\Cart;
use App\Model\Facades\CartFacade;
use App\Model\Facades\UsersFacade;
use Nette\Application\UI\Control;
use Nette\Application\UI\Template;
use Nette\Http\Session;
use Nette\Security\User;

/**
 * Class CartControl
 * @package App\FrontModule\Components\CartControl
 */
class CartControl extends Control{
  /** @var User $user */
  private $user;

    /** @var CartFacade $cartFacade */
    private $cartFacade;

    /** @var UsersFacade $usersFacade */
    private $usersFacade;

    /** @var Cart $cart */
    private $cart;

    /**
     * UserLoginControl constructor.
     * @param User $user
     * @param Session $session
     * @param CartFacade $cartFacade
     */
    public function __construct(User $user, Session $session, CartFacade $cartFacade, UsersFacade $usersFacade){
        $this->user=$user;
        $this->cartFacade = $cartFacade;
        $this->usersFacade = $usersFacade;
        $cartSession=$session->getSection('cart');

        $cartId = $cartSession->get('cartId');

        try {
            $this->cart=$this->cartFacade->getCartById($cartId);

            if ($user->isLoggedIn() && $this->cart->user->userId != $user->id) {
                if ($this->cart->getTotalCount() > 0) {
                    $this->cartFacade->deleteCartByUser($user->id);
                    $this->cart->user = $this->usersFacade->getUser($user->id);
                    $this->cartFacade->saveCart($this->cart);
                }
            }
        } catch (\Exception $e) {
            if($user->isLoggedIn()) {
                // @todo
            }
        }

    }

  /**
   * Akce renderující šablonu s odkazem pro zobrazení harmonogramu na desktopu
   * @param array $params = []
   */
  public function render($params=[]):void {
    $template=$this->prepareTemplate('default');
    $template->render();
  }

  /**
   * Metoda vytvářející šablonu komponenty
   * @param string $templateName=''
   * @return Template
   */
  private function prepareTemplate(string $templateName=''):Template{
    $template=$this->template;
    if (!empty($templateName)){
      $template->setFile(__DIR__.'/templates/'.$templateName.'.latte');
    }
    return $template;
  }

}