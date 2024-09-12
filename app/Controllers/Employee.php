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
        $data = ['memo'=>$memo,'employee'=>$employee];
        return view('Employee/index',$data);
    }

    public function account()
    {
        return view('Employee/account');
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
