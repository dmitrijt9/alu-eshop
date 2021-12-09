<?php

namespace App\AdminModule\Components\UserEditForm;

/**
 * Interface UserEditFormFactory
 * @package App\AdminModule\Components\UserEditForm
 */
interface UserEditFormFactory{

    public function create():UserEditForm;

}
