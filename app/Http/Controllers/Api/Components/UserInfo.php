<?php

namespace App\Http\Controllers\Api\Components;

use App\Student;
use App\SystemAdmin;
use App\Teacher;
use App\User;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;

/**
 * Class UserInfo
 * @package App\Http\Controllers\Api\Components
 *
 * @Schema()
 */
class UserInfo implements \JsonSerializable
{
    /**
     * @Property(description="Bearer Token，不带 Bearer 前缀")
     * @var string
     */
    public $access_token;

    /**
     * @Property(description="过期时间，时间戳")
     * @var integer
     */
    public $expires_at;

    /**
     * @Property(description="ID")
     * @var integer
     */
    public $id;

    /**
     * @Property(description="姓名")
     * @var string
     */
    public $name;

    /**
     * @Property(description="角色")
     * @var string
     */
    public $role;

    /**
     * @Property(description="是否绑定了 line 账号")
     * @var bool
     */
    public $lineBinded = false;

    public function __construct(User $user, string $access_token, int $expires_at)
    {
        $this->id = $user->id;
        $this->name = $user->name;
        $this->access_token = $access_token;
        $this->expires_at = $expires_at;
        $this->lineBinded = $user->line_id !== '';

        SystemAdmin::checkIdentity($user) && $this->role = 'system_admin';
        Teacher::checkIdentity($user) && $this->role = 'teacher';
        Student::checkIdentity($user) && $this->role = 'student';
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
