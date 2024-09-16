<?php

namespace App\Controllers;
use App\Libraries\Hash;

class Employee extends BaseController
{
    private $db;
    public function __construct()
    {
        helper(['url','form']);
        $this->db = db_connect();
    }

    public function employeeIndex()
    {
        return view('employee-portal');
    }

    public function overview()
    {
        $employeeModel = new \App\Models\employeeModel();
        $employee = $employeeModel->WHERE('employeeID',session()->get('employeeUser'))->first();
        //memo
        $builder = $this->db->table('tblmemo');
        $builder->select('File,Subject,Date');
        $builder->orderby('memoID','DESC')->limit(3);
        $memo = $builder->get()->getResult();
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();

        $data = ['memo'=>$memo,'employee'=>$employee,'celebrants'=>$celebrants];
        return view('Employee/index',$data);
    }

    public function memo()
    {
        $memoModel = new \App\Models\memoModel();
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perpage = 8;
        $total = $memoModel->WHERE('Status',1)->countAll();
        $list = $memoModel->WHERE('Status',1)->paginate($perpage);
        $pager = $memoModel->WHERE('Status',1)->pager;

        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();

        $data = ['celebrants'=>$celebrants,'page'=>$page,'perPage'=>$perpage,'total'=>$total,'list'=>$list,'pager'=>$pager];
        return view('Employee/memo',$data);
    }

    public function writeConcern()
    {
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();

        $data = ['celebrants'=>$celebrants,];
        return view('Employee/create',$data);
    }

    public function concerns()
    {
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();

        $data = ['celebrants'=>$celebrants,];
        return view('Employee/concerns',$data);
    }

    public function request()
    {
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();

        $data = ['celebrants'=>$celebrants,];
        return view('Employee/request',$data);
    }

    public function account()
    {
        $employeeModel = new \App\Models\employeeModel();
        $employee = $employeeModel->WHERE('employeeID',session()->get('employeeUser'))->first();
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();
        //history
        $workHistoryModel = new \App\Models\workHistoryModel();
        $work = $workHistoryModel->WHERE('employeeID',session()->get('employeeUser'))->findAll();

        $data = ['employee'=>$employee,'celebrants'=>$celebrants,'work'=>$work];
        return view('Employee/account',$data);
    }


    //functions
    public function resolve()
    {
        $employeeConcernModel = new \App\Models\employeeConcernModel();
        $val = $this->request->getPost('value');
        $values = ['Status'=>1];
        $employeeConcernModel->update($val,$values);
        echo "success";
    }

    public function denied()
    {
        $employeeConcernModel = new \App\Models\employeeConcernModel();
        $val = $this->request->getPost('value');
        $values = ['Status'=>2];
        $employeeConcernModel->update($val,$values);
        echo "success";
    }

    public function changePIN()
    {
        $employeeModel = new \App\Models\employeeModel();
        //data
        $user = session()->get('employeeUser');
        $pin = $this->request->getPost('current_pin');
        $new_pin = $this->request->getPost('new_pin');
        $confirm_pin = $this->request->getPost('confirm_pin');

        $validation = $this->validate([
            'new_pin'=>'is_unique[tblemployee.PIN]',
        ]);
        if(!$validation)
        {
            session()->setFlashdata('fail','Please create new PIN');
            return redirect()->to('Employee/account')->withInput();
        }
        else
        {
            $employee = $employeeModel->WHERE('employeeID',$user)->WHERE('PIN',$pin)->first();
            if(empty($employee['employeeID']))
            {
                session()->setFlashdata('fail','Invalid PIN');
                return redirect()->to('Employee/account')->withInput();
            }
            else
            {
                if($new_pin!=$confirm_pin)
                {
                    session()->setFlashdata('fail','PIN mismatched. Try again');
                    return redirect()->to('Employee/account')->withInput();
                }
                else
                {
                    $values = ['PIN'=>$new_pin];
                    $employeeModel->update($user,$values);
                    session()->setFlashdata('success','Great! Successfully applied changes');
                    return redirect()->to('Employee/account')->withInput();
                }
            }
        }
    }
}
