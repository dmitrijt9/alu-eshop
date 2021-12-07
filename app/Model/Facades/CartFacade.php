<?php

namespace App\Model\Facades;

use App\Model\Entities\Cart;
use App\Model\Entities\CartItem;
use App\Model\Entities\User;
use App\Model\Repositories\CartItemRepository;
use App\Model\Repositories\CartRepository;

/**
 * Class CartFacade
 * @package App\Model\Facades
 */
class CartFacade{
  /** @var CartRepository $cartRepository */
  private /*CartRepository*/ $cartRepository;

  public function __construct(CartRepository $cartRepository, CartItemRepository $cartItemRepository){
    $this->cartRepository=$cartRepository;
    $this->cartItemRepository=$cartItemRepository;
  }

  public function getCartById(int $id): Cart {
      return $this->cartRepository->find($id);
  }

  public function getCartByUser($user): Cart {
      if ($user instanceof User) {
          $user = $user->userId;
      }
      $this->cartRepository->findBy(['user_id'=>$user]);
  }

  public function deleteCartByUser($user) {
      if ($user instanceof User) {
          $user = $user->userId;
      }
      try {
          $this->cartRepository->delete($this->getCartByUser($user));
      } catch (\Exception $e) {

      }
  }

  /**
   * Metoda pro uložení kosiku
   * @param Cart &$cart
   * @return bool - true, pokud byly v DB provedeny nějaké změny
   */
  public function saveCart(Cart &$cart):bool {
      $cart->lastModified = new \DateTime();
    return (bool)$this->cartRepository->persist($cart);
  }

  /**
   * Metoda pro uložení polozky v kosiku
   * @param CartItem &$cartItem
   * @return bool - true, pokud byly v DB provedeny nějaké změny
   */
  public function saveCartItem(CartItem &$cartItem):bool {
    return (bool)$this->cartItemRepository->persist($cartItem);
  }
}