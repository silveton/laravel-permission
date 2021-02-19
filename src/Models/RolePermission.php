<?php
namespace Silveton\Acl\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Silveton\Acl\Models\Permission;

class RolePermission extends Model {

    protected $primaryKey = 'ROLPER_CODE';

    public $timestamps = false;

    /**
     * Set table name 
    */
    public function getTable()
    {
        return config('acl.table_names.role-permission', parent::getTable());
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ROLPER_PERMISSION_CODE',
        'ROLPER_ROLE_CODE'
    ];

    public function permission()
    {
        return $this->belongsTo(Permission::class,'ROLPER_PERMISSION_CODE','PERMISSION_CODE');
    }
    
}