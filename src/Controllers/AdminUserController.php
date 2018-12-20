<?php

namespace Zjalen\Leadmin\Controllers;

use Illuminate\Support\Facades\Storage;
use Zjalen\Leadmin\Auth\Models\AdminRole;
use Zjalen\Leadmin\Auth\Models\AdminUser;

use Zjalen\Leadmin\Requests\AdminUserRequest;
use Illuminate\Routing\Controller;
use Zjalen\Leadmin\Facades\Leadmin;

class AdminUserController extends Controller
{

    /**
     * Index interface.
     * @param AdminUserRequest $request
     *
     * @return mixed
     */
    public function index(AdminUserRequest $request)
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
            'title'=> '管理用户',
            'description'=> '后台登录用户的管理',
            'headers'=>[
              //['title'=> 'id','name'=> 'id'],
                ['title'=> '用户名','name'=> 'username'],
//                    ['title'=> '密码','name'=> 'password'],
//                    ['title'=> '记住密码','name'=> 'remember_token'],
                ['title'=> '名字','name'=> 'name'],
                ['title'=> '邮箱','name'=> 'email'],
                ['title'=> '头像','name'=> 'avatar', 'width'=> 80, 'image'=>true],
                ['title'=> '角色','name'=> 'roles', 'multiselect' => true],

            ],
            'header_actions'=>[
                ['action'=>'create', 'type'=> 'primary', 'text'=> '添加', 'icon'=> 'fa-plus'],
                ['action'=>'filter', 'type'=> 'danger', 'text'=> '筛选', 'icon'=> 'fa-search'],
                ['action'=>'export', 'type'=> 'success', 'text'=> '导出', 'icon'=> 'fa-table'],
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
            $data['body'] = AdminUser::where(function ($query) use ($conditions){
                foreach ($conditions as $key => $condition) {
                    if (strstr( $key, '%')){
                        $key = trim($key, '%');
                        $query->where($key, 'like', '%'.$condition.'%');
                    }else {
                        $query->where($key, $condition);
                    }
                }
            })->orderBy($sort['column'], $sort['type'])->limit($limit)->skip($skip)->with('roles')->get()->toArray();
            $data['count'] = AdminUser::where(function ($query) use ($conditions){
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
            $data['body'] = AdminUser::orderBy($sort['column'], $sort['type'])->limit($limit)->skip($skip)->with('roles')->get()->toArray();
            $data['count'] = AdminUser::all()->count();
        }
        foreach ($data['body'] as &$dt){
            if ($dt['avatar'])
                $dt['avatar'] = asset($dt['avatar']);
            else
                $dt['avatar'] = asset('vendor/leadmin/images/avatar-default.jpg');
        }
        $filters = [
            'headers'=>[
                    ['title'=> '用户名','name'=> '%username%'],
                    ['title'=> '密码','name'=> 'password'],
                    ['title'=> '记住密码','name'=> 'remember_token'],
                    ['title'=> '名字','name'=> 'name'],
                    ['title'=> '邮箱','name'=> 'email'],
                    ['title'=> '头像','name'=> 'avatar',],

            ],
            'body'=>[
                    'username'=> array_key_exists('username', $conditions) ? $conditions['username']: null ,
                    'password'=> array_key_exists('password', $conditions) ? $conditions['password']: null ,
                    'remember_token'=> array_key_exists('remember_token', $conditions) ? $conditions['remember_token']: null ,
                    'name'=> array_key_exists('name', $conditions) ? $conditions['name']: null ,
                    'email'=> array_key_exists('email', $conditions) ? $conditions['email']: null ,
                    'avatar'=> array_key_exists('avatar', $conditions) ? $conditions['avatar']: null ,

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
            'title'=> '用户',
            'description'=> '添加用户',
            'headers'=>[
                ['title'=> '用户名','name'=> 'username'],
                ['title'=> '密码','name'=> 'password'],
//                ['title'=> '记住密码','name'=> 'remember_token'],
                ['title'=> '名字','name'=> 'name'],
                ['title'=> '邮箱','name'=> 'email'],
                ['title'=> '头像','name'=> 'avatar','upload'=>url('admin/file_upload')],
                ['title'=> '角色','name'=> 'roles', 'multiselect'=> AdminRole::all(['name','id'])->toArray()],

            ],
            'body'=>[
                'username'=> null,
                'password'=> null,
                'remember_token'=> null,
                'name'=> null,
                'email'=> null,
                'avatar'=> null,
                'roles'=> [],
            ],
        ];
        return view('leadmin.commons.create_and_edit',['data'=> json_encode($data)]);
    }

    /**
     * @param AdminUserRequest $request
     * @return array
     */
    public function store(AdminUserRequest $request)
    {
        $receive = $request->input();
        if (array_key_exists('password', $receive)){
            $receive['password'] = bcrypt($receive['password']);
        }
        $model = new AdminUser;
        $model->fill($receive);
        $res = $model->save();
        if ($receive['roles']){
            $model->roles()->sync($receive['roles']);
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
        $model = AdminUser::find($id);
        $roles = $model->roles->pluck('id')->toArray();
        $model = $model->toArray();
        $model['password'] = '';
        $model['roles'] = $roles;
        if ($model['avatar'])
            $model['avatar'] = asset($model['avatar']);
        else
            $model['avatar'] = null;
        $data = [
            'title'=> '添加',
            'description'=> '添加设备',
            'headers'=>[
                ['title'=> '用户名','name'=> 'username'],
                ['title'=> '密码','name'=> 'password'],
                ['title'=> '名字','name'=> 'name'],
                ['title'=> '邮箱','name'=> 'email'],
                ['title'=> '头像','name'=> 'avatar','upload'=>url('admin/file_upload')],
                ['title'=> '角色','name'=> 'roles', 'multiselect'=> AdminRole::all('name','id')->toArray()],
            ],
            'body'=>$model,
        ];
        $array = json_encode($data);
        return view('leadmin.commons.create_and_edit',['data'=> $array]);
    }

    /**
     * @param AdminUserRequest $request
     * @param $id
     * @return array
     */
    public function update(AdminUserRequest $request, $id)
    {
        $receive = $request->input();
        if (array_key_exists('password', $receive)){
            if ($receive['password'])
                $receive['password'] = bcrypt($receive['password']);
            else
                unset($receive['password']);
        }
        $model = AdminUser::find($id);
        if (array_key_exists('avatar', $receive)) {
            if ($model->avatar == $receive['avatar']) {
                unset($receive['avatar']);
            }
        }
        $res = $model->update($receive);
        if ($receive['roles']){
            $model->roles()->sync($receive['roles']);
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
        $res = AdminUser::destroy($id);
        if ($res){
            return ['error_code'=> 0, 'error_message'=>'删除成功'];
        }else {
            return ['error_code'=> 1, 'error_message'=>'删除失败'];
        }

    }

    public function fileUpload(AdminUserRequest $request)
    {
        if ($request->hasFile('image')) {
            $name = $request->input('name');
            $picture = $request->file('image');
            if (!$picture->isValid()) {
                abort(400, '无效的上传文件');
            }
            // 文件扩展名
            $extension = $picture->getClientOriginalExtension();
            // 文件名
            $fileName = $picture->getClientOriginalName();
            // 生成新的统一格式的文件名
            $newFileName = md5($fileName . time() . mt_rand(1, 10000)) . '.' . $extension;
            // 图片保存路径
            $savePath = 'images/' . $newFileName;
            // Web 访问路径
            $webPath = '/storage/' . $savePath;
            // 将文件保存到本地 storage/app/public/images 目录下，先判断同名文件是否已经存在，如果存在直接返回
            if (Storage::disk('public')->has($savePath)) {
                return response()->json(['name'=>$name,'path' => $webPath, 'url'=>asset($webPath)]);
            }
            // 否则执行保存操作，保存成功将访问路径返回给调用方
            if ($picture->storePubliclyAs('images', $newFileName, ['disk' => 'public'])) {
                return response()->json(['name'=>$name,'path' => $webPath, 'url'=>asset($webPath)]);
            }
            abort(500, '文件上传失败');
        } else {
            abort(400, '请选择要上传的文件');
        }
    }
}
