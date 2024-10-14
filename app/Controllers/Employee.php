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
        //logo
        $logoModel = new \App\Models\logoModel();
        $logo = $logoModel->first();
        //application
        $appModel = new \App\Models\appModel();
        $about = $appModel->first();

        $data = ['logo'=>$logo,'about'=>$about];
        return view('employee-portal',$data);
    }

    public function overview()
    {
        //logo
        $logoModel = new \App\Models\logoModel();
        $logo = $logoModel->first();
        //application
        $appModel = new \App\Models\appModel();
        $about = $appModel->first();

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
        //notification
        $builder = $this->db->table('tblapproval_leave');
        $builder->select('COUNT(*)total');
        $builder->WHERE('Status',0)->WHERE('employeeID',session()->get('employeeUser'));
        $notification = $builder->get()->getResult();

        $data = ['memo'=>$memo,'employee'=>$employee,'celebrants'=>$celebrants,
                'vacation'=>$vacation,'sick'=>$sick,'concern'=>$concern,
                'leave'=>$leave,'notification'=>$notification,'logo'=>$logo,'about'=>$about];
        return view('Employee/index',$data);
    }

    public function applyLeave()
    {
        //logo
        $logoModel = new \App\Models\logoModel();
        $logo = $logoModel->first();
        //list of managers
        $employeeModel = new \App\Models\employeeModel();
        $employee = $employeeModel->WHERE('SalaryGrade','Managerial')->WHERE('employeeID <>',session()->get('employeeUser'))->findAll();
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();
        //notification
        $builder = $this->db->table('tblapproval_leave');
        $builder->select('COUNT(*)total');
        $builder->WHERE('Status',0)->WHERE('employeeID',session()->get('employeeUser'));
        $notification = $builder->get()->getResult();
        //employee information
        $employeeModel = new \App\Models\employeeModel();
        $account = $employeeModel->WHERE('employeeID',session()->get('employeeUser'))->first();

        $data = ['celebrants'=>$celebrants,'employee'=>$employee,'notification'=>$notification,'account'=>$account,'logo'=>$logo];
        return view('Employee/apply-leave',$data);
    }

    public function sendLeave()
    {
        date_default_timezone_set('Asia/Manila');
        $employeeLeaveModel = new \App\Models\employeeLeaveModel();
        $approveLeaveModel = new \App\Models\approveLeaveModel();
        $leaveModel = new \App\Models\leaveModel();
        //data
        $leave = $this->request->getPost('leave');
        $user = session()->get('employeeUser');
        $date = date('Y-m-d');
        $from  = $this->request->getPost('fromdate');
        $to  = $this->request->getPost('todate');
        $days = $this->request->getPost('days');
        $reason = $this->request->getPost('reason');
        $file = $this->request->getFile('file');
        $originalName = $file->getClientName();
        $approver = $this->request->getPost('approver');
        //add filter
        if(($leave=="Sick Leave"||$leave=="Bereavement Leave" || $leave=="Solo Parent") && $days>=2 && empty($originalName))
        {
            echo "Please attach the required document";
        }
        else
        {
            //check available credits
            $credit = $leaveModel->WHERE('employeeID',$user)->first();
            if($leave=="Vacation Leave" && $credit['Vacation']==0)
            {
                echo "No more available credits left";
            }
            else if($leave=="Sick Leave" && $credit['Sick']==0)
            {
                echo "No more available credits left";
            }
            else
            {
                if(($leave=="Sick Leave"||$leave=="Bereavement Leave" || $leave=="Solo Parent") && $days>=2)
                {
                    $file->move('Attachment/',$originalName);
                    //save the information
                    $values = ['Date'=>$date,'employeeID'=>$user,'leave_type'=>$leave,
                    'From'=>$from,'To'=>$to,'Days'=>$days,'Details'=>$reason,'Status'=>0,'Attachment'=>$originalName];
                    $employeeLeaveModel->save($values);
                }
                else
                {
                    //save the information
                    $values = ['Date'=>$date,'employeeID'=>$user,'leave_type'=>$leave,
                    'From'=>$from,'To'=>$to,'Days'=>$days,'Details'=>$reason,'Status'=>0,'Attachment'=>'N/A'];
                    $employeeLeaveModel->save($values);
                }
                //send to approver
                $leaveInfo = $employeeLeaveModel->WHERE('employeeID',$user)
                            ->WHERE('From',$from)->WHERE('To',$to)
                            ->WHERE('leave_type',$leave)->first();
                $values = ['employeeID'=>$approver,'leaveID'=>$leaveInfo['leaveID'],'DateReceived'=>$date,'Status'=>0,'DateApproved'=>'','Remarks'=>''];
                $approveLeaveModel->save($values);
                echo "success";
            }
        }
    }

    public function authorization()
    {
        //logo
        $logoModel = new \App\Models\logoModel();
        $logo = $logoModel->first();
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();
        //notification
        $builder = $this->db->table('tblapproval_leave');
        $builder->select('COUNT(*)total');
        $builder->WHERE('Status',0)->WHERE('employeeID',session()->get('employeeUser'));
        $notification = $builder->get()->getResult();
        //list of leave
        $builder = $this->db->table('tblapproval_leave a');
        $builder->select('a.Status,b.leaveID,b.leave_type,b.Details,b.Date,c.Surname,c.Firstname,c.MI,c.Suffix');
        $builder->join('tblemployee_leave b','b.leaveID=a.leaveID','LEFT');
        $builder->join('tblemployee c','c.employeeID=b.employeeID','LEFT');
        $builder->WHERE('a.employeeID',session()->get('employeeUser'));
        $builder->groupBy('a.approveID')->orderBy('a.approveID','DESC');
        $list = $builder->get()->getResult();

        $data = ['celebrants'=>$celebrants,'notification'=>$notification,'list'=>$list,'logo'=>$logo];
        return view('Employee/leave-approval',$data);
    }

    public function searchRequest()
    {
        $val = "%".$this->request->getGet('value')."%";
        $builder = $this->db->table('tblapproval_leave a');
        $builder->select('a.Status,b.leaveID,b.leave_type,b.Details,b.Date,c.Surname,c.Firstname,c.MI,c.Suffix');
        $builder->join('tblemployee_leave b','b.leaveID=a.leaveID','LEFT');
        $builder->join('tblemployee c','c.employeeID=b.employeeID','LEFT');
        $builder->WHERE('a.employeeID',session()->get('employeeUser'));
        $builder->LIKE('b.leave_type',$val);
        $builder->groupBy('a.approveID')->orderBy('a.approveID','DESC');
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <tr>
                <td class="ps-9 w-50px">
                    <div class="form-check form-check-sm form-check-custom form-check-solid mt-3">
                        <input class="form-check-input" type="checkbox" name="leave[]" value="<?php echo $row->leaveID ?>"/>
                    </div>
                </td>
                <td class="w-120px">
                    <a href="<?=site_url('Employee/reply/')?><?php echo $row->leaveID ?>" class="d-flex align-items-center text-gray-900">
                    <?php echo $row->Surname ?> <?php echo $row->Suffix ?>, <?php echo $row->Firstname ?> <?php echo $row->MI ?>
                    </a>
                </td>
                <td class="w-120px">
                    <a href="<?=site_url('Employee/reply/')?><?php echo $row->leaveID ?>" class="d-flex align-items-center text-gray-900">
                    <span class="fw-bold"><?php echo $row->leave_type ?></span>
                    </a>
                </td>
                <td class="w-600px">
                    <a href="<?=site_url('Employee/reply/')?><?php echo $row->leaveID ?>" class="d-flex align-items-center text-gray-900">
                    <?php echo substr($row->Details,0,50) ?>...
                    </a>
                </td>
                <td class="w-125px">
                    <?php if($row->Status==0){ ?>
                        <span class="badge bg-warning text-white">PENDING</span>
                    <?php }else if($row->Status==1){?>
                        <span class="badge bg-primary text-white">APPROVED</span>
                    <?php }else { ?>
                        <span class="badge bg-danger text-white">DECLINED</span>
                    <?php } ?>
                </td>
                <td class="w-125px"><?php echo date('d M, Y', strtotime($row->Date)) ?></td>
            </tr>
            <?php
        }
    }

    public function reply($id)
    {
        //logo
        $logoModel = new \App\Models\logoModel();
        $logo = $logoModel->first();
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();
        //notification
        $builder = $this->db->table('tblapproval_leave');
        $builder->select('COUNT(*)total');
        $builder->WHERE('Status',0)->WHERE('employeeID',session()->get('employeeUser'));
        $notification = $builder->get()->getResult();
        //list of leave
        $builder = $this->db->table('tblemployee_leave a');
        $builder->select('a.Status,a.leaveID,a.leave_type,a.From,a.To,a.Days,a.Details,a.Date,a.Attachment,b.Surname,b.Firstname,b.MI,b.Suffix');
        $builder->join('tblemployee b','b.employeeID=a.employeeID','LEFT');
        $builder->WHERE('a.leaveID',$id)->groupBy('a.leaveID');
        $list = $builder->get()->getResult();
        //comment
        $approveLeaveModel = new \App\Models\approveLeaveModel();
        $approve = $approveLeaveModel->WHERE('leaveID',$id)->WHERE('employeeID',session()->get('employeeUser'))->first();

        $data = ['celebrants'=>$celebrants,'notification'=>$notification,'list'=>$list,'approve'=>$approve,'logo'=>$logo];
        return view('Employee/reply',$data);
    }

    public function approveLeave()
    {
        date_default_timezone_set('Asia/Manila');
        $employeeLeaveModel = new \App\Models\employeeLeaveModel();
        $approveLeaveModel = new \App\Models\approveLeaveModel();
        $leaveModel = new \App\Models\leaveModel();
        //data
        $val = $this->request->getPost('value');
        //update the leave form
        $values = ['Status'=>1];
        $employeeLeaveModel->update($val,$values);
        //deduct the credits
        $leave = $employeeLeaveModel->WHERE('leaveID',$val)->first();
        if($leave['leave_type']=="Vacation Leave")
        {
            //deduct the vacation leave credit
            $credit = $leaveModel->WHERE('employeeID',session()->get('employeeUser'))->first();
            $remainCredit = $credit['Vacation']-$leave['Days'];
            $new_values = ['Vacation'=>$remainCredit];
            $leaveModel->update($credit['creditID'],$new_values);
        }
        else if($leave['leave_type']=="Leave without pay"||$leave['leave_type']=="Paternity Leave" || 
                $leave['leave_type']=="Maternity Leave" || $leave['leave_type']=="Special Leave"|| 
                $leave['leave_type']=="Solo Parent")
        {
            //do nothing
        }
        else if($leave['leave_type']=="Sick Leave")
        {
            //deduct the sick leave credit
            $credit = $leaveModel->WHERE('employeeID',session()->get('employeeUser'))->first();
            $remainCredit = $credit['Sick']-$leave['Days'];
            $new_values = ['Sick'=>$remainCredit];
            $leaveModel->update($credit['creditID'],$new_values);
        }
        else if($leave['leave_type']=="Bereavement Leave")
        {
            if($leave['Days']>3)
            {
                $credit = $leaveModel->WHERE('employeeID',session()->get('employeeUser'))->first();
                $remainCredit = $credit['Vacation']-($leave['Days']-3);
                $new_values = ['Vacation'=>$remainCredit];
                $leaveModel->update($credit['creditID'],$new_values);
            }
        }
        //update the approver status
        $approver = $approveLeaveModel->WHERE('leaveID',$val)->WHERE('employeeID',session()->get('employeeUser'))->first();
        $records = ['Status'=>1,'DateApproved'=>date('Y-m-d')];
        $approveLeaveModel->update($approver['approveID'],$records);
        echo "success";
    }

    public function rejectLeave()
    {
        date_default_timezone_set('Asia/Manila');
        $employeeLeaveModel = new \App\Models\employeeLeaveModel();
        $approveLeaveModel = new \App\Models\approveLeaveModel();
        $leaveModel = new \App\Models\leaveModel();
        //data
        $val = $this->request->getPost('value');
        $msg = $this->request->getPost('message');
        if(empty($msg))
        {
            echo "Please leave a comment to continue";
        }
        else
        {
            //update the leave form
            $values = ['Status'=>2];
            $employeeLeaveModel->update($val,$values);
            //update the approver status
            $approver = $approveLeaveModel->WHERE('leaveID',$val)->WHERE('employeeID',session()->get('employeeUser'))->first();
            $records = ['Status'=>2,'Remarks'=>$msg];
            $approveLeaveModel->update($approver['approveID'],$records);
            echo "success";
        }
    }

    public function memo()
    {
        //logo
        $logoModel = new \App\Models\logoModel();
        $logo = $logoModel->first();
        //memo
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
        //notification
        $builder = $this->db->table('tblapproval_leave');
        $builder->select('COUNT(*)total');
        $builder->WHERE('Status',0)->WHERE('employeeID',session()->get('employeeUser'));
        $notification = $builder->get()->getResult();

        $data = ['celebrants'=>$celebrants,'page'=>$page,
        'perPage'=>$perpage,'total'=>$total,'list'=>$list,'pager'=>$pager
        ,'notification'=>$notification,'logo'=>$logo];
        return view('Employee/memo',$data);
    }

    public function searchMemo()
    {
        //logo
        $logoModel = new \App\Models\logoModel();
        $logo = $logoModel->first();

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
        //notification
        $builder = $this->db->table('tblapproval_leave');
        $builder->select('COUNT(*)total');
        $builder->WHERE('Status',0)->WHERE('employeeID',session()->get('employeeUser'));
        $notification = $builder->get()->getResult();

        $data = ['celebrants'=>$celebrants,'page'=>$page,'perPage'=>$perpage,
        'total'=>$total,'list'=>$list,'pager'=>$pager,'notification'=>$notification,'logo'=>$logo];
        return view('Employee/memo',$data);
    }

    public function writeConcern()
    {
        //logo
        $logoModel = new \App\Models\logoModel();
        $logo = $logoModel->first();
        //memo
        $concernModel = new \App\Models\concernModel();
        $concern = $concernModel->findAll();
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();
        //notification
        $builder = $this->db->table('tblapproval_leave');
        $builder->select('COUNT(*)total');
        $builder->WHERE('Status',0)->WHERE('employeeID',session()->get('employeeUser'));
        $notification = $builder->get()->getResult();

        $data = ['celebrants'=>$celebrants,'concern'=>$concern,'notification'=>$notification,'logo'=>$logo];
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
        //logo
        $logoModel = new \App\Models\logoModel();
        $logo = $logoModel->first();
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
        //notification
        $builder = $this->db->table('tblapproval_leave');
        $builder->select('COUNT(*)total');
        $builder->WHERE('Status',0)->WHERE('employeeID',session()->get('employeeUser'));
        $notification = $builder->get()->getResult();

        $data = ['celebrants'=>$celebrants,'list'=>$list,'notification'=>$notification,'logo'=>$logo];
        return view('Employee/concerns',$data);
    }

    public function evaluate()
    {
        //logo
        $logoModel = new \App\Models\logoModel();
        $logo = $logoModel->first();
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
        //notification
        $builder = $this->db->table('tblapproval_leave');
        $builder->select('COUNT(*)total');
        $builder->WHERE('Status',0)->WHERE('employeeID',session()->get('employeeUser'));
        $notification = $builder->get()->getResult();

        $data = ['celebrants'=>$celebrants,'evaluation'=>$evaluation,'notification'=>$notification,'logo'=>$logo];
        return view('Employee/evaluation',$data);
    }

    public function account()
    {
        //logo
        $logoModel = new \App\Models\logoModel();
        $logo = $logoModel->first();
        //account
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
        //notification
        $builder = $this->db->table('tblapproval_leave');
        $builder->select('COUNT(*)total');
        $builder->WHERE('Status',0)->WHERE('employeeID',session()->get('employeeUser'));
        $notification = $builder->get()->getResult();

        $data = ['employee'=>$employee,'celebrants'=>$celebrants,'work'=>$work,'notification'=>$notification,'logo'=>$logo];
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
