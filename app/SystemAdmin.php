<?php

namespace App;

class SystemAdmin extends User
{
    protected static $identityColumn = 'is_system_admin';
}
