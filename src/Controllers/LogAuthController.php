<?php

namespace Zjalen\Leadmin\Controllers;

use Illuminate\Support\Facades\URL;
use Zjalen\Leadmin\Facades\Leadmin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class LogAuthController extends Controller
{
    /**
     * Show the login page.
     *
     * @return \Illuminate\Contracts\View\Factory|Redirect|\Illuminate\View\View
     */
    public function login()
    {
        if ($this->guard()->check()) {
            if (!$this->middleware){
                return view('leadmin.auth.login');
            }
            $path = $this->redirectPath();
            return redirect($path);
        }

        return view('leadmin.auth.login');
    }

    /**
     * Handle a login request.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function authCheck(Request $request)
    {
        $credentials = $request->only([$this->username(), 'password', 'captcha', 'remember']);

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($credentials, [
            $this->username()   => 'required',
            'password'          => 'required',
            'captcha' => 'required|captcha',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            if (in_array("validation.captcha", $errors)){
                return ['error_code'=> 1, 'error_message'=>'验证码有误'];
            }else {
                return ['error_code'=> 1, 'error_message'=>'账号或密码必填'];
            }
        }

        $remember = $credentials['remember'];

        unset($credentials['remember']);
        unset($credentials['captcha']);

        if ($this->guard()->attempt($credentials, $remember)) {
            $path = $request->session()->get('url.intended');
            return ['error_code'=> 0, 'url'=>$path ? $path : url('admin')];
        }

        return ['error_code'=> 1, 'error_message'=>'账号或密码有误'];

    }

    public function captcha()
    {
        return captcha_src();
    }

    /**
     * User logout.
     *
     * @return Redirect
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect(url('admin/auth/login'));
    }

    /**
     * User setting page.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function getSetting(Content $content)
    {
        $form = $this->settingForm();
        $form->tools(
            function (Form\Tools $tools) {
                $tools->disableList();
            }
        );

        return $content
            ->header(trans('admin.user_setting'))
            ->body($form->edit(Leadmin::user()->id));
    }

    /**
     * Update user setting.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putSetting()
    {
        return $this->settingForm()->update(Leadmin::user()->id);
    }

    /**
     * Model-form for user setting.
     *
     * @return Form
     */
    protected function settingForm()
    {
        $class = config('admin.database.users_model');

        $form = new Form(new $class());

        $form->display('username', trans('admin.username'));
        $form->text('name', trans('admin.name'))->rules('required');
        $form->image('avatar', trans('admin.avatar'));
        $form->password('password', trans('admin.password'))->rules('confirmed|required');
        $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });

        $form->setAction(admin_base_path('auth/setting'));

        $form->ignore(['password_confirmation']);

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            }
        });

        $form->saved(function () {
            admin_toastr(trans('admin.update_succeeded'));

            return redirect(admin_base_path('auth/setting'));
        });

        return $form;
    }

    /**
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    protected function getFailedLoginMessage()
    {
        return Lang::has('auth.failed')
            ? trans('auth.failed')
            : 'These credentials do not match our records.';
    }

    /**
     * Get the post login redirect path.
     *
     * @return string
     */
    protected function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : 'admin';
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {

        $path = $request->session()->get('url.intended');

        return redirect()->intended($path);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    protected function username()
    {
        return 'username';
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('leadmin');
    }
}
