<?php

namespace Faulancer\Fixture\Entity;

use Faulancer\ORM\User\Entity;

class UserEntity extends Entity {

    public $id = 1;
    public $firstname = 'Test';
    public $lastname = 'Test';
    public $roles = [];

}