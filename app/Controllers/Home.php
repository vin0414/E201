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
        $employeeModel = new \App\Models\employeeModel();
        $employee = $employeeModel->findAll();
        $data = ['employee'=>$employee];
        return view('HR/employee-records',$data);
    }

    public function newEmployee()
    {
        return view('HR/new-employee');
    }

    public function editEmployee($id)
    {
        $employeeModel = new \App\Models\employeeModel();
        $employee = $employeeModel->WHERE('Token',$id)->first();
        $data = ['employee'=>$employee];
        return view('HR/edit-employee',$data);
    }

    public function viewEmployee($id)
    {
        $employeeModel = new \App\Models\employeeModel();
        $employee = $employeeModel->WHERE('Token',$id)->first();
        $data = ['employee'=>$employee];
        return view('HR/view-employee',$data);
    }

    public function saveEmployee()
    {
        date_default_timezone_set('Asia/Manila');
        $employeeModel = new \App\Models\employeeModel();
        $logModel = new \App\Models\logModel();
        //data
        $token = $this->request->getPost('csrf_test_name');
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
        $file = $this->request->getFile('file');
        $originalName = $file->getClientName();
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
            //save the employee records
            $values =  ['DateCreated'=>date('Y-m-d'),'Surname'=>$surname,'Firstname'=>$firstname,'MI'=>$mi,'Suffix'=>$suffix,
                        'BirthDate'=>$dob,'MaritalStatus'=>$maritalStatus,'PlaceOfBirth'=>$place_of_birth,
                        'Address'=>$address,'DateHired'=>$date_hired,'Designation'=>$designation,'EmployeeStatus'=>$employeeStatus,
                        'SalaryGrade'=>$salary_grade,'Guardian1'=>$fathersName,'Guardian2'=>$mothersName,
                        'Spouse'=>$spouseName,'SpouseDOB'=>$spouseDOB,'Children'=>$children,
                        'Education'=>$education,'SSS'=>$sss,'HDMF'=>$hdmf,'PhilHealth'=>$ph,'TIN'=>$tin,
                        'Photo'=>$originalName,'Status'=>1,'Token'=>$token];
            $employeeModel->save($values);
            //moved the profile pic to profile folder
            if(!empty($originalName))
            {
                $file->move('Profile/',$originalName);
            }
            //logs
            $value = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Added new Employee'];
            $logModel->save($value);
            session()->setFlashdata('success','Great! Successfully added');
            return redirect()->to('HR/employee')->withInput();
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
        $total = $accountModel->countAll();
        $data = ['account'=>$account,'total'=>$total];
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
            'username'=>'required|min_length[8]|max_length[16]|is_unique[tblaccount.Username]',
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

    public function searchAccount()
    {
        $search = "%".$this->request->getGet('search')."%";
        $builder = $this->db->table('tblaccount');
        $builder->select('*');
        $builder->LIKE('Fullname',$search);
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <tr>
                <td><?php echo $row->Fullname ?></td>
                <td><?php echo $row->Username ?></td>
                <td><?php echo $row->Designation ?></td>
                <td><?php echo $row->Role ?></td>
                <td class="text-center">
                    <?php 
                    if($row->Status==1){ ?>
                    <span class="badge bg-primary text-white">Active</span>
                    <?php } else { ?>
                    <span class="badge bg-danger text-white">Inactive</span>
                    <?php } ?>
                </td>
                <td class="text-center">
                    <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        Actions&nbsp;<i class="fa-solid fa-circle-chevron-down"></i>                   
                    </a>
                    <!--begin::Menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="<?=site_url('HR/edit/')?><?php echo $row->Token ?>" class="menu-link px-3">
                                Edit
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="javascript:void(0);" class="menu-link btn-outline-default px-3 reset">
                                Reset
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::Menu-->
                </td>
            </tr>
            <?php
        }
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
