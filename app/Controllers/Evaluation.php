<?php

namespace App\Controllers;
use App\Libraries\Hash;

class Evaluation extends BaseController
{
    private $db;
    public function __construct()
    {
        helper(['url','form']);
        $this->db = db_connect();
    }

    public function save()
    {
        date_default_timezone_set('Asia/Manila');
        $evaluationModel = new \App\Models\evaluationModel();
        $logModel = new \App\Models\logModel();
        //data
        $title = $this->request->getPost('title');
        $details = $this->request->getPost('details');
        $status = 1;
        $date = date('Y-m-d');
        //validate
        $validation = $this->validate([
            'title'=>'required','details'=>'required'
        ]);
        if(!$validation)
        {
            echo "Please fill in the form";
        }
        else
        {
            $records = ['DateCreated'=>$date,'Title'=>$title,'Details'=>$details,'Status'=>$status,'accountID'=>session()->get('loggedUser')];
            $evaluationModel->save($records);
            //save logs
            $values = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Added evaluation : '.$title];
            $logModel->save($values);
            echo "success";
        }
    }

    public function view()
    {
        $evaluationModel = new \App\Models\evaluationModel();
        $val = $this->request->getGet('value');
        $evaluation = $evaluationModel->WHERE('evaluationID',$val)->first();
        if($evaluation):
            ?>
            <form method="POST" class="form w-100" id="editEvaluation">
                <input type="hidden" name="evaluationID" value="<?php echo $evaluation['evaluationID'] ?>"/>
                <div class="fv-row mb-4">
                    <spanc class="menu-title">Title</span>
                    <input type="text" class="form-control" value="<?php echo $evaluation['Title'] ?>" name="title"/>
                </div>
                <div class="fv-row mb-4">
                    <spanc class="menu-title">Details</span>
                    <textarea class="form-control" name="details"><?php echo $evaluation['Details'] ?></textarea>
                </div>
                <div class="fv-row mb-4">
                    <spanc class="menu-title">Status</span>
                    <select class="form-select mb-2" data-control="select2" name="status">
                        <option value="">Choose</option>
                        <option value="1" <?php if($evaluation['Status']==1) echo 'selected="selected"'; ?> >Active</option>
                        <option value="0" <?php if($evaluation['Status']==0) echo 'selected="selected"'; ?>>Inactive</option>
                    </select>  
                </div>
                <div class="fv-row mb-4" id="btnConfirmation">
                    <button type="submit" class="btn btn-primary save" id="Add"><i class="fa-regular fa-floppy-disk"></i>&nbsp;Apply Changes</button>
                </div>
                <div class="fv-row mb-4" id="btnLoading" style="display:none;">
                    <button type="button" class="btn btn-primary">
                        Please wait...    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </button>
                </div>
            </form> 
            <?php
        endif;
    }

    public function update()
    {
        date_default_timezone_set('Asia/Manila');
        $evaluationModel = new \App\Models\evaluationModel();
        $logModel = new \App\Models\logModel();
        //data
        $evaluationID = $this->request->getPost('evaluationID');
        $title = $this->request->getPost('title');
        $details = $this->request->getPost('details');
        $status = $this->request->getPost('status');
        //validate
        $validation = $this->validate([
            'title'=>'required','details'=>'required','status'=>'required'
        ]);
        if(!$validation)
        {
            echo "Please fill in the form";
        }
        else
        {
            $records = ['Title'=>$title,'Details'=>$details,'Status'=>$status];
            $evaluationModel->update($evaluationID,$records);
            //save logs
            $values = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Update evaluation : '.$title];
            $logModel->save($values);
            echo "success";
        }
    }

    public function saveQuestion()
    {
        date_default_timezone_set('Asia/Manila');
        $questionModel = new \App\Models\questionModel();
        //data
        $id = $this->request->getPost('evaluationID');
        $details = $this->request->getPost('details');
        $date = date('Y-m-d');
        $status = 1; //active
        //validate
        $validation = $this->validate([
            'details'=>'required',
        ]);
        if(!$validation)
        {
            echo "Please fill in the form";
        }
        else
        {
            $values = ['evaluationID'=>$id,'Details'=>$details,'Status'=>$status,'DateCreated'=>$date];
            $questionModel->save($values);
            echo "success";
        }
    }

    public function viewQuestion()
    {
        $questionModel = new \App\Models\questionModel();
        //data
        $id = $this->request->getGet("value");
        $question = $questionModel->WHERE('questionID',$id)->first();
        if($question):
        {
            ?>
            <form method="POST" class="form w-100" id="editQuestion">
                <input type="hidden" name="questionID" value="<?php echo $question['questionID'] ?>"/>
                <div class="fv-row mb-4">
                    <spanc class="menu-title">Details</span>
                    <textarea class="form-control" name="details"><?php echo $question['Details'] ?></textarea>
                </div>
                <div class="fv-row mb-4">
                    <spanc class="menu-title">Status</span>
                    <select class="form-select mb-2" data-control="select2" name="status">
                        <option value="">Choose</option>
                        <option value="1" <?php if($question['Status']==1) echo 'selected="selected"'; ?> >Active</option>
                        <option value="0" <?php if($question['Status']==0) echo 'selected="selected"'; ?>>Inactive</option>
                    </select>  
                </div>
                <div class="fv-row mb-4" id="btnConfirmation">
                    <button type="submit" class="btn btn-primary save" id="Add"><i class="fa-regular fa-floppy-disk"></i>&nbsp;Apply Changes</button>
                </div>
                <div class="fv-row mb-4" id="btnLoading" style="display:none;">
                    <button type="button" class="btn btn-primary">
                        Please wait...    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </button>
                </div>
            </form>
            <?php
        }
        endif;
    }

    public function editQuestion()
    {
        $questionModel = new \App\Models\questionModel();
        //data
        $questionID = $this->request->getPost('questionID');
        $details = $this->request->getPost('details');
        $status = $this->request->getPost('status');
        //validate
        $validation = $this->validate([
            'details'=>'required','status'=>'required'
        ]);
        if(!$validation)
        {
            echo "Please fill in the form";
        }
        else
        {
            $values = ['Details'=>$details,'Status'=>$status];
            $questionModel->update($questionID,$values);
            echo "success";
        }
    }
}