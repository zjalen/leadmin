<?php

namespace Zjalen\Leadmin\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Zjalen\Leadmin\Traits\ModelTree;

class AdminMenu extends Model
{
    protected $table = 'admin_menu';

    use ModelTree {
        ModelTree::boot as treeBoot;
    }

    // 可自动填充
    protected $fillable = [ 'parent_id', 'order', 'title', 'icon', 'url', ];

    // 自动转换属性
    protected $casts = [ ];


    public function roles() :BelongsToMany
    {
        return $this->belongsToMany(AdminRole::class, 'admin_role_menu', 'menu_id', 'role_id');
    }

    /**
     * Detach models from the relationship.
     *
     * @return void
     */
    protected static function boot()
    {
        static::treeBoot();

        static::deleting(function ($model) {
            $model->roles()->detach();
        });
    }
}
