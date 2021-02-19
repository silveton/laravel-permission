<?php
namespace Silveton\Acl\Traits;

trait IHavePermission {

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
    public function getPermissionRoleKey()
    {
        return 'permission-roles';
    }

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
    public function getRoleKey(){
        return 'roles';
    }

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
    public function getPermissionKey()
    {
        return 'permissions';
    }

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
    public function bootPermissions()
    {
        return [
            $this->getPermissionRoleKey()=>[
             
            ],
            $this->getRoleKey()=>[
             
            ]
        ];
    }

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
    public function getRootPermissionRoles()
    {   
        return  $this->bootPermissions()[$this->getPermissionRoleKey()] ?? [];
    }

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
    public function getRootRoles()
    {
        return  $this->bootPermissions()[$this->getRoleKey()];
    }

    /**
     * Seleciona todos os role que estão vinculados com uma permissão especifica
     * @example $this->getPermissionRoleInRootPermissionRole('panel-admin)
     * @param string $permissionRole
     * @return array
    */
    public function getPermissionRoleInRootPermissionRole($permissionRole)
    {
        return $this->getRootPermissionRoles()[$permissionRole] ?? [];
    }

    /**
     * Seleciona um role o que esta vinculado a uma permissão específica
     * @example $this->getRoleInRootPermissionRole('admin')
     * @param string $role
     * @return array
    */
    public function getRoleInRootPermissionRole($role,$permissionRole)
    {
        return $this->getPermissionRoleInRootPermissionRole($permissionRole)[$role] ?? [];
    }

    /**
     * Seleciona um role
     * @example $this->getRoleInRootRole('admin')
     * @param string $role
     * @return string
    */
    public function getRoleInRootRole($role)
    {
        return $this->getRootRoles()[$role] ?? null;
    }

     /**
     * Selecione todas permissões de um de terminado role que está ligado a uma permissão
     * @example $this->getPermissionsByRoleAndPermissionRoleInRootPermissionRole('admin','panel-admin')
     * @param $role
     * @param $permissionRole
     * @return array
    */
    public function getPermissionsByRoleAndPermissionRoleInRootPermissionRole($role,$permissionRole)
    {
        return $this->getRoleInRootPermissionRole($role,$permissionRole)[$this->getPermissionKey()] ?? [];
    }

    /**
     * Selecione todas permissões de um de terminado role
     * @example $this->getPermissionsByRoleInRootRole('admin')
     * @param $role
     * @return array
    */
    public function getPermissionsByRoleInRootRole($role)
    {
        return $this->getRoleInRootRole($role)[$this->getPermissionKey()] ?? [];
    }

    /**
     * Seleciona todas as permissões independete de role 
     * @return array
    */
    public function getAllPermissions()
    {
        $bootPermissions = $this->bootPermissions();

        return array_merge( $this->_getAllPermissionsInRootPermissionRole($bootPermissions),$this->_getAllPermissionsInRootRole($bootPermissions));
    }

    /**
     * Seleciona todas as permissões
     * @return array
    */
    public function getAllPermissionsInRootPermissionRole()
    {
        return $this->_getAllPermissionsInRootPermissionRole($this->bootPermissions());
    }

    /**
     * Seleciona todas as permissões
     * @return array
    */
    public function _getAllPermissionsInRootPermissionRole($bootPermissions)
    {
        $permissions = [];

        foreach($bootPermissions[$this->getPermissionRoleKey()] as $permissionRole)
        {
            foreach($permissionRole as $role)
            {
                foreach($role[$this->getPermissionKey()] as $permission)
                {
                    if( ! \in_array($permission,$permissions) )
                    {
                        \array_push($permissions,$permission);
                    }
                }
            }
        }
    
        return $permissions;
    }

    /**
     * Seleciona todas as permissões 
     * @return array
    */
    public function getAllPermissionsInRootRole()
    {
        return $this->_getAllPermissionsInRootRole($this->bootPermissions());
    }

