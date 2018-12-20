<?php

namespace Zjalen\Leadmin\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Zjalen\Leadmin\Traits\ModelTree;
use Illuminate\Support\Facades\DB;

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
     * Get all elements.
     *
     * @return mixed
     */
    public function allNodes()
    {
        $orderColumn = DB::getQueryGrammar()->wrap($this->orderColumn);
        $byOrder = $orderColumn.' = 0,'.$orderColumn;

        $self = new static();

        if ($this->queryCallback instanceof \Closure) {
            $self = call_user_func($this->queryCallback, $self);
        }

        return $self->with('roles')->orderByRaw($byOrder)->get()->toArray();
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
