<?php

namespace App\FrontModule\Components\ReviewForm;

/**
 * Interface ReviewFormFactory
 * @package App\FrontModule\Components\ReviewForm
 */
interface ReviewFormFactory{

    public function create():ReviewForm;

}
