<?php
namespace Silveton\Acl\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Silveton\Acl\Exceptions\PermissionAlreadyExists;
use Silveton\Acl\Contracts\Permission as PermissionContract;
use Silveton\Acl\Models\Role;

class Permission extends Model implements  PermissionContract 
{
 
    protected $primaryKey = 'PERMISSION_CODE';

    public $timestamps = false;

    /**
     * Set table name 
    */
    public function getTable()
    {
        return config('acl.table_names.permission', parent::getTable());
    }

    const PERMISSION_ACTIVE_S ="Y";
    const PERMISSION_ACTIVE_N ="N";

    public static function getLabelsActive()
    {
        return \config('acl.active_label');
    }

    public function getLabelActive()
    {
        return static::getLabelsActive()[$this->PERMISSION_ACTIVE];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PERMISSION_NAME',
        'PERMISSION_SLUG',
        'PERMISSION_ABILITY',
        'PERMISSION_NOTE',
        'PERMISSION_ACTIVE',
        'PERMISSION_DESCRIPTION',
        'PERMISSION_PERMISSION_CODE',
    ];

    public static function createPermission(string $name, string $slug = null,string $ability = null, string $note = null, string $description = null, string $permissionPermissionCode = null)
    {   

        if(static::findByName($name,$permissionPermissionCode))
        {
            throw PermissionAlreadyExists::name($name);
        }

        $slug = empty($slug) ? Str::slug($name) : $slug;

        if(static::findBySlug($slug,$permissionPermissionCode))
        {
            throw PermissionAlreadyExists::slug($slug);
        }

        if( is_null($ability))
        {
            $ability =  $slug;

            if( ! is_null( $permissionPermissionCode ))
            {
                $ability = static::find($permissionPermissionCode)->PERMISSION_ABILITY.config('acl.permission_ability_separator').$ability;
            }

        }

        if( ! is_null($ability) )
        {
            if(static::findByAbility($ability))
            {
                throw PermissionAlreadyExists::ability($name);
            }
        }

        return static::create([
            'PERMISSION_NAME'=>$name,
            'PERMISSION_SLUG'=>$slug,
            'PERMISSION_ABILITY'=>$ability,
            'PERMISSION_NOTE'=>$note,
            'PERMISSION_ACTIVE'=>config('acl.permission_active_default'),
            'PERMISSION_DESCRIPTION'=>$description,
            'PERMISSION_PERMISSION_CODE'=>$permissionPermissionCode
        ]);
    }

    /**
     * 
     * @return bool 
    */
    public function updatePermission()
    {
        if(static::findByNameNotByPermissionCode($this->PERMISSION_CODE,$this->PERMISSION_NAME,$this->PERMISSION_PERMISSION_CODE))
        {
            throw PermissionAlreadyExists::name($this->PERMISSION_NAME);
        }

        if(static::findBySlugNotByPermissionCode($this->PERMISSION_CODE,$this->PERMISSION_SLUG,$this->PERMISSION_PERMISSION_CODE))
        {
            throw PermissionAlreadyExists::slug($this->PERMISSION_SLUG);
        }

        if(static::findByAbilityNotByPermissionCode($this->PERMISSION_CODE,$this->PERMISSION_ABILITY))
        {
            throw PermissionAlreadyExists::ability($this->PERMISSION_ABILITY);
        }

        return $this->save();
    }

    /**
     * @return \Silveton\Acl\Contracts\Permission
    */
    public static function findByName(string $name, string $permissionPermissionCode = null)
    {
        $object =  static::where('PERMISSION_NAME','=',$name);

        if( ! is_null($permissionPermissionCode) )
        {
            $object = $object->where('PERMISSION_PERMISSION_CODE','=',$permissionPermissionCode);
        }
        else
        {
            $object = $object->whereNull('PERMISSION_PERMISSION_CODE');
        }
        
        return $object->first();
    }

    /**
     * @return \Silveton\Acl\Contracts\Permission
    */
    public static function findByNameNotByPermissionCode(string $permissionCode, string $name, string $permissionPermissionCode = null)
    {
        $object =  static::where('PERMISSION_NAME','=',$name)->whereNotIn('PERMISSION_CODE',[$permissionCode]);
        
        if( ! is_null($permissionPermissionCode) )
        {
            $object = $object->where('PERMISSION_PERMISSION_CODE','=',$permissionPermissionCode);
        }
        else
        {
            $object = $object->whereNull('PERMISSION_PERMISSION_CODE');
        }
        
        return $object->first();
    }

    /**
     * @return \Silveton\Acl\Contracts\Permission
    */
    public static function findBySlug(string $slug,  string $permissionPermissionCode = null)
    {
        $object =  static::where('PERMISSION_SLUG','=',$slug);

        if( ! is_null($permissionPermissionCode) )
        {
            $object = $object->where('PERMISSION_PERMISSION_CODE','=',$permissionPermissionCode);
        }
        else
        {
            $object = $object->whereNull('PERMISSION_PERMISSION_CODE');
        }

        return $object->first();
    }

    /**
     * @return \Silveton\Acl\Contracts\Permission
    */
    public static function findBySlugNotByPermissionCode(string $permissionCode, string $slug,  string $permissionPermissionCode = null)
    {
        $object =  static::where('PERMISSION_SLUG','=',$slug)->whereNotIn('PERMISSION_CODE',[$permissionCode]);

        if( ! is_null($permissionPermissionCode) )
        {
            $object = $object->where('PERMISSION_PERMISSION_CODE','=',$permissionPermissionCode);
        }
        else
        {
            $object = $object->whereNull('PERMISSION_PERMISSION_CODE');
        }

        return $object->first();
    }

    /**
     * @return \Silveton\Acl\Contracts\Permission
    */
    public static function findByAbility($ability)
    {
        return static::where('PERMISSION_ABILITY','=',$ability)->first();
    }

    /**
     * @return \Silveton\Acl\Contracts\Permission
    */
    public static function findByAbilityNotByPermissionCode(string $permissionCode, string $ability)
    {
        return static::where('PERMISSION_ABILITY','=',$ability)->whereNotIn('PERMISSION_CODE',[$permissionCode])->first();
    }

    /**
     * @return bool 
    */
    public function isActive():bool
    {
        return $this->PERMISSION_ACTIVE == Permission::PERMISSION_ACTIVE_S;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function roles():HasMany
    {
        return $this->hasMany(Role::class,'ROLE_PERMISSION_CODE','PERMISSION_CODE');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function permission():BelongsTo
    {
        return $this->belongsTo(Permission::class,'PERMISSION_PERMISSION_CODE','PERMISSION_CODE');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function permissions():HasMany
    {
        return $this->hasMany(Permission::class,'PERMISSION_PERMISSION_CODE','PERMISSION_CODE');
    }

    public static function permissionsRoot($permissionActive = null )
    {
        $query = static::whereNull('PERMISSION_PERMISSION_CODE');

        if( ! is_null($permissionActive))
        {
            return $query->where('PERMISSION_ACTIVE','=',$permissionActive)->get();
        }
        
        return $query->get();
    }

}