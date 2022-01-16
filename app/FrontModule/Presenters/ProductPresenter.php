<?php

namespace App\FrontModule\Presenters;

use App\FrontModule\Components\ProductCartForm\ProductCartFormFactory;
use App\FrontModule\Components\ReviewForm\ReviewForm;
use App\FrontModule\Components\ReviewForm\ReviewFormFactory;
use App\Model\Entities\ProductReview;
use App\FrontModule\Components\ProductCartForm\ProductCartForm;
use App\FrontModule\Components\CartControl\CartControl;
use App\Model\Facades\ProductsFacade;
use App\Model\Facades\UsersFacade;
use App\Model\Facades\WheelSizesFacade;
use App\Model\Facades\CategoriesFacade;
use Nette\Application\BadRequestException;
use Nette\Utils\Paginator;

/**
 * Class ProductPresenter
 * @package App\FrontModule\Presenters
 * @property string $category
 */
class ProductPresenter extends BasePresenter{
  /** @var ProductsFacade $productsFacade */
  private $productsFacade;
  /** @var ProductCartFormFactory $productCartFormFactory */
  private $productCartFormFactory;
  /** @var WheelSizesFacade $wheelSizesFacade */
  private $wheelSizesFacade;
  /** @var CategoriesFacade $categoriesFacade */
  private $categoriesFacade;
  /** @var ReviewFormFactory $reviewFormFactory */
  private $reviewFormFactory;
    /** @var UsersFacade $usersFacade */
    private $usersFacade;

  /** @persistent */
  public $category;

  /**
   * Akce pro zobrazení jednoho produktu
   * @param string $url
   * @throws BadRequestException
   */
  public function renderShow(string $url):void {
    try{
      $product = $this->productsFacade->getProductByUrl($url);
      $reviews = $this->productsFacade->getProductReviews($product);
    }catch (\Exception $e){
      throw new BadRequestException('Produkt nebyl nalezen.');
    }

    $this->template->product = $product;
    $this->template->reviews = $reviews;
    $this->template->user = $this->getUser();
    $this->template->canUserReview = $this->getUser()->isLoggedIn() && count(
      array_filter($reviews, function($r) {
          return $r->user->userId == $this->getUser()->getId();
      })
    ) == 0;
  }

  /**
   * Akce pro vykreslení přehledu produktů
   */
  public function renderList(int $page = 1):void {
    $productsCount = $this->productsFacade->findProductsCount();
    $filter = [];

    if($this->getParameter('categoryId') !== null) {
        $category = $this->categoriesFacade->getCategory($this->getParameter('categoryId'));
        $this->template->category = $category;
        $filter['category_id'] = $category->categoryId;
    }

    if($this->getParameter('wheelSize') !== null) {
        $this->template->wheelSize = $this->getParameter('wheelSize');
        $filter['wheel_size_id'] = $this->getParameter('wheelSize');
    }

    $paginator = new Paginator();
    $paginator->setItemCount($productsCount);
    $paginator->setItemsPerPage(10);
    $paginator->setPage($page);


      $this->template->wheelSizes=$this->wheelSizesFacade->findWheelSizes(['order'=>'size']);
    $this->template->products = $this->productsFacade->findProducts(array_merge(['order'=>'title'], $filter), $paginator->getOffset(), $paginator->getLength());
    $this->template->paginator = $paginator;
  }

    public function createComponentReview():ReviewForm {
        $form = $this->reviewFormFactory->create();
        $form->onSubmit[]=function($form) {
            try {
                $values = $form->getValues();
                $product = $this->productsFacade->getProduct($values['productId']);
                $productReview = new ProductReview();
                $productReview->assign($values, ['stars', 'text']);
                $user = $this->usersFacade->getUser($this->getUser()->getId());
                $productReview->product = $product;
                $productReview->user = $user;
                $this->productsFacade->saveProductReview($productReview);
                $this->flashMessage('Hodnocení bylo uloženo.');
            } catch(\Error $e) {
                $this->flashMessage('Hodnocení se nepodařilo uložit.', 'error');
            }
        };
        return $form;
    }

 public function createComponentProductCartForm() {
     $form = $this->productCartFormFactory->create();
     $form->onSubmit[]=function(ProductCartForm $form){
         try{
             $product = $this->productsFacade->getProduct($form->values->productId);
             //kontrola zakoupitelnosti
         }catch (\Exception $e){
             $this->flashMessage('Produkt nejde přidat do košíku','error');
             $this->redirect('this');
         }
         //přidání do košíku
         /** @var CartControl $cart */
         $cart = $this->getComponent('cart');
         $cart->addToCart($product, (int)$form->values->count);
         $this->redirect('this');
     };

     return $form;
 }

  #region injections
  public function injectProductsFacade(ProductsFacade $productsFacade):void {
    $this->productsFacade=$productsFacade;
  }

  public function injectProductCartFormFactory(ProductCartFormFactory $productCartFormFactory):void {
    $this->productCartFormFactory=$productCartFormFactory;
  }

  public function injectWheelSizesFacade(WheelSizesFacade $wheelSizesFacade):void {
    $this->wheelSizesFacade=$wheelSizesFacade;
  }

  public function injectCategoriesFacade(CategoriesFacade $categoriesFacade):void {
    $this->categoriesFacade=$categoriesFacade;
  }

  public function injectReviewFormFactory(ReviewFormFactory $reviewFormFactory):void {
    $this->reviewFormFactory=$reviewFormFactory;
  }

    public function injectUsersFacade(UsersFacade $usersFacade){
        $this->usersFacade=$usersFacade;
    }
  #endregion injections
}