    /**
     * Seleciona todas as permissões 
     * @param $bootPermissions
     * @return array
    */
    public function _getAllPermissionsInRootRole($bootPermissions)
    {
        $permissions = [];

        foreach($bootPermissions[$this->getRoleKey()] as $role)
        {
            foreach($role[$this->getPermissionKey()] as $permission)
            {
                if( ! \in_array($permission,$permissions) )
                {
                    \array_push($permissions,$permission);
                }
            }
        }
       
        return $permissions;
    }

    /**
     * Verifica se possui a permissão que tem roles 
     * @example $this->iHavePermissionRoleInRootPermissionRole('panel-admin')
     * @param string $permissionRole
     * @return bool
    */
    public function iHavePermissionRoleInRootPermissionRole($permissionRole)
    {
        return $this->_iHavePermissionRoleInRootPermissionRole($permissionRole,$this->getRootPermissionRoles());
    }

    /**
     * Verifica sepossui a permissão que tem roles
     * @example $this->_iHavePermissionRoleInRootPermissionRole('panel-admin',['panel-admin','panel-user'])
     * @param string $permissionRole
     * @param array $permissionRoles
     * @return bool
    */
    public function _iHavePermissionRoleInRootPermissionRole($permissionRole, $permissionRoles)
    {
        return array_key_exists($permissionRole,$permissionRoles);
    }

    
    /**
     * Verifica se o role passado existe dentro desta permissão
     * @example $this->iHaveRoleToPermissionInRootPermissionRole('admin','panel-admin')
     * @param string $role
     * @param string $permissionRole
     * @return bool 
    */
    public function iHaveRoleToPermissionInRootPermissionRole($role, $permissionRole)
    {
        return $this->_iHaveRoleToPermissionInRootPermissionRole($role, $permissionRole,$this->getRootPermissionRoles());
    }

    /**
     * Verifica se o role passado existe dentro desta permissão
     * @example $this->_iHaveRoleToPermissionInRootPermissionRole('admin','panel-admin',['panel-admin','panel-user'])
     * @param string $role
     * @param string $permissionRole
     * @param array $permissionRoles
     * @return bool 
    */
    public function _iHaveRoleToPermissionInRootPermissionRole($role, $permissionRole, $permissionRoles)
    {
        if( ! $this->_iHavePermissionRoleInRootPermissionRole($permissionRole,$permissionRoles))
        {
            return false;
        }

        return $this->_iHaveRole($role,$permissionRoles[$permissionRole]);
    }

    /**
     * Verifica se o role passado existe nos roles que não stão vinculados a uma permissão
     * @example $this->iHaveRoleInRootRole('admin')
     * @param string $role
     * @return bool 
    */
    public function iHaveRoleInRootRole($role)
    {
        return $this->_iHaveRole($role,$this->getRootRoles());
    }

    /**
     * Verifica se o role passado existe
     * @example $this->_iHaveRole('admin',['admin','support'])
     * @param string $role
     * @param array $roles
     * @return bool 
    */
    public function _iHaveRole($role,$roles)
    {
        return array_key_exists($role,$roles);
    }

    /**
     * Verifica se todos roles passado existe dentro desta permissão
     * $this->iHaveAllRolesToPermissionInRootPermissionRole(['admin','support'],'panel-admin')
     * @param string $permissionRole
     * @param array $roles
     * @return bool 
    */
    public function iHaveAllRolesToPermissionInRootPermissionRole($permissionRole,$roles)
    {
        return $this->_iHaveAllRolesToPermissionInRootPermissionRole($permissionRole,$roles,$this->getRootPermissionRoles());
    }

    /**
     * Verifica se todos roles passado existe dentro desta permissão
     * $this->_iHaveAllRolesToPermissionInRootPermissionRole(['admin','support'],'panel-admin')
     * @param string $permissionRole
     * @param array $roles
     * @param array $rolesIn
     * @return bool 
    */
    public function _iHaveAllRolesToPermissionInRootPermissionRole($permissionRole, $roles, $rolesIn)
    {
        foreach($roles as $role)
        {
            if( ! $this->_iHaveRoleToPermissionInRootPermissionRole($role,$permissionRole,$rolesIn))
            {
                return false;
            }
        }

        return true;
    }
   
