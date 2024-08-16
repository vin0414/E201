<?php

namespace App\Controllers;

class Home extends BaseController
{
    //pages
    public function index()
    {
        return view('welcome_message');
    }

    public function Overview()
    {
        return view('HR/overview');
    }

    public function Employee()
    {
        return view('HR/employee-records');
    }

    public function Users()
    {
        $accountModel = new \App\Models\accountModel();
        $account = $accountModel->findAll();
        $data = ['account'=>$account];
        return view('HR/all-users',$data);
    }

    public function newUser()
    {
        return view('HR/new-user');
    }

    public function editUser($id)
    {
        return view('HR/edit-user');
    }

    public function Account()
    {
        return view('HR/account');
    }

}
