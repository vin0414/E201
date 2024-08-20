<?php

namespace App\Controllers;
use App\Libraries\Hash;
class Home extends BaseController
{
    private $db;
    public function __construct()
    {
        helper(['form']);
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

    public function saveEmployee()
    {
        $employeeModel = new \App\Models\employeeModel();
        //data
        $surname = $this->request->getPost('surname');
        $firstname = $this->request->getPost('firstname');
        $mi = $this->request->getPost('middlename');
        $suffix = $this->request->getPost('suffix');
        $maritalStatus = $this->request->getPost('maritalStatus');
        $dob = $this->request->getPost('dob');
        $place_of_birth = $this->request->getPost('place_of_birth');
        $address = $this->request->getPost('address');
        $date_hired = $this->request->getPost('date_hired');
        $designation = $this->request->getPost('designation');
        $salary_grade = $this->request->getPost('salary_grade');
        $employeeStatus = $this->request->getPost('employeeStatus');
        $fathersName = $this->request->getPost('fathersName');
        $mothersName = $this->request->getPost('mothersName');
        $spouseName = $this->request->getPost('spouseName');
        $spouseDOB = $this->request->getPost('spouseDOB');
        $children = $this->request->getPost('children');
        $education = $this->request->getPost('education');
        //photo
        $file="";$photo_name="";
        if(empty($this->request->getFile('file')))
        {
            $file = "N/A";$photo_name = "N/A";
        }
        else
        {
            $file =  $this->request->getFile('file');
            $photo_name = $file->getClientName();
        }
        //government
        $sss = $this->request->getPost('sss_number');
        $hdmf = $this->request->getPost('pagibig_number');
        $ph = $this->request->getPost('ph_number');
        $tin = $this->request->getPost('tin_number');
        //validate
        $validation = $this->validate([
            'surname'=>'required',
            'firstname'=>'required',
            'maritalStatus'=>'required',
            'dob'=>'required',
            'place_of_birth'=>'required',
            'date_hired'=>'required',
            'address'=>'required',
            'designation'=>'required',
            'salary_grade'=>'required',
            'employeeStatus'=>'required',
            'education'=>'required',
            'sss_number'=>'required|min_length[8]|max_length[16]',
            'pagibig_number'=>'required|min_length[8]|max_length[16]',
            'ph_number'=>'required|min_length[8]|max_length[16]',
            'tin_number'=>'required|min_length[8]|max_length[16]'
        ]);
        if(!$validation)
        {
            return view('HR/new-employee',['validation'=>$this->validator]);
        }
        else
        {

        }
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
