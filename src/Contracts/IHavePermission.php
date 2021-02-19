<?php
namespace Silveton\Acl\Contracts;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface IHavePermission
{
    /**
     * Chave onde estão os roles que estão desntro de uma permissão
     * @example 
     * [
     **     permission-roles => [
     *          'panel-admin' => [
     *             'admin'=> [
     *                  'permissions' =>[
     *                      'panel-admin->module-users->users->create'
     *                   ]
     *              ]
     *          ]
     **      ]
     * ]
     * @return string  
    */
    public function getPermissionRoleKey();

    /**
     * Chave onde estão os roles que não estão vinculados a nenhuma permissão
     * @example 
     * [
     **      roles => [
     *         'support'=> [
     *             'permissions' =>[
     *                 'panel-admin->module-support->tickets->create'
     *            ]
     *         ]
     **      ]
     * ]
     * @return string  
    */
    public function getRoleKey();

    /**
     * Chave onde estão permissões do role
     * @example 
     * [
     *      roles => [
     *         'support'=> [
     **            'permissions' =>[
     **                 'panel-admin->module-support->tickets->create'
     **            ]
     *         ]
     *      ]
     * ] 
     * @return string 
    */
    public function getPermissionKey();

    /**
     * Lugar onde a logica para montar o array com as permissões devem estar
     * Deve retornar um array na seguinte estrutura
     * @example 
     * [
     *    permission-roles => [
     *        'panel-admin' => [
     *           'admin'=> [
     *             'permissions' =>[
     *                'panel-admin->module-users->users->create'
     *             ]
     *           ]
     *        ]
     *     ]
     *     roles => [
     *        'support'=> [
     *           'permissions' =>[
     *                 'panel-admin->module-support->tickets->create'
     *           ]
     *        ]
     *     ]
     * ]
     * @return array  
    */
    public function bootPermissions();

    /**
     * Retorna um array com os roles que estão ligados a uma permissão
     * @example 
     * [
     *   'panel-admin' => [
     *      'admin'=> [
     *        'permissions' =>[
     *           'panel-admin->module-users->users->create'
     *        ]
     *      ]
     *   ]
     * ]
     * @return array  
    */
    public function getRootPermissionRoles();

    /**
     * Retorna um array com os roles que não estão ligados a uma permissão
     * @example 
     * [
     *   'admin'=> [
     *     'permissions' =>[
     *        'panel-admin->module-users->users->create'
     *     ]
     *   ]
     * ]
     * @return array
    */
    public function getRootRoles();

    /**
     * Seleciona todos os role que estão vinculados com uma permissão especifica
     * @example $this->getPermissionRoleInRootPermissionRole('panel-admin)
     * @param string $permissionRole
     * @return array
    */
    public function getPermissionRoleInRootPermissionRole($permissionRole);
    
    /**
     * Seleciona um role o que esta vinculado a uma permissão específica
     * @example $this->getRoleInRootPermissionRole('admin')
     * @param string $role
     * @return array
    */
    public function getRoleInRootPermissionRole($role,$permissionRole);

    /**
     * Seleciona um role
     * @example $this->getRoleInRootRole('admin')
     * @param string $role
     * @return string
    */
    public function getRoleInRootRole($role);

    /**
     * Selecione todas permissões de um de terminado role que está ligado a uma permissão
     * @example $this->getPermissionsByRoleAndPermissionRoleInRootPermissionRole('admin','panel-admin')
     * @param $role
     * @param $permissionRole
     * @return array
    */
    public function getPermissionsByRoleAndPermissionRoleInRootPermissionRole($role,$permissionRole);

    /**
     * Selecione todas permissões de um de terminado role
     * @example $this->getPermissionsByRoleInRootRole('admin')
     * @param $role
     * @return array
    */
    public function getPermissionsByRoleInRootRole($role);

    /**
     * Seleciona todas as permissões 
     * @return array
    */
    public function getAllPermissions();

    /**
     * Seleciona todas as permissões
     * @return array
    */
    public function getAllPermissionsInRootPermissionRole();

    /**
     * Seleciona todas as permissões
     * @param array $bootPermissions
     * @return array
    */
    public function _getAllPermissionsInRootPermissionRole($bootPermissions);

    /**
     * Seleciona todas as permissões 
     * @return array
    */
    public function getAllPermissionsInRootRole();
    
    
    /**
     * Seleciona todas as permissões 
     * @param array $bootPermissions
     * @return array
    */
    public function _getAllPermissionsInRootRole($bootPermissions);

    /**
     * Verifica se possui a permissão que tem roles 
     * @example $this->iHavePermissionRoleInRootPermissionRole('panel-admin')
     * @param string $permissionRole
     * @return bool
    */
    public function iHavePermissionRoleInRootPermissionRole($permissionRole);

