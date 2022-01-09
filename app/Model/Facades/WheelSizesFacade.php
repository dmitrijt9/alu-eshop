<?php

namespace App\Model\Facades;

use App\Model\Entities\WheelSize;
use App\Model\Repositories\WheelSizeRepository;

/**
 * Class WheelSizesFacade - fasáda pro využívání velikosti kol z presenterů
 * @package App\Model\Facades
 */
class WheelSizesFacade{
  /** @var WheelSizeRepository $wheelSizeRepository */
  private /*WheelSizeRepository*/ $wheelSizeRepository;

  public function __construct(WheelSizeRepository $wheelSizeRepository){
    $this->wheelSizeRepository=$wheelSizeRepository;
  }

  /**
   * Metoda pro načtení jedné velikosti
   * @param int $id
   * @return WheelSize
   * @throws \Exception
   */
  public function getWheelSize(int $id):WheelSize {
    return $this->wheelSizeRepository->find($id); //buď počítáme s možností vyhození výjimky, nebo ji ošetříme už tady a můžeme vracet např. null
  }

  /**
   * Metoda pro vyhledání velikosti kol
   * @param array|null $params = null
   * @param int $offset = null
   * @param int $limit = null
   * @return WheelSize[]
   */
  public function findWheelSizes(array $params=null,int $offset=null,int $limit=null):array {
    return $this->wheelSizeRepository->findAllBy($params,$offset,$limit);
  }

  /**
   * Metoda pro zjištění počtu velikosti kol
   * @param array|null $params
   * @return int
   */
  public function findWheelSizesCount(array $params=null):int {
    return $this->wheelSizeRepository->findCountBy($params);
  }

  /**
   * Metoda pro uložení velikosti
   * @param WheelSize &$wheelSize
   * @return bool - true, pokud byly v DB provedeny nějaké změny
   */
  public function saveWheelSize(WheelSize &$wheelSize):bool {
    return (bool)$this->wheelSizeRepository->persist($wheelSize);
  }

  /**
   * Metoda pro smazání velikosti
   * @param WheelSize $wheelSize
   * @return bool
   */
  public function deleteWheelSize(WheelSize $wheelSize):bool {
    try{
      return (bool)$this->wheelSizeRepository->delete($wheelSize);
    }catch (\Exception $e){
      return false;
    }
  }

}