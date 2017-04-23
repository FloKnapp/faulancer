<?php
/**
 * Class Entity | Entity.php
 * @package Faulancer\ORM\UserEntity
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\ORM\User;

use Faulancer\ORM\Entity as BaseEntity;

/**
 * Class UserEntity
 *
 * @property int    $id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $login
 * @property string $password
 * @property RoleEntity[] $roles
 */
class Entity extends BaseEntity
{

    protected static $relations = [
        'roles' => [RoleEntity::class, ['id' => 'user_id'], 'users', 'userrole']
    ];

    protected static $tableName = 'user';

}