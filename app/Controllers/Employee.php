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
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();

        $data = ['celebrants'=>$celebrants];
        return view('Employee/memo',$data);
    }

    public function writeConcern()
    {
        return view('Employee/create');
    }

    public function concerns()
    {
        return view('Employee/concerns');
    }

    public function account()
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
        //history
        $workHistoryModel = new \App\Models\workHistoryModel();
        $work = $workHistoryModel->WHERE('employeeID',session()->get('employeeUser'))->findAll();

        $data = ['memo'=>$memo,'employee'=>$employee,'celebrants'=>$celebrants,'work'=>$work];
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
}
