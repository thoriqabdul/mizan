<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Database\Eloquent\Model;


class UserController extends BaseController
{
    public function __construct()
    {
        $this->model = User::class;
        $this->prefix = "admin.publish";
        $this->routePath="publish.index";
    }   

    public function dataForIndex(): array
    {
        return array(
            'publis'=> User::where('role_id',2)->get()
        );
    }

    protected function beforeUpdate(Model $model, Request $request)
    {
        parent::beforeUpdate($model, $request); // TODO: Change the autogenerated stub
        if($request->has('password'))
        {
            $this->data['password'] =bcrypt($request->password);
        }
    }

    protected function beforeSave(Request $request)
    {
        parent::beforeSave($request); // TODO: Change the autogenerated stub
        $this->data['password'] =bcrypt($request->password);
    }

    protected function validateFormBeforeSave(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'name'=>'required|string|max:255',
            'password'=>'required|string|max:16'
        ]);
    }

    protected function validateFormBeforeUpdate(Request $request)
    {
        if($request->email_old!=$request->email){
            $this->validate($request, [
                'role_id' => 'required|exists:roles,id',
                'email' => 'required|email|unique:users,email',
                'name'=>'required|string|max:255'
            ]);
        }else{
            $this->validate($request, [
                'role_id' => 'required|exists:roles,id',
                'name'=>'required|string|max:255'
            ]);
        }

    }

    protected function whiteListCreate()
    {
        return[
            'name',
            'role_id',
            'email',
            'password'
        ];
    }

    protected function whiteListUpdate()
    {
        return[
            'name',
            'role_id',
            'email',
            'password'
        ];
    }

}
