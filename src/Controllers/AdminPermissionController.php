<?php

namespace Zjalen\Leadmin\Controllers;

use Zjalen\Leadmin\Auth\Models\AdminPermission;

use Zjalen\Leadmin\Requests\AdminPermissionRequest;
use Illuminate\Routing\Controller;

class AdminPermissionController extends Controller
{

    /**
     * Index interface.
     * @param AdminPermissionRequest $request
     *
     * @return mixed
     */
    public function index(AdminPermissionRequest $request)
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
            'title'=> '权限管理',
            'description'=> '细分权限管理',
            'headers'=>[
                //['title'=> 'id','name'=> 'id',],
                    ['title'=> '名称','name'=> 'name',],
                    ['title'=> '标识','name'=> 'slug',],
                    ['title'=> '请求方法','name'=> 'http_method','multiselect'=> true],
                    ['title'=> '请求路由','name'=> 'http_path',],

            ],
            'header_actions'=>[
                ['action'=>'create', 'type'=> 'primary', 'text'=> '添加', 'icon'=> 'fa-plus'],
                ['action'=>'filter', 'type'=> 'danger', 'text'=> '筛选', 'icon'=> 'fa-search'],
                //['action'=>'export', 'type'=> 'success', 'text'=> '导出', 'icon'=> 'fa-table'],
            ],
            'body'=>[],
            'actions'=>[
                ['action'=>'edit', 'type'=> 'primary', 'text'=> '编辑', 'icon'=> 'fa-edit'],
                ['action'=>'delete', 'type'=> 'danger', 'text'=> '删除', 'icon'=> 'fa-trash'],
            ],
            'count'=> 0,
        ];
        if (count($conditions) > 0){
            $data['body'] = AdminPermission::where(function ($query) use ($conditions){
                foreach ($conditions as $key => $condition) {
                    if (strstr( $key, '%')){
                        $key = trim($key, '%');
                        $query->where($key, 'like', '%'.$condition.'%');
                    }else {
                        $query->where($key, $condition);
                    }
                }
            })->orderBy($sort['column'], $sort['type'])->limit($limit)->skip($skip)->get()->toArray();
            $data['count'] = AdminPermission::where(function ($query) use ($conditions){
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
            $data['body'] = AdminPermission::orderBy($sort['column'], $sort['type'])->limit($limit)->skip($skip)->get()->toArray();
            $data['count'] = AdminPermission::all()->count();
        }
        $filters = [
            'headers'=>[
                    ['title'=> '名称','name'=> 'name',],
                    ['title'=> '标识','name'=> 'slug',],
                    ['title'=> '请求方法','name'=> '%http_method%',],
                    ['title'=> '请求路由','name'=> 'http_path',],

            ],
            'body'=>[
                    'name'=> array_key_exists('name', $conditions) ? $conditions['name']: null ,
                    'slug'=> array_key_exists('slug', $conditions) ? $conditions['slug']: null ,
                    'http_method'=> array_key_exists('http_method', $conditions) ? $conditions['http_method']: null ,
                    'http_path'=> array_key_exists('http_path', $conditions) ? $conditions['http_path']: null ,

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
                    ['title'=> '名称','name'=> 'name',],
                    ['title'=> '标识','name'=> 'slug',],
                    ['title'=> '请求方法','name'=> 'http_method','multiselect'=> [['id'=>'GET','name'=>'GET'],['id'=>'POST','name'=>'POST'],['id'=>'PUT','name'=>'PUT'],['id'=>'DELETE','name'=>'DELETE'],['id'=>'PATCH','name'=>'PATCH'], ['id'=>'OPTIONS','name'=>'OPTIONS'], ['id'=>'HEAD','name'=>'HEAD']]],
                    ['title'=> '请求路由','name'=> 'http_path',],

            ],
            'body'=>[
                    'name'=> null,
                    'slug'=> null,
                    'http_method'=> [],
                    'http_path'=> null,

            ],
        ];
        return view('leadmin.commons.create_and_edit',['data'=> json_encode($data)]);
    }

    /**
     * @param AdminPermissionRequest $request
     * @return array
     */
    public function store(AdminPermissionRequest $request)
    {
        $receive = $request->input();
        $model = new AdminPermission;
        $model->fill($receive);
        $res = $model->save();

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
        $model = AdminPermission::find($id)->toArray();
        $data = [
            'title'=> '编辑',
            'description'=> '编辑管理',
            'headers'=>[
                    ['title'=> '名称','name'=> 'name',],
                    ['title'=> '标识','name'=> 'slug',],
                    ['title'=> '请求方法','name'=> 'http_method','multiselect'=> [['id'=>'GET','name'=>'GET'],['id'=>'POST','name'=>'POST'],['id'=>'PUT','name'=>'PUT'],['id'=>'DELETE','name'=>'DELETE'],['id'=>'PATCH','name'=>'PATCH'], ['id'=>'OPTIONS','name'=>'OPTIONS'], ['id'=>'HEAD','name'=>'HEAD']]],
                    ['title'=> '请求路由','name'=> 'http_path',],

            ],
            'body'=>$model,
        ];
        return view('leadmin.commons.create_and_edit',['data'=> json_encode($data)]);
    }

    /**
     * @param AdminPermissionRequest $request
     * @param $id
     * @return array
     */
    public function update(AdminPermissionRequest $request, $id)
    {
        $receive = $request->input();
        $model = AdminPermission::find($id);
        $res = $model->update($receive);
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
        $res = AdminPermission::destroy($id);
        if ($res){
            return ['error_code'=> 0, 'error_message'=>'删除成功'];
        }else {
            return ['error_code'=> 1, 'error_message'=>'删除失败'];
        }

    }
}