    /**
     * Veririca se possui todos os roles passados
     * $this->iHaveAllRolesInRootRole(['admin','support'])
     * @param array $role
     * @param bool 
    */
    public function iHaveAllRolesInRootRole($roles)
    {
        return $this->_iHaveAllRolesInRootRole($roles,$this->getRootRoles());
    }

    /**
     * Veririca se possui todos os roles passados
     * $this->_iHaveAllRolesInRootRole(['admin','support'],['admin','support','developer'])
     * @param array $roles
     * @param array $rolesIn
     * @param bool 
    */
    public function _iHaveAllRolesInRootRole($roles,$rolesIn)
    {
        foreach($roles as $role)
        {
            if( ! $this->_iHaveRole($role,$rolesIn))
            {
                return false;
            }
        }

        return  true;
    }

    
    /**
     * Verifica se algum dos roles passado existe dentro desta permissão
     * @example $this->iHaveAnyRolesInRootPermissionRole('panel-admin',['admin','support','developer']);
     * @param array $roles
     * @param string $permissionRole
     * @return bool 
    */
    public function iHaveAnyRolesInRootPermissionRole($permissionRole, $roles)
    {
        return $this->_iHaveAnyRolesInRootPermissionRole($roles,$permissionRole,$this->getRootPermissionRoles());
    }

