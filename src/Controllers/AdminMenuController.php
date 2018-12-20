<?php

namespace Zjalen\Leadmin\Controllers;

use Zjalen\Leadmin\Auth\Models\AdminMenu;

use Zjalen\Leadmin\Auth\Models\AdminRole;
use Zjalen\Leadmin\Requests\AdminMenuRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminMenuController extends Controller
{

    /**
     * @param AdminMenuRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(AdminMenuRequest $request)
    {
        $parent = $request->input('parent');
        $menus = AdminMenu::all(['id','title']);
        $list = [];
        $selected = $parent ? (int)$parent: null;
        foreach ($menus as $menu) {
            $list[] = ['value' => $menu->id, 'label' => $menu->title];
        }
        $data = [
            'title'=> '添加',
            'description'=> '菜单添加',
            'headers'=>[
                ['title'=> '父级','name'=> 'parent_id', 'width'=> 100, 'select'=> $list ],
                ['title'=> '标题','name'=> 'title', 'width'=> 100],
                ['title'=> '图标','name'=> 'icon', 'width'=> 100],
                ['title'=> '链接','name'=> 'url', 'width'=> 100],

            ],
            'body'=>[
                'parent_id'=> $selected,
                'title'=> null,
                'icon'=> null,
                'url'=> null,

            ],
        ];
        return view('leadmin.commons.create_and_edit',['data'=> json_encode($data) ]);
    }

    /**
     * @param AdminMenuRequest $request
     * @return array
     */
    public function store(AdminMenuRequest $request)
    {
        $receive = $request->input();
        foreach ($receive as $key => $value){
            if ($value == null){
                $receive[$key] = 0;
            }
        }
        $model = new AdminMenu;
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
        $model = AdminMenu::find($id)->toArray();
        $menus = AdminMenu::all(['id','title']);
        $list = [];
        foreach ($menus as $menu) {
            if ((int)$id != (int)$menu->id)
                $list[] = ['value' => $menu->id, 'label' => $menu->title];
        }
        $model['parent_id'] = $model['parent_id'] != 0 ? $model['parent_id']:null;
        $data = [
            'title'=> '编辑',
            'description'=> '菜单编辑',
            'headers'=>[
                ['title'=> '父级','name'=> 'parent_id', 'width'=> 100, 'select'=> $list ],
                ['title'=> '排序','name'=> 'order', 'width'=> 100],
                ['title'=> '标题','name'=> 'title', 'width'=> 100],
                ['title'=> '图标','name'=> 'icon', 'width'=> 100],
                ['title'=> '链接','name'=> 'url', 'width'=> 100],
                ['title'=> '访问角色','name'=> 'roles', 'multiselect'=>AdminRole::all(['name','id'])->toArray()],

            ],
            'body'=>$model,
        ];
        return view('leadmin.commons.create_and_edit',['data'=> json_encode($data)]);
    }

    /**
     * @param AdminMenuRequest $request
     * @param $id
     * @return array
     */
    public function update(AdminMenuRequest $request, $id)
    {
        $receive = $request->input();
        foreach ($receive as $key => $value){
            if ($value == null){
                $receive[$key] = 0;
            }
        }
        $model = AdminMenu::find($id);
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
        $res = AdminMenu::destroy($id);
        if ($res){
            return ['error_code'=> 0, 'error_message'=>'删除成功'];
        }else {
            return ['error_code'=> 1, 'error_message'=>'删除失败'];
        }

    }

    public function index()
    {
        $model = new AdminMenu();
        $data = $model->toTree();
        $title = "菜单管理";
        $description = "后台菜单管理";

        $tree_data = [
            'data' => $data,
            'title' => $title,
            'description' => $description,
        ];

        return view('leadmin.commons.tree', ['tree_data'=>json_encode($tree_data)]);
    }

    public function saveMenus(Request $request)
    {
        $receive = $request->input();
        $this->saveOrder($receive, 'top');
    }

    public function saveOrder($array, $parent)
    {
        foreach ($array as $key => $value) {
            $model = AdminMenu::find($value['id']);
            $model->order = $key + 1;
            if ($parent == 'top'){
                $model->parent_id = 0;
            }else {
                $model->parent_id = $parent;
            }
            $model->save();
            if (array_key_exists('children', $value)){
                if (count($value['children']) > 0){
                    $this->saveOrder($value['children'], $value['id']);
                }
            }
        }
    }
}
