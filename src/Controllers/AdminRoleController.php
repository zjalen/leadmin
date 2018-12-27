<?php

namespace Zjalen\Leadmin\Controllers;

use Zjalen\Leadmin\Auth\Models\AdminPermission;
use Zjalen\Leadmin\Auth\Models\AdminRole;

use Zjalen\Leadmin\Requests\AdminRoleRequest;
use Illuminate\Routing\Controller;

class AdminRoleController extends Controller
{

    /**
     * Index interface.
     * @param AdminRoleRequest $request
     *
     * @return mixed
     */
    public function index(AdminRoleRequest $request)
    {
        $conditions = $request->input('filters');
        if ($conditions) {
            $conditions = json_decode(stripslashes($conditions), true);
        }else {
            $conditions = [];
        }
        $sort = $request->input('_sort',['column'=> 'created_at', 'type'=> 'desc']);
        $limit = $request->input('limit', 20);
        $skip = $request->input('skip', 0);
        $data = [
            'title'=> '角色管理',
            'description'=> '角色创建与权限分配',
            'headers'=>[
                ['title'=> 'id','name'=> 'id', 'width'=> 100],
                ['title'=> '名称','name'=> 'name'],
                ['title'=> '标识','name'=> 'slug'],
                ['title'=> '权限','name'=> 'permissions', 'multiselect'=>true],
            ],
            'header_actions'=>[
                ['action'=>'create', 'type'=> 'primary', 'text'=> '添加', 'icon'=> 'fa-plus'],
                ['action'=>'filter', 'type'=> 'danger', 'text'=> '筛选', 'icon'=> 'fa-search'],
                //['action'=>'export', 'type'=> 'success', 'text'=> '导出', 'icon'=> 'fa-table'],
            ],
            'body'=>[],
            'actions'=>[
                'width'=>'200',
                'button'=>[
                    ['action'=>'edit', 'type'=> 'primary', 'text'=> '编辑', 'icon'=> 'fa-edit'],
                    ['action'=>'delete', 'type'=> 'danger', 'text'=> '删除', 'icon'=> 'fa-trash'],
                ]
            ],
            'count'=> 0,
        ];
        if (count($conditions) > 0){
            $data['body'] = AdminRole::where(function ($query) use ($conditions){
                foreach ($conditions as $key => $condition) {
                    if (strstr( $key, '%')){
                        $key = trim($key, '%');
                        $query->where($key, 'like', '%'.$condition.'%');
                    }else {
                        $query->where($key, $condition);
                    }
                }
            })->orderBy($sort['column'], $sort['type'])->limit($limit)->skip($skip)->with('permissions')->get()->toArray();
            $data['count'] = AdminRole::where(function ($query) use ($conditions){
                foreach ($conditions as $key => $condition) {
                    if (strstr( $key, '%')){
                        $key = trim($key, '%');
                        $query->where($key, 'like', '%'.$condition.'%');
                    }else {
                        $query->where($key, $condition);
                    }
                }
            })->count();
        }else {
            $data['body'] = AdminRole::orderBy($sort['column'], $sort['type'])->limit($limit)->skip($skip)->with('permissions')->get()->toArray();
            $data['count'] = AdminRole::all()->count();
        }
        $filters = [
            'headers'=>[
                    ['title'=> '名称','name'=> 'name'],
                    ['title'=> '标识','name'=> 'slug']
            ],
            'body'=>[
                    'name'=> array_key_exists('name', $conditions) ? $conditions['name']: null ,
                    'slug'=> array_key_exists('slug', $conditions) ? $conditions['slug']: null ,

            ],
        ];
        if ($request->ajax()){
            return $data;
        }
        $boxes = [
//            [
//                'color'=> '#ff6b5f',
//                'icon'=> 'fa-users',
//                'title'=> $data['count'],
//                'description'=> '总数量',
//                //'link'=> 'www.baidu.com'
//            ],
        ];
        return view('leadmin.commons.index',['boxes'=> json_encode($boxes), 'data'=> json_encode($data),'filters'=> json_encode($filters)]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $data = [
            'title'=> '添加',
            'description'=> '添加管理',
            'headers'=>[
                    ['title'=> '名称','name'=> 'name', 'width'=> 100],
                    ['title'=> '标识','name'=> 'slug', 'width'=> 100],
                    ['title'=> '权限','name'=> 'permissions', 'multiselect'=>AdminPermission::all(['name','id'])->toArray()],
            ],
            'body'=>[
                    'name'=> null,
                    'slug'=> null,
                    'permissions'=>[]
            ],
            'rules' => [
                'name' => [
                    ['required'=>true, 'message'=>'名称必填', 'trigger'=> 'blur'],
                    ['max'=>20, 'message'=>'长度不大于20', 'trigger'=> 'blur'],
                ]
            ],
        ];
        return view('leadmin.commons.create_and_edit',['data'=> json_encode($data)]);
    }

    /**
     * @param AdminRoleRequest $request
     * @return array
     */
    public function store(AdminRoleRequest $request)
    {
        $receive = $request->input();
        $model = new AdminRole;
        $model->fill($receive);
        $res = $model->save();
        if ($receive['permissions']){
            $model->permissions()->sync($receive['permissions']);
        }

        if ($res) {
            return ['error_code' => 0, 'error_message' => '更新成功'];
        }else {
            return ['error_code' => 1, 'error_message' => '更新失败'];
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = AdminRole::find($id);
        $permissions = $model->permissions->pluck('id')->toArray();
        $model = $model->toArray();
        $model['permissions'] = $permissions;
        $data = [
            'title'=> '编辑',
            'description'=> '编辑管理',
            'headers'=>[
                    ['title'=> '名称','name'=> 'name', 'width'=> 100],
                    ['title'=> '标识','name'=> 'slug', 'width'=> 100],
                    ['title'=> '权限','name'=> 'permissions', 'multiselect'=>AdminPermission::all(['name','id'])->toArray()],
            ],
            'body'=>$model,
            'rules' => [
                'name' => [
                    ['required'=>true, 'message'=>'名称必填', 'trigger'=> 'blur'],
                    ['max'=>20, 'message'=>'长度不大于20', 'trigger'=> 'blur'],
                ],
                'slug' => [
                    ['required'=>true, 'message'=>'标识必填', 'trigger'=> 'blur'],
                    ['max'=>20, 'message'=>'长度不大于20', 'trigger'=> 'blur'],
                ],
            ],
        ];
        return view('leadmin.commons.create_and_edit',['data'=> json_encode($data)]);
    }

    /**
     * @param AdminRoleRequest $request
     * @param $id
     * @return array
     */
    public function update(AdminRoleRequest $request, $id)
    {
        $receive = $request->input();
        $model = AdminRole::find($id);
        $res = $model->update($receive);
        if ($receive['permissions']){
            $model->permissions()->sync($receive['permissions']);
        }
        if ($res) {
            return ['error_code' => 0, 'error_message' => '更新成功'];
        }else {
            return ['error_code' => 1, 'error_message' => '更新失败'];
        }
    }

    /**
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        $res = AdminRole::destroy($id);
        if ($res){
            return ['error_code'=> 0, 'error_message'=>'删除成功'];
        }else {
            return ['error_code'=> 1, 'error_message'=>'删除失败'];
        }

    }
}
