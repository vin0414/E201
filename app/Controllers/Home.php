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

    //employee
    public function Employee()
    {
        return view('HR/employee-records');
    }

    //memorandum
    public function Memo()
    {
        return view('HR/Memo/index');
    }

    public function Upload()
    {
        return view('HR/Memo/upload-memo');
    }

    //user accounts
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
