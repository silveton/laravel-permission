<?php

namespace Silveton\Acl\Exceptions;

use InvalidArgumentException;

class PermissionDoesNotExist extends InvalidArgumentException
{
    public static function create(string $permissionName, string $permission = '')
    {
        return new static("There is no permission named `{$permission}` for guard `{$permission}`.");
    }

    public static function withId(int $permissionCode, string $permission = '')
    {
        return new static("There is no [permission] with code `{$permissionCode}` for permission `{$permission}`.");
    }
}
