
## Exemplos

````
    $permission1 = Permission::createPermission('Painel do Admin',null,null,null,'Painel de Administração do sistema');
        $permission2 = Permission::createPermission('Modulo A.C.L',null,null,null,'Modulo de A.C.L',$permission1->PERMISSION_CODE);
            $permission3 = Permission::createPermission('Usuários',null,null,null,'Gerenciamento de usuários',$permission2->PERMISSION_CODE);
                $permission4 = Permission::createPermission('Cadastrar',null,null,null,'Cadastrar usuário',$permission3->PERMISSION_CODE);
                $permission5 = Permission::createPermission('Editar',null,null,null,'Editar usuário',$permission3->PERMISSION_CODE);
                $permission6 = Permission::createPermission('Excluir',null,null,null,'Excluír usuário',$permission3->PERMISSION_CODE);
                $permission7 = Permission::createPermission('Redefinir Senha',null,null,null,'Redefinir senha do usuário',$permission3->PERMISSION_CODE);
        $permission8 = Permission::createPermission('Modulo Suporte',null,null,null,'Modulo de Suporte',$permission1->PERMISSION_CODE);
            $permissio9 = Permission::createPermission('Tickets',null,null,null,'Gerenciamento  Tickets',$permission8->PERMISSION_CODE);
                $permission10 = Permission::createPermission('Cadastrar',null,null,null,'Cadastrar Ticket',$permissio9->PERMISSION_CODE);
                $permission11 = Permission::createPermission('Editar',null,null,null,'Editar Ticket',$permissio9->PERMISSION_CODE);
                $permission12 = Permission::createPermission('Excluir',null,null,null,'Excluír Ticket',$permissio9->PERMISSION_CODE);


    $roleAdmin = Role::createRole('Admin',null,$permission1->PERMISSION_CODE);
    $roleSuporte = Role::createRole('Suporte');

    $roleAdmin->addPermission($permission4);
    $roleAdmin->addPermission($permission5);
    $roleAdmin->addPermission($permission6);
    $roleAdmin->addPermission($permission7);

    $roleSuporte->addPermission($permission10);
    $roleSuporte->addPermission($permission11);
    $roleSuporte->addPermission($permission12);

    $roleAdmin->removePermission($permission4);
    $roleAdmin->removePermission($permission5);
    $roleAdmin->removePermission($permission6);
    $roleAdmin->removePermission($permission7);

    $roleSuporte->removePermission($permission10);
    $roleSuporte->removePermission($permission11);
    $roleSuporte->removePermission($permission12);


````

## commnand

````
composer require silveton/acl
````

## Adicionar ao ServicesProvider

````
'providers' => [
    Silveton\Acl\AclServiceProvider::class,
];
````

## Rodar as publicações

````
php artisan vendor:publish --provider="Silveton\Acl\AclServiceProvider"
````

## Rodas as migrações
````
php artisan migrate
````

## Adicionar o contrado a sua classe  
````
use Silveton\Acl\Contracts\IHavePermission as IHavePermissionContract;

class User extends Authenticatable implements IHavePermissionContract
{

}
````

## Adicionar Trait em sua classe  
````
use Silveton\Acl\Traits\IHavePermission;

class User extends Authenticatable implements IHavePermissionContract
{
    use IHavePermission;

}
````

## Implementar sua logica para recuperar suas permissões 
````
class User extends Authenticatable implements IHavePermissionContract
{
    use IHavePermission;

    public function bootPermissions()
    {
        return [
            $this->getPermissionRoleKey()=>[
             
            ],
            $this->getRoleKey()=>[
              
            ]
        ];
    }

}
````
