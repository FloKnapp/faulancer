<?php
/**
 * Class Role | Role* @package Faulancer\Entity
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\ORM\User;

use Faulancer\ORM\Entity as BaseEntity;

/**
 * Class Role
 *
 * @property string $roleName
 */
class Role extends BaseEntity
{

    protected static $relations = [
        'users' => [Entity::class, ['id' => 'role_id'], 'roles', 'userrole']
    ];

    protected static $tableName = 'role';

}