    /**
     * Verifica se algum dos roles passado existe dentro desta permissão
     * @example $this->_iHaveAnyRolesInRootPermissionRole('panel-admin',['admin','support','developer'],['admin','support','developer']);
     * @param array $roles
     * @param string $permissionRole
     * @param array $permissionRoles
     * @return bool 
    */
    public function _iHaveAnyRolesInRootPermissionRole($roles, $permissionRole, $permissionRoles)
    {

        foreach($roles as $role)
        {
            if( $this->_iHaveRoleToPermissionInRootPermissionRole($role,$permissionRole,$permissionRoles) )
            {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Verifica se existe pelo menos uma das roles passadas
     * @example $this->iHaveAnyRoles(['admin','support'])
     * @param array $roles
     * @param bool 
    */
    public function iHaveAnyRolesInRootRole($roles)
    {
        return $this->_iHaveAnyRolesInRootRole($roles,$this->getRootRoles());
    }

    /**
     * Verifica se existe pelo menos uma das roles passadas
     * @example $this->iHaveAnyRoles(['admin','support'],['admin','support','developer'])
     * @param array $roles
     * @param array $rolesIn
     * @param bool 
    */
    public function _iHaveAnyRolesInRootRole($roles,$rolesIn)
    {
        foreach($roles as $role)
        {
            if($this->_iHaveRole($role,$rolesIn))
            {
                return true;
            }
        }

        return  false;
    }

    /**
     * Verifica se existe a permissão independente do role
     * @example  $this->iHavePermission('panel-admin->module-support->tickets->create')
     * @param string $permission
     * @return bool
    */
    public function iHavePermission($permission)
    {
        return $this->_iHavePermission($permission,$this->getAllPermissions());
    }

    /**
     * Verifica se existe a permissão independente do role
     * @example  $this->iHavePermission('panel-admin->module-support->tickets->create',['panel-admin->module-support->tickets->create'])
     * @param string $permission
     * @param array $permissions
     * @return bool
    */
    public function _iHavePermission($permission,$permissions)
    {
        return \in_array($permission,$permissions);
    }

    /**
     * Verifica se existe todas as permissões independente do role
     * @example  $this->iHaveAllPermissions(['panel-admin->module-support->tickets->create'])
     * @param array $permissions
     * @return bool
    */
    public function iHaveAllPermissions($permissions)
    {
        $permissionsIn = $this->getAllPermissions();

        foreach($permissions as $permission)
        {
            if( ! $this->_iHavePermission($permission,$permissionsIn))
            {
                return false;
            }
        }

        return true;
    }

    /**
     * Verifica se existe pelo menos uma das permissões independente do role
     * @example  $this->iHaveAnyPermissions(['panel-admin->module-support->tickets->create'])
     * @param array $permissions
     * @return bool
    */
    public function iHaveAnyPermissions($permissions)
    {
        $permissionsIn = $this->getAllPermissions();

        foreach($permissions as $permission)
        {
            if( $this->_iHavePermission($permission,$permissionsIn) )
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifica se o role que está vinculado a uma permissão possui a permissão  
     * @example $this->iHavePermissionInRoleOfRootPermissionRole('panel-admin','admin','panel-admin->module-support->tickets->create')
     * @param string $permissionRole
     * @param string role
     * @param string $permission
     * @return bool
    */
    public function iHavePermissionInRoleOfRootPermissionRole($permissionRole,$role,$permission)
    {
       return $this->_iHavePermissionInRoleOfRootPermissionRole( $this->getRootPermissionRoles(),$permissionRole,$role,$permission);
    }

    /**
     * Verifica se o role que está vinculado a uma permissão possui a permissão  
     * @example $this->iHavePermissionInRoleOfRootPermissionRole(['panel-admin','panel-user'],'panel-admin','admin','panel-admin->module-support->tickets->create')
     * @param array $permissionRoles
     * @param string $permissionRole
     * @param string role
     * @param string $permission
     * @return bool
    */
    public function _iHavePermissionInRoleOfRootPermissionRole($permissionRoles,$permissionRole,$role,$permission)
    {
        if( ! $this->_iHavePermissionRoleInRootPermissionRole($permissionRole,$permissionRoles))
        {
            return false;
        }

        if( ! $this->_iHaveRole($role,$permissionRoles[$permissionRole]))
        {
            return false;
        }
    
        if( ! $this->_iHavePermissionInRole($permissionRoles[$permissionRole][$role],$permission))
        {
            return false;
        }

        return true;
    }

    /**
     * Verifica se o role possui está permissão
     * @example $this->iHavePermissionInRoleOfRootRole('admin','panel-admin->module-support->tickets->create')
     * @param string role
     * @param string $permission
     * @return bool
    */
    public function iHavePermissionInRoleOfRootRole($role,$permission)
    {
        return $this->_iHavePermissionInRoleOfRootRole($this->getRootRoles(),$role,$permission);
    }

    /**
     * Verifica se o role possui está permissão
     * @example $this->iHavePermissionInRoleOfRootRole(['admin','support','developer'],'admin','panel-admin->module-support->tickets->create')
     * @param string role
     * @param string $permission
     * @param array roles
     * @return bool
    */
    public function _iHavePermissionInRoleOfRootRole($roles,$role,$permission)
    {
        if( ! $this->_iHaveRole($role,$roles))
        {
            return false;
        }

        if( ! $this->_iHavePermissionInRole($roles[$role],$permission))
        {
            return false;
        }

        return true;
    }

    /**
     * Verifica se o role possui está permissão
     * @example $this->_iHavePermissionInRole(['admin'=>['permissions'=>[]]],'panel-admin->module-support->tickets->create')
     * @param array role
     * @param string $permission
     * @return bool
    */
    public function _iHavePermissionInRole($role,$permission)
    {
        return in_array($permission,$role[$this->getPermissionKey()]);
    }

    /**
     * Verifica se há todas as permissoes passadas 
     * $this->iHaveAllPermissionsInRoleOfRootPermissionRole('panel-admin','admin',['panel-admin->module-support->tickets->create'])
     * @param string $permissionRole
     * @param string $role
     * @param array $permissions
     * @return bool
    */
    public function iHaveAllPermissionsInRoleOfRootPermissionRole($permissionRole,$role,$permissions)
    {
        $permissionRoles = $this->getRootPermissionRoles();

        foreach($permissions as $permission)
        {
            if( ! $this->_iHavePermissionInRoleOfRootPermissionRole($permissionRoles,$permissionRole,$role,$permission))
            {
                return false;
            }
        }

        return true;
    }
    
    /**
     * Verifica se há todas as permissoes passadas 
     * @example $this->iHaveAllPermissionsInRoleOfRootRole('admin',['panel-admin->module-support->tickets->create'])
     * @param string $role
     * @param array $permissions
    */
    public function iHaveAllPermissionsInRoleOfRootRole($role,$permissions)
    {
        $roles = $this->getRootRoles();

        foreach($permissions as $permission)
        {
            if( ! $this->_iHavePermissionInRoleOfRootRole($roles,$role,$permission))
            {
                return false;
            }
        }

        return true;
    }

    /**
     * Verifica se há alguma das permissoes passadas
     * @example $this->iHaveAnyPermissionsInRoleOfRootPermissionRole('panel-admin','admin',['panel-admin->module-support->tickets->create'])
     * @param string $permissionRole
     * @param string $role
     * @param array $permissions
     * @return bool
    */
    public function iHaveAnyPermissionsInRoleOfRootPermissionRole($permissionRole,$role,$permissions)
    {
        $permissionRoles = $this->getRootPermissionRoles();

        foreach($permissions as $permission)
        {
            if( $this->_iHavePermissionInRoleOfRootPermissionRole($permissionRoles,$permissionRole,$role,$permission))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifica se há alguma das permissoes passadas 
     * @example 
     * @param string $role
     * @param array $permissions
     * @return bool iHaveAnyPermissionsInRoleOfRootRole('admin',['panel-admin->module-support->tickets->create'])
    */
    public function iHaveAnyPermissionsInRoleOfRootRole($role,$permissions)
    {
        $roles = $this->getRootRoles();

        foreach($permissions as $permission)
        {
            if( $this->_iHavePermissionInRoleOfRootRole($roles,$role,$permission))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Busca pela pemissão com um regex, procura em todas as permissões
     * @example searchPermission('module-support')
     * @param string $search
     * @return bool
    */
    public function searchPermission($search)
    {
        foreach($this->getAllPermissions() as $permission)
        {
            if(\strpos($permission,$search) !== false )
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Busca pela pemissão com um regex, procura em todas as permissões no RootPermissionRole
     * @example searchPermissionInRootPermissionRole('module-support')
     * @param string $search
     * @return bool
    */
    public function searchPermissionInRootPermissionRole($search)
    {
        foreach($this->getAllPermissionsInRootPermissionRole() as $permission)
        {
            if(\strpos($permission,$search) !== false )
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Busca pela pemissão com um regex, procura em todas as permissões no RootRole
     * @example searchPermissionInRootRole('module-support')
     * @param string $permission
     * @return bool
    */
    public function searchPermissionInRootRole($search)
    {
        foreach($this->getAllPermissionsInRootRole() as $permission)
        {
            if(\strpos($permission,$search) !== false )
            {
                return true;
            }
        }

        return false;
    }
    
    /**
     * Busca pela pemissão com um regex, procura em todas as permissões de um determinado role
     * @example searchPermissionInRoleOfRootRole('panel-admin','module-support')
     * @param string $role
     * @param string $search
     * @return bool
    */
    public function searchPermissionInRoleOfRootRole($role,$search)
    {
        $roles = $this->getRootRoles();

        if( ! $this->_iHaveRole($role, $roles))
        {
            return false;
        }

        foreach($roles[$role][$this->getPermissionKey()]  as $permission)
        {
            if(\strpos($permission,$search) !== false )
            {
                return true;
            }
        }

        return false;
    }
    
    /**
     * Busca pela pemissão com um regex , procura em todas as permissões de um determinado role no RootPermissionRole
     * @example searchPermissionInRoleOfRootPermissionRole('panel-admin','admin','module-support')
     * @param string $permissionRole
     * @param string $role
     * @param string $search
     * @return bool
    */
    public function searchPermissionInRoleOfRootPermissionRole($permissionRole,$role,$search)
    {
        $permissionRoles = $this->getRootPermissionRoles();

        if( ! $this->_iHavePermissionRoleInRootPermissionRole($permissionRole,$permissionRoles))
        {
            return false;
        }

        if( ! $this->_iHaveRole($role,$permissionRoles[$permissionRole]))
        {
            return false;
        }

        foreach($permissionRoles[$permissionRole][$role][$this->getPermissionKey()] as $permission)
        {
            if(\strpos($permission,$search) !== false )
            {
                return true;
            }
        }
    
        return false;
    }
}