    /**
     * Verifica sepossui a permissão que tem roles
     * @example $this->_iHavePermissionRoleInRootPermissionRole('panel-admin',['panel-admin','panel-user'])
     * @param string $permissionRole
     * @param array $permissionRoles
     * @return bool
    */
    public function _iHavePermissionRoleInRootPermissionRole($permissionRole, $permissionRoles);
    
      
    /**
     * Verifica se o role passado existe dentro desta permissão
     * @example $this->iHaveRoleToPermissionInRootPermissionRole('admin','panel-admin')
     * @param string $role
     * @param string $permissionRole
     * @return bool 
    */
    public function iHaveRoleToPermissionInRootPermissionRole($role, $permissionRole);

    /**
     * Verifica se o role passado existe dentro desta permissão
     * @example $this->_iHaveRoleToPermissionInRootPermissionRole('admin','panel-admin',['panel-admin','panel-user'])
     * @param string $role
     * @param string $permissionRole
     * @param array $permissionRoles
     * @return bool 
    */
    public function _iHaveRoleToPermissionInRootPermissionRole($role, $permissionRole, $permissionRoles);

     /**
     * Verifica se o role passado existe nos roles que não stão vinculados a uma permissão
     * @example $this->iHaveRoleInRootRole('admin')
     * @param string $role
     * @return bool 
    */
    public function iHaveRoleInRootRole($role);

    /**
     * Verifica se o role passado existe
     * @example $this->_iHaveRole('admin',['admin','support'])
     * @param string $role
     * @param array $roles
     * @return bool 
    */
    public function _iHaveRole($role,$roles);

    /**
     * Verifica se todos roles passado existe dentro desta permissão
     * $this->iHaveAllRolesToPermissionInRootPermissionRole(['admin','support'],'panel-admin')
     * @param string $permissionRole
     * @param array $roles
     * @return bool 
    */
    public function iHaveAllRolesToPermissionInRootPermissionRole($permissionRole,$roles);

    /**
     * Verifica se todos roles passado existe dentro desta permissão
     * $this->_iHaveAllRolesToPermissionInRootPermissionRole(['admin','support'],'panel-admin')
     * @param string $permissionRole
     * @param array $roles
     * @param array $rolesIn
     * @return bool 
    */
    public function _iHaveAllRolesToPermissionInRootPermissionRole($permissionRole, $roles, $rolesIn);

    /**
     * Verifica se possui todos os roles passados
     * $this->iHaveAllRolesInRootRole(['admin','support'])
     * @param array $role
     * @param bool 
    */
    public function iHaveAllRolesInRootRole($roles);

    /**
     * Verifica se possui todos os roles passados
     * $this->_iHaveAllRolesInRootRole(['admin','support'],['admin','support','developer'])
     * @param array $roles
     * @param array $rolesIn
     * @param bool 
    */
    public function _iHaveAllRolesInRootRole($roles,$rolesIn);

    /**
     * Verifica se algum dos roles passado existe dentro desta permissão
     * @example $this->iHaveAnyRolesInRootPermissionRole('panel-admin',['admin','support','developer']);
     * @param array $roles
     * @param string $permissionRole
     * @return bool 
    */
    public function iHaveAnyRolesInRootPermissionRole($permissionRole, $roles);

    /**
     * Verifica se algum dos roles passado existe dentro desta permissão
     * @example $this->_iHaveAnyRolesInRootPermissionRole('panel-admin',['admin','support','developer'],['admin','support','developer']);
     * @param array $roles
     * @param string $permissionRole
     * @param array $permissionRoles
     * @return bool 
    */
    public function _iHaveAnyRolesInRootPermissionRole($roles, $permissionRole, $permissionRoles);

    
    /**
     * Verifica se existe pelo menos uma das roles passadas
     * @example $this->iHaveAnyRoles(['admin','support'])
     * @param array $roles
     * @param bool 
    */
    public function iHaveAnyRolesInRootRole($roles);

    /**
     * Verifica se existe pelo menos uma das roles passadas
     * @example $this->iHaveAnyRoles(['admin','support'],['admin','support','developer'])
     * @param array $roles
     * @param array $rolesIn
     * @param bool 
    */
    public function _iHaveAnyRolesInRootRole($roles,$rolesIn);

    /**
     * Verifica se existe a permissão independente do role
     * @example  $this->iHavePermission('panel-admin->module-support->tickets->create')
     * @param string $permission
     * @return bool
    */
    public function iHavePermission($permission);

    /**
     * Verifica se existe a permissão independente do role
     * @example  $this->iHavePermission('panel-admin->module-support->tickets->create',['panel-admin->module-support->tickets->create'])
     * @param string $permission
     * @param array $permissions
     * @return bool
    */
    public function _iHavePermission($permission,$permissions);
    /**
     * Verifica se existe todas as permissões independente do role
     * @example  $this->iHaveAllPermissions(['panel-admin->module-support->tickets->create'])
     * @param array $permissions
     * @return bool
    */
    public function iHaveAllPermissions($permissions);

