<?php

namespace Silveton\Acl\Exceptions;

use InvalidArgumentException;

class PermissionAlreadyExists extends InvalidArgumentException
{   
    public static function name(string $name)
    {
        return new static(trans('acl::exceptions.PermissionAlreadyExistsName',['name'=>$name]));
    }

    public static function slug(string $slug)
    {
        return new static(trans('acl::exceptions.PermissionAlreadyExistsSlug',['slug'=>$slug]));
    }

    public static function ability(string $ability)
    {
        return new static(trans('acl::exceptions.PermissionAlreadyExistsAbility',['ability'=>$ability]));
    }
}
