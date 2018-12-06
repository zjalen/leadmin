<?php
/**
 * Created by PhpStorm.
 * User: jialinzhang
 * Date: 2018/12/1
 * Time: 00:36
 */

namespace Zjalen\Leadmin\Controllers;


use Zjalen\Leadmin\Auth\Models\AdminMenu;

class HomeController
{
    public function index()
    {
        $model = new AdminMenu();
        $tree = $model->toTree();
        return view('leadmin.layouts.app', ['tree'=>$tree]);
    }

    public function welcome()
    {
        return view('welcome');
    }

}