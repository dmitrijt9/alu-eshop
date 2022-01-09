<?php

namespace App\Model\Facades;

use App\Model\Entities\WheelColor;
use App\Model\Repositories\WheelColorRepository;

/**
 * Class WheelColorsFacade - fasáda pro využívání barvy kol z presenterů
 * @package App\Model\Facades
 */
class WheelColorsFacade{
  /** @var WheelColorRepository $wheelColorRepository */
  private /*WheelColorRepository*/ $wheelColorRepository;

  public function __construct(WheelColorRepository $wheelColorRepository){
    $this->wheelColorRepository=$wheelColorRepository;
  }

  /**
   * Metoda pro načtení jedné barvy
   * @param int $id
   * @return WheelColor
   * @throws \Exception
   */
  public function getWheelColor(int $id):WheelColor {
    return $this->wheelColorRepository->find($id); //buď počítáme s možností vyhození výjimky, nebo ji ošetříme už tady a můžeme vracet např. null
  }

  /**
   * Metoda pro vyhledání barvy kol
   * @param array|null $params = null
   * @param int $offset = null
   * @param int $limit = null
   * @return WheelColor[]
   */
  public function findWheelColors(array $params=null,int $offset=null,int $limit=null):array {
    return $this->wheelColorRepository->findAllBy($params,$offset,$limit);
  }

  /**
   * Metoda pro zjištění počtu barvy kol
   * @param array|null $params
   * @return int
   */
  public function findWheelColorsCount(array $params=null):int {
    return $this->wheelColorRepository->findCountBy($params);
  }

  /**
   * Metoda pro uložení barvy
   * @param WheelColor &$wheelColor
   * @return bool - true, pokud byly v DB provedeny nějaké změny
   */
  public function saveWheelColor(WheelColor &$wheelColor):bool {
    return (bool)$this->wheelColorRepository->persist($wheelColor);
  }

  /**
   * Metoda pro smazání barvy
   * @param WheelColor $wheelColor
   * @return bool
   */
  public function deleteWheelColor(WheelColor $wheelColor):bool {
    try{
      return (bool)$this->wheelColorRepository->delete($wheelColor);
    }catch (\Exception $e){
      return false;
    }
  }

}