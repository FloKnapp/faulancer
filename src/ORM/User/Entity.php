<?php
/**
 * Class Entity | Entity
 * @package Faulancer\ORM\Entity
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\ORM\User;

use Faulancer\ORM\Entity as UserEntity;

/**
 * Class Entity
 *
 * @property int    $id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $login
 * @property string $password
 * @property Role[] $roles
 */
class Entity extends UserEntity
{

    protected static $relations = [
        'roles' => [Role::class, ['id' => 'user_id'], 'users', 'userrole']
    ];

    protected static $tableName = 'user';

}