    /**
     * Verifica se existe pelo menos uma das permissões independente do role
     * @example  $this->iHaveAnyPermissions(['panel-admin->module-support->tickets->create'])
     * @param array $permissions
     * @return bool
    */
    public function iHaveAnyPermissions($permissions);

    /**
     * Verifica se o role que está vinculado a uma permissão possui a permissão  
     * @example $this->iHavePermissionInRoleOfRootPermissionRole('panel-admin','admin','panel-admin->module-support->tickets->create')
     * @param string $permissionRole
     * @param string role
     * @param string $permission
     * @return bool
    */
    public function iHavePermissionInRoleOfRootPermissionRole($permissionRole,$role,$permission);

    /**
     * Verifica se o role que está vinculado a uma permissão possui a permissão  
     * @example $this->iHavePermissionInRoleOfRootPermissionRole(['panel-admin','panel-user'],'panel-admin','admin','panel-admin->module-support->tickets->create')
     * @param array $permissionRoles
     * @param string $permissionRole
     * @param string role
     * @param string $permission
     * @return bool
    */
    public function _iHavePermissionInRoleOfRootPermissionRole($permissionRoles,$permissionRole,$role,$permission);

    /**
     * Verifica se o role possui está permissão
     * @example $this->iHavePermissionInRoleOfRootRole('admin','panel-admin->module-support->tickets->create')
     * @param string role
     * @param string $permission
     * @return bool
    */
    public function iHavePermissionInRoleOfRootRole($role,$permission);

    /**
     * Verifica se o role possui está permissão
     * @example $this->iHavePermissionInRoleOfRootRole(['admin','support','developer'],'admin','panel-admin->module-support->tickets->create')
     * @param string role
     * @param string $permission
     * @param array roles
     * @return bool
    */
    public function _iHavePermissionInRoleOfRootRole($roles,$role,$permission);

    /**
     * Verifica se o role possui está permissão
     * @example $this->_iHavePermissionInRole(['admin'=>['permissions'=>[]]],'panel-admin->module-support->tickets->create')
     * @param array role
     * @param string $permission
     * @return bool
    */
    public function _iHavePermissionInRole($role,$permission);
    
    /**
     * Verifica se há todas as permissoes passadas 
     * $this->iHaveAllPermissionsInRoleOfRootPermissionRole('panel-admin','admin',['panel-admin->module-support->tickets->create'])
     * @param string $permissionRole
     * @param string $role
     * @param array $permissions
     * @return bool
    */
    public function iHaveAllPermissionsInRoleOfRootPermissionRole($permissionRole,$role,$permissions);
    
    /**
     * Verifica se há todas as permissoes passadas 
     * @example $this->iHaveAllPermissionsInRoleOfRootRole('admin',['panel-admin->module-support->tickets->create'])
     * @param string $role
     * @param array $permissions
    */
    public function iHaveAllPermissionsInRoleOfRootRole($role,$permissions);

    /**
     * Verifica se há alguma das permissoes passadas
     * @example $this->iHaveAnyPermissionsInRoleOfRootPermissionRole('panel-admin','admin',['panel-admin->module-support->tickets->create'])
     * @param string $permissionRole
     * @param string $role
     * @param array $permissions
     * @return bool
    */
    public function iHaveAnyPermissionsInRoleOfRootPermissionRole($permissionRole,$role,$permissions);

    /**
     * Verifica se há alguma das permissoes passadas 
     * @example 
     * @param string $role
     * @param array $permissions
     * @return bool iHaveAnyPermissionsInRoleOfRootRole('admin',['panel-admin->module-support->tickets->create'])
    */
    public function iHaveAnyPermissionsInRoleOfRootRole($role,$permissions);

    /**
     * Busca pela pemissão com um regex, procura em todas as permissões
     * @example searchPermission('module-support')
     * @param string $search
     * @return bool
    */
    public function searchPermission($search);

    /**
     * Busca pela pemissão com um regex, procura em todas as permissões no RootPermissionRole
     * @example searchPermissionInRootPermissionRole('module-support')
     * @param string $search
     * @return bool
    */
    public function searchPermissionInRootPermissionRole($search);

    /**
     * Busca pela pemissão com um regex, procura em todas as permissões no RootRole
     * @example searchPermissionInRootRole('module-support')
     * @param string $permission
     * @return bool
    */
    public function searchPermissionInRootRole($search);
    
    /**
     * Busca pela pemissão com um regex, procura em todas as permissões de um determinado role
     * @example searchPermissionInRoleOfRootRole('panel-admin','module-support')
     * @param string $role
     * @param string $search
     * @return bool
    */
    public function searchPermissionInRoleOfRootRole($role,$search);
    
    /**
     * Busca pela pemissão com um regex , procura em todas as permissões de um determinado role no RootPermissionRole
     * @example searchPermissionInRoleOfRootPermissionRole('panel-admin','admin','module-support')
     * @param string $permissionRole
     * @param string $role
     * @param string $search
     * @return bool
    */
    public function searchPermissionInRoleOfRootPermissionRole($permissionRole,$role,$search);
}