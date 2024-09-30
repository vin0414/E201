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
        date_default_timezone_set('Asia/Manila');
        $year = date('Y');
        $employeeModel = new \App\Models\employeeModel();
        $employee = $employeeModel->WHERE('employeeID',session()->get('employeeUser'))->first();
        //concern
        $builder = $this->db->table('tblemployee_concern');
        $builder->select('COUNT(*)total');
        $builder->WHERE('employeeID',session()->get('employeeUser'));
        $concern = $builder->get()->getResult();
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
        //leave credits
        //vacation
        $builder = $this->db->table('tblcredit');
        $builder->select('Vacation');
        $builder->WHERE('employeeID',session()->get('employeeUser'))->WHERE('Year',$year);
        $vacation = $builder->get()->getResult();
        //sick
        $builder = $this->db->table('tblcredit');
        $builder->select('Sick');
        $builder->WHERE('employeeID',session()->get('employeeUser'))->WHERE('Year',$year);
        $sick = $builder->get()->getResult();
        //leave
        $leaveModel = new \App\Models\employeeLeaveModel();
        $leave = $leaveModel->WHERE('employeeID',session()->get('employeeUser'))->findAll();

        $data = ['memo'=>$memo,'employee'=>$employee,'celebrants'=>$celebrants,
                'vacation'=>$vacation,'sick'=>$sick,'concern'=>$concern,'leave'=>$leave];
        return view('Employee/index',$data);
    }

    public function applyLeave()
    {
        //list of managers
        $employeeModel = new \App\Models\employeeModel();
        $employee = $employeeModel->WHERE('SalaryGrade','Managerial')->findAll();
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();

        $data = ['celebrants'=>$celebrants,'employee'=>$employee];
        return view('Employee/apply-leave',$data);
    }

    public function memo()
    {
        $memoModel = new \App\Models\memoModel();
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perpage = 8;
        $total = $memoModel->countAll();
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

    public function searchMemo()
    {
        $memoModel = new \App\Models\memoModel();
        $val = "%".$this->request->getGet('search')."%";
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perpage = 8;
        $total = $memoModel->countAll();
        $list = $memoModel->WHERE('Status',1)->LIKE('Subject',$val)->paginate($perpage);
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
        $concernModel = new \App\Models\concernModel();
        $concern = $concernModel->findAll();
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();

        $data = ['celebrants'=>$celebrants,'concern'=>$concern];
        return view('Employee/create',$data);
    }

    public function createConcern()
    {
        $employeeConcernModel = new \App\Models\employeeConcernModel();
        //data
        $title = $this->request->getPost('title');
        $details = $this->request->getPost('details');
        $date = date('Y-m-d');
        //validate
        $validation = $this->validate([
            'title'=>'required',
            'details'=>'required'
        ]);
        if(!$validation)
        {
            echo "Invalid! Please fill in the form to continue";
        }
        else
        {
            $values = ['Date'=>$date,'concernID'=>$title,'Details'=>$details,'employeeID'=>session()->get('employeeUser'),'Status'=>0];
            $employeeConcernModel->save($values);
            echo "success";
        }
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
        //concerns
        $builder = $this->db->table('tblemployee_concern  a');
        $builder->select('a.Date,b.Title,a.Details,a.Status');
        $builder->join('tblconcern b','b.concernID=a.concernID','LEFT');
        $builder->WHERE('a.employeeID',session()->get('employeeUser'));
        $list = $builder->get()->getResult();

        $data = ['celebrants'=>$celebrants,'list'=>$list];
        return view('Employee/concerns',$data);
    }

    public function evaluate()
    {
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();
        //load the evaluation
        $evaluationModel = new \App\Models\evaluationModel();
        $evaluation = $evaluationModel->WHERE('Status',1)->findAll();

        $data = ['celebrants'=>$celebrants,'evaluation'=>$evaluation];
        return view('Employee/evaluation',$data);
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
