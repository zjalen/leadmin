<?php

namespace Zjalen\Leadmin\Auth\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Collection;

class AdminUser extends Model implements AuthenticatableContract
{
    use Authenticatable;
    protected $table = 'admin_users';

    // 可自动填充
    protected $fillable = [ 'username', 'password', 'remember_token', 'name', 'email', 'avatar', ];

    // 自动转换属性
    protected $casts = [ ];

//    /**
//     * Get avatar attribute.
//     *
//     * @param string $avatar
//     *
//     * @return string
//     */
//    public function getAvatarAttribute($avatar)
//    {
//        $disk = config('admin.upload.disk');
//
//        if ($avatar && array_key_exists($disk, config('filesystems.disks'))) {
//            return Storage::disk(config('admin.upload.disk'))->url($avatar);
//        }
//
//        return admin_asset('/vendor/laravel-admin/AdminLTE/dist/img/user2-160x160.jpg');
//    }

    /**
     * A user has and belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles() : BelongsToMany
    {

        return $this->belongsToMany(AdminRole::class, 'admin_role_users', 'user_id', 'role_id');
    }

    /**
     * A User has to many permissions.
     *
     * @return Collection
     */
    public function permissions() : Collection
    {
        return $this->roles()->with('permissions')->get()->pluck('permissions')->flatten();
    }

    /**
     * Get all permissions of user.
     *
     * @return mixed
     */
    public function allPermissions() : Collection
    {
        return $this->permissions();
    }

    /**
     * Check if user has permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function can(string $permission) : bool
    {
        if ($this->isAdministrator()) {
            return true;
        }

        return $this->roles()->with('permissions')->get()->pluck('permissions')->flatten()->pluck('slug')->contains($permission);
    }

    /**
     * Check if user has no permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function cannot(string $permission) : bool
    {
        return !$this->can($permission);
    }

    /**
     * Check if user is administrator.
     *
     * @return mixed
     */
    public function isAdministrator() : bool
    {
        return $this->isRole('administrator');
    }

    /**
     * Check if user is $role.
     *
     * @param string $role
     *
     * @return mixed
     */
    public function isRole(string $role) : bool
    {
        return $this->roles()->pluck('slug')->contains($role);
    }

    /**
     * Check if user in $roles.
     *
     * @param array $roles
     *
     * @return mixed
     */
    public function inRoles(array $roles = []) : bool
    {
        return $this->roles()->pluck('slug')->intersect($roles)->isNotEmpty();
    }

    /**
     * If visible for roles.
     *
     * @param $roles
     *
     * @return bool
     */
    public function visible(array $roles = []) : bool
    {
        if (empty($roles)) {
            return true;
        }

        $roles = array_column($roles, 'slug');

        return $this->inRoles($roles) || $this->isAdministrator();
    }
}
