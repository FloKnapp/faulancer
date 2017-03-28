<?php
/**
 * Class RoleEntity | RoleEntity.php
 * @package Faulancer\User
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\ORM\User;

use Faulancer\ORM\Entity as BaseEntity;

/**
 * Class RoleEntity
 *
 * @property string $roleName
 */
class RoleEntity extends BaseEntity
{

    protected static $relations = [
        'users' => [Entity::class, ['id' => 'role_id'], 'roles', 'userrole']
    ];

    protected static $tableName = 'role';

}