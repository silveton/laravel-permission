<?php
namespace Silveton\Acl\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Silveton\Acl\Exceptions\RoleAlreadyExists;
use Silveton\Acl\Contracts\Role as RoleContract;
use Silveton\Acl\Contracts\Permission as PermissionContract;
use Silveton\Acl\Models\Permission;
use Silveton\Acl\Models\RolePermission;
use Illuminate\Support\Str;

class Role extends Model implements RoleContract {

    protected $primaryKey = 'ROLE_CODE';

    public $timestamps = false;

    /**
     * Set table name 
    */
    public function getTable()
    {
        return config('acl.table_names.role', parent::getTable());
    }

    const ROLE_ACTIVE_S ="Y";
    const ROLE_ACTIVE_N ="N";

    public static function getlabelsActive()
    {
        return \config('acl.active_label');
    }

    public function getLabelActive()
    {
        return static::getlabelsActive()[$this->ROLE_ACTIVE];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ROLE_NAME',
        'ROLE_SLUG',
        'ROLE_DESCRIPTION',
        'ROLE_ACTIVE',
        'ROLE_PERMISSION_CODE',
    ];

    /**
     * @return \Silveton\Acl\Contracts\Role
     * @throws \Silveton\Acl\Exceptions\RoleAlreadyExists
    */
    public static function createRole(string $name, string $slug = null, string $description, string $rolePermissionCode = null)
    {
        if(static::findByName($name,$rolePermissionCode))
        {
            throw RoleAlreadyExists::name($name);
        }

        if( is_null($slug) )
        {
            $slug = Str::slug($name);
        }

        if(static::findBySlug($slug,$rolePermissionCode))
        {
            throw RoleAlreadyExists::slug($slug);
        }

        return static::create([
            'ROLE_NAME'=>$name,
            'ROLE_SLUG'=>$slug,
            'ROLE_DESCRIPTION'=>$description,
            'ROLE_ACTIVE'=>config('acl.role_active_default'),
            'ROLE_PERMISSION_CODE'=>$rolePermissionCode
        ]);
    }

    /**
     * @return bool 
    */
    public function updateRole()
    {   
        if(static::findByNameNotByRoleCode($this->ROLE_CODE,$this->ROLE_NAME,$this->ROLE_PERMISSION_CODE))
        {
            throw RoleAlreadyExists::name($this->ROLE_NAME);
        }

        if(static::findBySlugNotByRoleCode($this->ROLE_CODE,$this->ROLE_SLUG,$this->ROLE_PERMISSION_CODE))
        {
            throw RoleAlreadyExists::slug($this->ROLE_SLUG);
        }

        return $this->save();
    }

    /**
     * @return \Silveton\Acl\Contracts\Role
    */
    public static function findByName(string $name, string $rolePermissionCode = null)
    {
        
        $object =  static::where('ROLE_NAME','=',$name);

        if( ! is_null($rolePermissionCode) )
        {
            $object = $object->where('ROLE_PERMISSION_CODE','=',$rolePermissionCode);
        }
        else
        {
            $object->whereNull('ROLE_PERMISSION_CODE');
        }
        
        return $object->first();
    }

    /**
     * @return \Silveton\Acl\Contracts\Role
    */
    public static function findByNameNotByRoleCode(string $roleCode, string $name, string $rolePermissionCode = null)
    {
        $object =  static::where('ROLE_NAME','=',$name)->whereNotIn('ROLE_CODE',[$roleCode]);

        if( ! is_null($rolePermissionCode) )
        {
            $object = $object->where('ROLE_PERMISSION_CODE','=',$rolePermissionCode);
        }
        else
        {
            $object->whereNull('ROLE_PERMISSION_CODE');
        }
        
        return $object->first();
    }

    /**
     * @return \Silveton\Acl\Contracts\Role
    */
    public static function findBySlug(string $slug,  string $rolePermissionCode = null)
    {
        $object =  static::where('ROLE_SLUG','=',$slug);

        if( ! is_null($rolePermissionCode) )
        {
            $object = $object->where('ROLE_PERMISSION_CODE','=',$rolePermissionCode);
        }
        else
        {
            $object = $object->whereNull('ROLE_PERMISSION_CODE');
        }

        return $object->first();
    }

    /**
     * @return \Silveton\Acl\Contracts\Role
    */
    public static function findBySlugNotByRoleCode(string $roleCode, string $slug,  string $rolePermissionCode = null)
    {
        $object =  static::where('ROLE_SLUG','=',$slug)->whereNotIn('ROLE_CODE',[$roleCode]);

        if( ! is_null($rolePermissionCode) )
        {
            $object = $object->where('ROLE_PERMISSION_CODE','=',$rolePermissionCode);
        }
        else
        {
            $object = $object->whereNull('ROLE_PERMISSION_CODE');
        }

        return $object->first();
    }

    /**
     * @return bool
    */
    public function isActive():bool
    {
        return $this->ROLE_ACTIVE == Role::ROLE_ACTIVE_S;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function permission():BelongsTo
    {
        return $this->belongsTo(Permission::class,'ROLE_PERMISSION_CODE','PERMISSION_CODE');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function permissions():BelongsToMany
    {
        return $this->belongsToMany(Permission::class,config('acl.table_names.role-permission'),'ROLPER_ROLE_CODE','ROLPER_PERMISSION_CODE')->withPivot('ROLPER_CODE','ROLPER_PERMISSION_CODE','ROLPER_ROLE_CODE');
    }

    /**
     * @return  Silveton\Acl\Contracts\PermissionContract 
    */
    public function addPermission(PermissionContract $permission)
    {
        return RolePermission::create(['ROLPER_ROLE_CODE'=>$this->ROLE_CODE,'ROLPER_PERMISSION_CODE'=>$permission->PERMISSION_CODE]);
    }

    /**
     * @return  Silveton\Acl\Contracts\PermissionContract 
    */
    public function removePermission(PermissionContract $permission)
    {
        if( $rolePermission = RolePermission::where('ROLPER_ROLE_CODE','=',$this->ROLE_CODE)->where('ROLPER_PERMISSION_CODE','=',$permission->PERMISSION_CODE)->first())
        {
            return $rolePermission->delete();
        }
        return true;
    }

    /**
     * @return array 
    */
    public static function rolesRoot($roleActive = null)
    {
        $query = static::whereNull('ROLE_PERMISSION_CODE');

        if( ! is_null($roleActive))
        {
            return $query->where('ROLE_ACTIVE','=',$roleActive)->get();
        }
        
        return $query->get();
    }
}