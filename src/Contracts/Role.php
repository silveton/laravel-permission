<?php
namespace Silveton\Acl\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Silveton\Acl\Contracts\Permission as PermissionContract;

interface Role {

    /**
     * @return \Silveton\Acl\Contracts\Role
     * @throws \Silveton\Acl\Exceptions\RoleAlreadyExists
    */
    public static function createRole(string $name, string $slug = null, string $description, string $rolePermissionCode = null);

    /**
     * @return bool 
    */
    public function updateRole();

    /**
     * @return \Silveton\Acl\Contracts\Role
    */
    public static function findByName(string $name, string $rolePermissionCode = null);

    /**
     * @return \Silveton\Acl\Contracts\Role
    */
    public static function findByNameNotByRoleCode(string $roleCode, string $name, string $rolePermissionCode = null);

    /**
     * @return \Silveton\Acl\Contracts\Role
    */
    public static function findBySlug(string $slug,  string $rolePermissionCode = null);

    /**
     * @return \Silveton\Acl\Contracts\Role
    */
    public static function findBySlugNotByRoleCode(string $roleCode, string $slug,  string $rolePermissionCode = null);

    /**
     * @return bool
    */
    public function isActive():bool;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function permission():BelongsTo;
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function permissions():BelongsToMany;

    /**
     * @return  Silveton\Acl\Contracts\PermissionContract  
    */
    public function addPermission(PermissionContract $permission);

    /**
     * @return  Silveton\Acl\Contracts\PermissionContract 
    */
    public function removePermission(PermissionContract $permission);

    /**
     * @return array 
    */
    public static function rolesRoot($roleActive = null);
}