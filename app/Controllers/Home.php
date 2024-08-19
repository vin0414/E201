<?php

namespace App\Controllers;
use App\Libraries\Hash;
class Home extends BaseController
{
    private $db;
    public function __construct()
    {
        helper(['url','form']);
        $this->db = db_connect();
    }
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

    public function newEmployee()
    {
        return view('HR/new-employee');
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

    //function to save user data
    public function saveUserData()
    {
        $accountModel = new \App\Models\accountModel();
        $logModel = new \App\Models\logModel();
        //data
        $token = $this->request->getPost('csrf_test_name');
        $name = $this->request->getPost('name');
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $designation = $this->request->getPost('designation');
        $role = $this->request->getPost('role');
        //validate
        $validation = $this->validate([
            'name'=>'required',
            'username'=>'required|min_length[8]|max_length[16]',
            'password'=>'required|min_length[8]|max_length[16]',
            'designation'=>'required',
            'role'=>'required'
        ]);
        if(!$validation)
        {
            return view('HR/new-user',['validation'=>$this->validator]);
        }
        else
        {
            $HashPassword = Hash::make($password);
            $values = ['Username'=>$username,'Password'=>$HashPassword,'Fullname'=>$name,
                        'Designation'=>$designation,'Status'=>1,'Role'=>$role,'Token'=>$token];
            $accountModel->save($values);
            //logs
            date_default_timezone_set('Asia/Manila');
            $values = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Added account of '.$name];
            $logModel->save($values);

            session()->setFlashdata('success','Great! Successfully registered');
            return redirect()->to('HR/new-account')->withInput();
        }
    }

    public function editUser($id)
    {
        $accountModel = new \App\Models\accountModel();
        $account = $accountModel->WHERE('Token',$id)->first();
        $data = ['account'=>$account];
        return view('HR/edit-user',$data);
    }

    public function modifyAccount()
    {
        $accountModel = new \App\Models\accountModel();
        $logModel = new \App\Models\logModel();
        //data
        $id = $this->request->getPost('id');
        $name = $this->request->getPost('complete_name');
        $username = $this->request->getPost('username');
        $designation = $this->request->getPost('designation');
        $role = $this->request->getPost('role');
        $status = $this->request->getPost('status');
        //update
        $values = ['Username'=>$username,'Fullname'=>$name,'Designation'=>$designation,'Status'=>$status,'Role'=>$role,];
        $accountModel->update($id,$values);
        //logs
        date_default_timezone_set('Asia/Manila');
        $values = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Modify the account of '.$name];
        $logModel->save($values);
        session()->setFlashdata('success','Great! Successfully save changes');
        return redirect()->to('HR/users')->withInput();
    }

    //logs
    public function systemLogs()
    {
        $model = new \App\Models\logModel();
        $builder = $this->db->table('tblsystem_logs a');
        $builder->select('a.Date,a.Activity,b.Fullname');
        $builder->join('tblaccount b','b.accountID=a.accountID','LEFT');
        $builder->groupby('a.logID')->orderBy('a.logID','DESC')->limit(10);
        $logs = $builder->get()->getResult();
        //page
        $total = $model->countAll();
        $data = ['logs'=>$logs,'total'=>$total];
        return view('HR/system-logs',$data);
    }

    public function Account()
    {
        return view('HR/account');
    }

}
