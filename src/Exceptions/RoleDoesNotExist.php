<?php
namespace Silveton\Acl\Exceptions;

use InvalidArgumentException;

class RoleDoesNotExist extends InvalidArgumentException
{
    public static function named(string $roleName)
    {
        return new static("There is no role named `{$roleName}`.");
    }

    public static function withId(int $roleCode)
    {
        return new static("There is no role with code `{$roleCode}`.");
    }
}