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
        //chart
        $builder = $this->db->table('tblemployee');
        $builder->select('DateCreated,COUNT(employeeID)total');
        $builder->groupBy('DateCreated');
        $query = $builder->get()->getResult();
        //recent employees
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname, MI, Suffix,Designation');
        $builder->orderBy('employeeID','DESC')->limit(5);
        $employee = $builder->get()->getResult();
        //regular
        $builder = $this->db->table('tblemployee');
        $builder->select('COUNT(*)total');
        $builder->WHERE('EmployeeStatus','Regular')->WHERE('Status',1);
        $regular = $builder->get()->getRow();
        //probationary
        $builder = $this->db->table('tblemployee');
        $builder->select('COUNT(*)total');
        $builder->WHERE('EmployeeStatus','Probationary')->WHERE('Status',1);
        $newlyHired = $builder->get()->getRow();
        //total
        $builder = $this->db->table('tblemployee');
        $builder->select('COUNT(*)total');
        $builder->WHERE('Status',1);
        $total = $builder->get()->getRow();
        //resigned
        $builder = $this->db->table('tblemployee');
        $builder->select('COUNT(*)total');
        $builder->WHERE('Status<>',1);
        $inactive = $builder->get()->getRow();
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();

        $data = ['employee'=>$employee,'regular'=>$regular,'probationary'=>$newlyHired,
        'total'=>$total,'inactive'=>$inactive,'query'=>$query,'celebrants'=>$celebrants];
        return view('HR/overview',$data);
    }

    //employee
    public function Employee()
    {
         //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();
        //employee
        $employeeModel = new \App\Models\employeeModel();
        $employee = $employeeModel->findAll();
        $data = ['employee'=>$employee,'celebrants'=>$celebrants];
        return view('HR/employee-records',$data);
    }

    public function activeEmployee()
    {
        $builder = $this->db->table('tblemployee a');
        $builder->select('a.*,IFNULL(b.Vacation,0)Vacation,IFNULL(b.Sick,0)Sick');
        $builder->join('tblcredit b','b.employeeID=a.employeeID','LEFT');
        $builder->WHERE('a.Status',1);
        $employee = $builder->get()->getResult();
        ?>
        <form method="POST" class="form w-100" id="frmLeave">
            <div class="fv-row mb-4">
                <table class="table table-bordered table-striped">
                    <thead>
                        <th class="text-white">#</th>
                        <th class="text-white">Employee ID</th>
                        <th class="text-white">Complete Name</th>
                        <th class="text-white w-100px">Vacation</th>
                        <th class="text-white w-100px">Sick</th>
                    </thead>
                    <tbody>
                <?php
                foreach($employee as $row):
                ?>
                <tr>
                    <td><input type="checkbox" style="height:15px;width:15px;" value="<?php echo $row->employeeID ?>" name="employeeID[]" id="employeeID" checked/></td>
                    <td><?php echo $row->CompanyID ?></td>
                    <td><?php echo $row->Firstname ?> <?php echo $row->MI ?> <?php echo $row->Surname ?> <?php echo $row->Suffix ?></td>
                    <td><input type='text' class='form-control' value="<?php echo $row->Vacation ?>" name='item_vacation[]'/></td>
                    <td><input type='text' class='form-control' value="<?php echo $row->Sick ?>" name='item_sick[]'/></td>
                </tr>
                <?php
                endforeach;
                ?>
                    </tbody>
                </table>
            </div>
            <div class="fv-row mb-4" id="btnAction">
                <button type="submit" class="btn btn-primary addLeave"><i class="fa-regular fa-floppy-disk"></i>&nbsp;Submit</button>
            </div>
            <div class="fv-row mb-4" id="btnConfirmation" style="display:none;">
                <button type="button" class="btn btn-primary">
                    Please wait...    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </button>
            </div>
        </form>
        <?php
    }

    public function loadCredit()
    {
        date_default_timezone_set('Asia/Manila');
        $leaveModel = new \App\Models\leaveModel();
        //data
        $employeeID = $this->request->getPost('employeeID');
        $item_vl = $this->request->getPost('item_vacation');
        $item_sl = $this->request->getPost('item_sick');
        $year = date('Y');
        //save
        $count = count($employeeID);
        for($i=0;$i<$count;$i++)
        {
            $leave = $leaveModel->WHERE('employeeID',$employeeID[$i])->first();
            if(empty($leave['employeeID']))
            {
                $values = ['employeeID'=>$employeeID[$i],'Vacation'=>$item_vl[$i],'Sick'=>$item_sl[$i],'Year'=>$year];
                $leaveModel->save($values);
            }
            else
            {
                $values = ['employeeID'=>$employeeID[$i],'Vacation'=>$item_vl[$i],'Sick'=>$item_sl[$i],'Year'=>$year];
                $leaveModel->update($leave['creditID'],$values);
            }
        }
        echo "success";
    }

    public function newEmployee()
    {
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();

        $data = ['celebrants'=>$celebrants];
        return view('HR/new-employee',$data);
    }

    public function editEmployee($id)
    {
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();
        //employee
        $employeeModel = new \App\Models\employeeModel();
        $employee = $employeeModel->WHERE('Token',$id)->first();
        $data = ['employee'=>$employee,'celebrants'=>$celebrants];
        return view('HR/edit-employee',$data);
    }

    public function updateEmployee()
    {
        date_default_timezone_set('Asia/Manila');
        $employeeModel = new \App\Models\employeeModel();
        $employeeMovementModel = new \App\Models\employeeMovementModel();
        $logModel = new \App\Models\logModel();
        //data
        $employeeID = $this->request->getPost('employeeID');
        $surname = $this->request->getPost('surname');
        $firstname = $this->request->getPost('firstname');
        $mi = $this->request->getPost('middlename');
        $suffix = $this->request->getPost('suffix');
        $companyID = $this->request->getPost('companyID');
        $contactNo = $this->request->getPost('contactNo');
        $emailAdd = $this->request->getPost('email');
        $maritalStatus = $this->request->getPost('maritalStatus');
        $dob = $this->request->getPost('dob');
        $place_of_birth = $this->request->getPost('place_of_birth');
        $address = $this->request->getPost('address');
        $date_hired = $this->request->getPost('date_hired');
        $designation = $this->request->getPost('designation');
        $department = $this->request->getPost('department');
        $salary_grade = $this->request->getPost('salary_grade');
        $employeeStatus = $this->request->getPost('employeeStatus');
        $fathersName = $this->request->getPost('fathersName');
        $mothersName = $this->request->getPost('mothersName');
        $spouseName = $this->request->getPost('spouseName');
        $spouseDOB = $this->request->getPost('spouseDOB');
        $children = $this->request->getPost('children');
        $education = $this->request->getPost('education');
        $status = $this->request->getPost('status');
        //photo
        $file = $this->request->getFile('file');
        $originalName = $file->getClientName();
        //government
        $sss = $this->request->getPost('sss_number');
        $hdmf = $this->request->getPost('pagibig_number');
        $ph = $this->request->getPost('ph_number');
        $tin = $this->request->getPost('tin_number');
        //save the employee records
        if(!empty($originalName))
        {
            $values =  ['CompanyID'=>$companyID,'Surname'=>$surname,'Firstname'=>$firstname,'MI'=>$mi,'Suffix'=>$suffix,
            'BirthDate'=>$dob,'MaritalStatus'=>$maritalStatus,'PlaceOfBirth'=>$place_of_birth,
            'Address'=>$address,'ContactNo'=>$contactNo,'EmailAddress'=>$emailAdd,'DateHired'=>$date_hired,
            'Designation'=>$designation,'Department'=>$department,'EmployeeStatus'=>$employeeStatus,
            'SalaryGrade'=>$salary_grade,'Guardian1'=>$fathersName,'Guardian2'=>$mothersName,
            'Spouse'=>$spouseName,'SpouseDOB'=>$spouseDOB,'Children'=>$children,
            'Education'=>$education,'SSS'=>$sss,'HDMF'=>$hdmf,'PhilHealth'=>$ph,'TIN'=>$tin,
            'Photo'=>$originalName,'Status'=>$status];
            $employeeModel->update($employeeID,$values);
        }
        else
        {
            $values =  ['CompanyID'=>$companyID,'Surname'=>$surname,'Firstname'=>$firstname,'MI'=>$mi,'Suffix'=>$suffix,
            'BirthDate'=>$dob,'MaritalStatus'=>$maritalStatus,'PlaceOfBirth'=>$place_of_birth,
            'Address'=>$address,'ContactNo'=>$contactNo,'EmailAddress'=>$emailAdd,
            'DateHired'=>$date_hired,'Designation'=>$designation,'Department'=>$department,'EmployeeStatus'=>$employeeStatus,
            'SalaryGrade'=>$salary_grade,'Guardian1'=>$fathersName,'Guardian2'=>$mothersName,
            'Spouse'=>$spouseName,'SpouseDOB'=>$spouseDOB,'Children'=>$children,
            'Education'=>$education,'SSS'=>$sss,'HDMF'=>$hdmf,'PhilHealth'=>$ph,'TIN'=>$tin,'Status'=>$status];
            $employeeModel->update($employeeID,$values);
        }
        //get the ID of employee movement
        $employee = $employeeMovementModel->WHERE('employeeID',$employeeID)->first();
        //update the title
        $records = ['Title'=>$designation];
        $employeeMovementModel->update($employee['movementID'],$records);
        //moved the profile pic to profile folder
        if(!empty($originalName))
        {
            $file->move('Profile/',$originalName);
        }
        //logs
        $value = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Update the Employee records'];
        $logModel->save($value);
        session()->setFlashdata('success','Great! Successfully applied changes');
        return redirect()->to('HR/employee')->withInput();
    }

    public function viewEmployee($id)
    {
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();
        //employee
        $employeeModel = new \App\Models\employeeModel();
        $employee = $employeeModel->WHERE('Token',$id)->first();
        //movement
        $builder = $this->db->table('tblemployee_movement');
        $builder->select('*');
        $builder->WHERE('employeeID',$employee['employeeID'])
                ->orderBy('movementID','DESC');
        $job = $builder->get()->getResult();
        $data = ['employee'=>$employee,'job'=>$job,'celebrants'=>$celebrants];
        return view('HR/view-employee',$data);
    }

    public function saveEmployee()
    {
        date_default_timezone_set('Asia/Manila');
        $employeeModel = new \App\Models\employeeModel();
        $employeeMovementModel = new \App\Models\employeeMovementModel();
        $logModel = new \App\Models\logModel();
        //data
        $token = $this->request->getPost('csrf_test_name');
        $surname = $this->request->getPost('surname');
        $firstname = $this->request->getPost('firstname');
        $mi = $this->request->getPost('middlename');
        $suffix = $this->request->getPost('suffix');
        $companyID = $this->request->getPost('companyID');
        $contactNo = $this->request->getPost('contactNo');
        $emailAdd = $this->request->getPost('email');
        $maritalStatus = $this->request->getPost('maritalStatus');
        $dob = $this->request->getPost('dob');
        $place_of_birth = $this->request->getPost('place_of_birth');
        $address = $this->request->getPost('address');
        $date_hired = $this->request->getPost('date_hired');
        $designation = $this->request->getPost('designation');
        $department = $this->request->getPost('department');
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
            'companyID'=>'required',
            'contactNo'=>'required|min_length[11]|max_length[11]',
            'maritalStatus'=>'required',
            'dob'=>'required',
            'place_of_birth'=>'required',
            'date_hired'=>'required',
            'address'=>'required',
            'designation'=>'required',
            'department'=>'required',
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
            //generate pin from tblrecords
            $pin = "1234";
            //save the employee records
            $values =  ['DateCreated'=>date('Y-m-d'),'CompanyID'=>$companyID,'PIN'=>$pin,'Surname'=>$surname,'Firstname'=>$firstname,'MI'=>$mi,'Suffix'=>$suffix,
                        'BirthDate'=>$dob,'MaritalStatus'=>$maritalStatus,'PlaceOfBirth'=>$place_of_birth,
                        'Address'=>$address,'ContactNo'=>$contactNo,'EmailAddress'=>$emailAdd,'DateHired'=>$date_hired,
                        'Designation'=>$designation,'Department'=>$department,'EmployeeStatus'=>$employeeStatus,
                        'SalaryGrade'=>$salary_grade,'Guardian1'=>$fathersName,'Guardian2'=>$mothersName,
                        'Spouse'=>$spouseName,'SpouseDOB'=>$spouseDOB,'Children'=>$children,
                        'Education'=>$education,'SSS'=>$sss,'HDMF'=>$hdmf,'PhilHealth'=>$ph,'TIN'=>$tin,
                        'Photo'=>$originalName,'Status'=>1,'Token'=>$token];
            $employeeModel->save($values);
            //get the employeeID
            $employee = $employeeModel->WHERE('Token',$token)->first();
            //save the movement as default value
            $records = ['employeeID'=>$employee['employeeID'],'Title'=>$designation,'Date'=>date('Y-m-d')];
            $employeeMovementModel->save($records);
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

    public function saveWork()
    {
        date_default_timezone_set('Asia/Manila');
        $workHistoryModel = new \App\Models\workHistoryModel();
        $logModel = new \App\Models\logModel();
        //data
        $eID = $this->request->getPost('employeeID');
        $job = $this->request->getPost('job');
        $company = $this->request->getPost('company');
        $address = $this->request->getPost('company_address');
        $fromdate = $this->request->getPost('fromdate');
        $todate = $this->request->getPost('todate');

        $validation = $this->validate([
            'job'=>'required',
            'company'=>'required',
            'company_address'=>'required',
            'fromdate'=>'required',
            'todate'=>'required',
        ]);
        
        if(!$validation)
        {
            echo "Please fill in the form";
        }
        else
        {
            //check if company is already added per employee
            $builder = $this->db->table('tblhistory');
            $builder->select('Company');
            $builder->WHERE('employeeID',$eID)->WHERE('Company',$company);
            $data = $builder->get();
            if($row = $data->getRow())
            {
                echo "Company/Institution already added";
            }
            else
            {
                //save the data
                $values = ['employeeID'=>$eID,'Designation'=>$job,'Company'=>$company,'Address'=>$address,'From'=>$fromdate,'To'=>$todate];
                $workHistoryModel->save($values);
                //logs
                $new_values = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Added employment history'];
                $logModel->save($new_values);
                echo "success";
            }
        }
    }

    public function listWork()
    {
        $employeeModel = new \App\Models\employeeModel();
        $token = $this->request->getGet('user');
        $employee = $employeeModel->WHERE('Token',$token)->first();
        $builder = $this->db->table('tblhistory');
        $builder->select('*');
        $builder->WHERE('employeeID',$employee['employeeID']);
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <tr>
                <td>
                    <div class="d-flex flex-column">
                        <a href="javascript:void(0);"><b><?php echo $row->Designation ?></b></a>
                        <span><?php echo $row->Company ?></span><span><?php echo $row->Address ?></span>
                    </div>
                </td>
                <td><?php echo date('d M, Y', strtotime($row->From)) ?></td>
                <td><?php echo date('d M, Y', strtotime($row->To)) ?></td>
                <td>
                    <button type="button" class="btn btn-primary btn-sm edit" value="<?php echo $row->historyID ?>"><i class="fa-regular fa-pen-to-square"></i>&nbsp;Edit</button>
                </td>
            </tr>
            <?php
        }
    }

    
    public function updateData()
    {
        date_default_timezone_set('Asia/Manila');
        $workHistoryModel = new \App\Models\workHistoryModel();
        $logModel = new \App\Models\logModel();
        //data
        $historyID = $this->request->getPost('historyID');
        $job = $this->request->getPost('job');
        $company = $this->request->getPost('company');
        $address = $this->request->getPost('address');
        $fromdate = $this->request->getPost('fromdate');
        $todate = $this->request->getPost('todate');
        //update the data
        $values = ['Designation'=>$job,'Company'=>$company,'Address'=>$address,'From'=>$fromdate,'To'=>$todate];
        $workHistoryModel->update($historyID,$values);
        //logs
        $new_values = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Update employment history'];
        $logModel->save($new_values);
        echo "success";
    }

    public function fetchData()
    {
        $val = $this->request->getGet('value');
        $builder = $this->db->table('tblhistory');
        $builder->select('*');
        $builder->WHERE('historyID',$val);
        $work = $builder->get();
        foreach($work->getResult() as $row)
        {
            ?>
            <form method="POST" class="form w-100" id="editHistory">
                <input type="hidden" name="historyID" value="<?php echo $row->historyID ?>"/>
                <div class="fv-row mb-4">
                    <span class="menu-title">Designation/Position</span>
                    <input type="text" name="job" value="<?php echo $row->Designation ?>" class="form-control bg-transparent"/>
                </div>
                <div class="fv-row mb-4">
                    <span class="menu-title">Company</span>
                    <input type="text" name="company" value="<?php echo $row->Company ?>" class="form-control bg-transparent"/>
                </div>
                <div class="fv-row mb-4">
                    <span class="menu-title">Company Address</span>
                    <textarea id="address" class="form-control" name="address" class="min-h-200px mb-2"><?php echo $row->Address ?></textarea>
                </div>
                <div class="fv-row mb-4">
                    <div class="d-flex flex-wrap gap-5">
                        <div class="fv-row w-100 flex-md-root">
                            <span class="menu-title">From</span>
                            <input type="date" name="fromdate" value="<?php echo $row->From ?>" class="form-control bg-transparent"/>
                        </div>
                        <div class="fv-row w-100 flex-md-root">
                            <span class="menu-title">To</span>
                            <input type="date" name="todate" value="<?php echo $row->To ?>" class="form-control bg-transparent"/>
                        </div>
                    </div>
                </div>
                <div class="fv-row mb-4" id="btnSave">
                    <button type="submit" class="btn btn-primary save"><i class="fa-solid fa-circle-plus"></i>&nbsp;Save Changes</button>
                </div>
                <div class="fv-row mb-4" id="btnLoading" style="display:none;">
                    <button type="button" class="btn btn-primary">
                        Please wait...    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </button>
                </div>
            </form>
            <?php
        }
    }

    public function savePromotion()
    {
        $employeeMovementModel = new \App\Models\employeeMovementModel();
        $employeeModel = new \App\Models\employeeModel();
        //save the data
        $employeeID = $this->request->getPost('employeeID');
        $job = $this->request->getPost('job');
        $date = $this->request->getPost('date');

        $validation = $this->validate([
            'job'=>'required','date'=>'required'
        ]);

        if(!$validation)
        {
            echo "Please fill in the form";
        }
        else
        {
            $values = ['employeeID'=>$employeeID,'Title'=>$job,'Date'=>$date];
            $employeeMovementModel->save($values);
            //update the designation
            $value = ['Designation'=>$job];
            $employeeModel->update($employeeID,$value);
            echo "success";
        }
    }

    //memorandum
    public function Memo()
    {
         //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();
        //memo
        $memoModel = new \App\Models\memoModel();
        $memo = $memoModel->findAll();
        $data = ['memo'=>$memo,'celebrants'=>$celebrants];
        return view('HR/Memo/index',$data);
    }

    public function Upload()
    {
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();
        //memo
        $builder = $this->db->table('tblmemo');
        $builder->select('File,Subject,Date');
        $builder->orderby('memoID','DESC')->limit(3);
        $memo = $builder->get()->getResult();

        $data = ['memo'=>$memo,'celebrants'=>$celebrants];
        return view('HR/Memo/upload-memo',$data);
    }

    public function editMemo($id)
    {
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();
        //memo
        $memoModel = new \App\Models\memoModel();
        $memo = $memoModel->WHERE('memoID',$id)->first();
        $data = ['memo'=>$memo,'celebrants'=>$celebrants];
        return view('HR/Memo/edit-memo',$data);
    }

    //user accounts
    public function Users()
    {
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();
        //users
        $accountModel = new \App\Models\accountModel();
        $account = $accountModel->findAll();
        $total = $accountModel->countAll();

        $data = ['account'=>$account,'total'=>$total,'celebrants'=>$celebrants];
        return view('HR/all-users',$data);
    }

    public function newUser()
    {
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();

        $data = ['celebrants'=>$celebrants];
        return view('HR/new-user',$data);
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
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();
        //account
        $accountModel = new \App\Models\accountModel();
        $account = $accountModel->WHERE('Token',$id)->first();
        $data = ['account'=>$account,'celebrants'=>$celebrants];
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

    public function resetPassword()
    {
        $accountModel = new \App\Models\accountModel();
        $logModel = new \App\Models\logModel();
        //data
        $val = $this->request->getPost('value');
        $defaultPassword = Hash::make("NewPassword1234");
        $values = ['Password'=>$defaultPassword];
        $accountModel->update($val,$values);
        //logs
        date_default_timezone_set('Asia/Manila');
        $account = $accountModel->WHERE('accountID',$val)->first();
        $values = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Reset the password of account: '.$account['Fullname']];
        $logModel->save($values);
        echo "success";
    }

    public function accountSecurity()
    {
        $accountModel = new \App\Models\accountModel();
        $account = $accountModel->WHERE('accountID',session()->get('loggedUser'))->first();
        //data
        $current_password = $this->request->getPost('password');
        $new_password = $this->request->getPost('new_password');
        $confirm_password = $this->request->getPost('confirm_password');
        //check if password is correct
        $passwordCheck = Hash::check($current_password,$account['Password']);
        if(!$passwordCheck||empty($passwordCheck))
        {
            session()->setFlashdata('fail','Invalid! Incorrect Password');
            return redirect()->to('HR/account')->withInput();
        }
        else
        {
            if($current_password==$new_password)
            {
                session()->setFlashdata('fail','Invalid! Please enter a new password');
                return redirect()->to('HR/account')->withInput();
            }
            else if($new_password!=$confirm_password)
            {
                session()->setFlashdata('fail','Invalid! Password mismatched. Try again');
                return redirect()->to('HR/account')->withInput();
            }
            else
            {
                $values = ['Password'=>Hash::make($new_password)];
                $accountModel->update($account['accountID'],$values);
                session()->setFlashdata('success','Great! Successfully changed the password');
                return redirect()->to('HR/account')->withInput();
            }
        }
    }
    
    //performance
    public function Performance()
    {
         //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();

        $data = ['celebrants'=>$celebrants];
        return view('HR/performance',$data);
    }
    //evaluation
    public function Evaluation()
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
        $evaluation = $evaluationModel->findAll();

        $data = ['celebrants'=>$celebrants,'evaluation'=>$evaluation];
        return view('HR/Evaluation/index',$data);
    }

    public function view($id)
    {
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();
        //evaluation
        $evaluationModel = new \App\Models\evaluationModel();
        $evaluation = $evaluationModel->WHERE('evaluationID',$id)->first();
        //questions
        $questionModel = new \App\Models\questionModel();
        $question = $questionModel->WHERE('evaluationID',$id)->findAll();

        $data = ['celebrants'=>$celebrants,'evaluation'=>$evaluation,'question'=>$question];
        return view('HR/Evaluation/view-question',$data);
    }

    //report
    public function Report()
    {
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();
        //chart
        $sql = "SELECT a.Title,COUNT(b.ecID)total FROM 
                tblconcern a LEFT JOIN (Select concernID, ecID,Status from tblemployee_concern) b ON a.concernID=b.concernID GROUP BY a.concernID";
        $query = $this->db->query($sql);
        $queries = $query->getResult();
        //for regularization
        $builder = $this->db->table('tblemployee');
        $builder->select('*');
        $builder->WHERE('TIMESTAMPDIFF(MONTH, DateHired, Now())>=',5)
                ->WHERE('EmployeeStatus','Probationary');
        $employee = $builder->get()->getResult();
        //concerns
        $sql = "SELECT a.Title,COUNT(b.ecID)total FROM 
                tblconcern a LEFT JOIN (Select concernID, ecID,Status from tblemployee_concern) b ON a.concernID=b.concernID GROUP BY a.concernID";
        $query = $this->db->query($sql);
        $concern = $query->getResult();
        //all concerns
        $sql = "Select a.*,b.Title,c.Surname,c.Firstname,c.MI,c.Suffix from tblemployee_concern a 
                LEFT JOIN tblconcern b ON b.concernID=a.concernID 
                LEFT JOIN tblemployee c ON c.employeeID=a.employeeID GROUP BY a.ecID";
        $query = $this->db->query($sql);
        $alldata = $query->getResult();

        $data = ['regular'=>$employee,'concerns'=>$concern,'alldata'=>$alldata,'query'=>$queries,'celebrants'=>$celebrants];
        return view('HR/report',$data);
    }

    public function saveEntry()
    {
        date_default_timezone_set('Asia/Manila');
        $concernModel = new \App\Models\concernModel();
        $logModel = new \App\Models\logModel();
        //data
        $title = $this->request->getPost('title');
        $validation = $this->validate([
            'title'=>'is_unique[tblconcern.Title]'
        ]);

        if(!$validation)
        {
            echo "Entry already exist. Please enter new entry";
        }
        else
        {
            if(empty($title))
            {
                echo "Please enter the title";
            }
            else
            {
                $values = ['Date'=>date('Y-m-d'),'Title'=>$title];
                $concernModel->save($values);
                //logs
                $values = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Added new concern '.$title];
                $logModel->save($values);
                echo "success";
            }
        }
    }

    //logs
    public function systemLogs()
    {
         //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();
        //logs
        $model = new \App\Models\logModel();
        $builder = $this->db->table('tblsystem_logs a');
        $builder->select('a.Date,a.Activity,b.Fullname');
        $builder->join('tblaccount b','b.accountID=a.accountID','LEFT');
        $builder->groupby('a.logID')->orderBy('a.logID','DESC');
        $logs = $builder->get()->getResult();
        //page
        $total = $model->countAll();
        $data = ['logs'=>$logs,'total'=>$total,'celebrants'=>$celebrants];
        return view('HR/system-logs',$data);
    }

    public function Maintenance()
    {
         //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();

        $data = ['celebrants'=>$celebrants];
        return view('HR/system-config',$data);
    }

    public function Account()
    {
        //celebrants
        $month = date('m');
        $builder = $this->db->table('tblemployee');
        $builder->select('Surname,Firstname,MI,Suffix,Designation,BirthDate');
        $builder->WHERE('DATE_FORMAT(BirthDate,"%m")',$month)->WHERE('Status',1);
        $builder->orderby('BirthDate','ASC');
        $celebrants = $builder->get()->getResult();
        //account
        $accountModel = new \App\Models\accountModel();
        $account = $accountModel->WHERE('accountID',session()->get('loggedUser'))->first();
        $data = ['account'=>$account,'celebrants'=>$celebrants];
        return view('HR/account',$data);
    }

}
