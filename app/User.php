<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;

    /**
     * 身份字段，boot() 中会根据此字段设定 global scope
     * @var string
     */
    protected static $identityColumn = '';

    protected static function boot()
    {
        parent::boot();

        if (static::$identityColumn) {
            static::addGlobalScope('student', function (Builder $builder) {
                $builder->where(static::$identityColumn, '>', 0);
            });
        }
    }

    /**
     * 检查用户是否当前 model 角色
     * @param User $user
     * @return bool
     */
    public static function checkIdentity(User $user)
    {
        return static::$identityColumn && $user->getAttribute(static::$identityColumn) > 0;
    }

    /**
     * User 根据身份不同建了多个子类，所以需要在此处固定表名
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_student' => 'int',
        'is_teacher' => 'int',
        'is_system_admin' => 'int',
    ];
}
