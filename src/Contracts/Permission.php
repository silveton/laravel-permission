<?php
namespace Silveton\Acl\Contracts;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface Permission
{
    /**
     * @return \Silveton\Acl\Contracts\Permission
     * @throws \Silveton\Acl\Exceptions\PermissionDoesNotExist
    */
    public static function createPermission(string $name, string $slug = null,string $ability = null, string $note = null, string $description = null, string $permissionPermissionCode = null);
    /**
     * 
     * @return bool 
    */
    public function updatePermission();
    
    /**
     * @return \Silveton\Acl\Contracts\Permission
    */
    public static function findByName(string $name, string $permissionPermissionCode = null);

    /**
     * @return \Silveton\Acl\Contracts\Permission
    */
    public static function findByNameNotByPermissionCode(string $permissionCode, string $name, string $permissionPermissionCode = null);

    /**
     * @return \Silveton\Acl\Contracts\Permission
    */
    public static function findBySlug(string $slug,  string $permissionPermissionCode = null);

    /**
     * @return \Silveton\Acl\Contracts\Permission
    */
    public static function findBySlugNotByPermissionCode(string $permissionCode, string $slug,  string $permissionPermissionCode = null);

    /**
     * @return \Silveton\Acl\Contracts\Permission
    */
    public static function findByAbility(string $ability);

    /**
     * @return \Silveton\Acl\Contracts\Permission
    */
    public static function findByAbilityNotByPermissionCode(string $permissionCode, string $ability);

    /**
     * @return bool
    */
    public function isActive():bool;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function roles():HasMany;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function permission():BelongsTo;
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function permissions():HasMany;

    /**
     * @return array 
    */
    public static function permissionsRoot($permissionActive = null );

}