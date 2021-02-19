<?php

namespace Silveton\Acl\Exceptions;


use InvalidArgumentException;

class RoleAlreadyExists extends InvalidArgumentException
{
    public static function name(string $roleName)
    {
        return new static(trans('acl::exceptions.RoleAlreadyExistsName',['name'=>$roleName]));
    } 

    public static function slug(string $roleSlug)
    {
        return new static(trans('acl::exceptions.RoleAlreadyExistsSlug',['slug'=>$roleSlug]));
    }
}
