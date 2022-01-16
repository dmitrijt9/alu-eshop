<?php

namespace App\FrontModule\Presenters;

use App\FrontModule\Components\ProductCartForm\ProductCartFormFactory;
use App\Model\Facades\ProductsFacade;
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
    }catch (\Exception $e){
      throw new BadRequestException('Produkt nebyl nalezen.');
    }

    $this->template->product = $product;
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
  #endregion injections
}
