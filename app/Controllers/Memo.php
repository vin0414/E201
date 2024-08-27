<?php

namespace App\Controllers;
class Memo extends BaseController
{
    private $db;
    public function __construct()
    {
        helper(['form']);
        $this->db = db_connect();
    }

    public function uploadFile()
    {
        date_default_timezone_set('Asia/Manila');
        $memoModel = new \App\Models\memoModel();
        $logModel = new \App\Models\logModel();
        //data
        $user = session()->get('loggedUser');
        $date = $this->request->getPost("date");
        $from = $this->request->getPost("from");
        $to = $this->request->getPost("to");
        $subject = $this->request->getPost("subject");
        $file = $this->request->getFile("file");
        $originalName = $file->getClientName();
        //validation
        $validation = $this->validate([
            'date'=>'required',
            'from'=>'required',
            'to'=>'required',
            'subject'=>'required',
        ]);

        if(!$validation)
        {
            session()->setFlashdata('fail','Please fill in the form');
            return redirect()->to('HR/Memo/Upload')->withInput();
        }
        else
        {
            //moved the file to Memo folder
            if(empty($originalName))
            {
                session()->setFlashdata('fail','Please attach the required file');
                return redirect()->to('HR/Memo/Upload')->withInput();
            }
            else
            {
                $file->move('Memo/',$originalName);
                $values = ['Date'=>$date,'From'=>$from,'To'=>$to,'Subject'=>$subject,'File'=>$originalName,'DateCreated'=>date('Y-m-d'),'accountID'=>$user,'Status'=>1];
                $memoModel->save($values);
                //logs
                $value = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Uploaded memo with the subject of '.$subject];
                $logModel->save($value);
                session()->setFlashdata('success','Great! Successfully uploaded');
                return redirect()->to('HR/Memo/Upload')->withInput();
            }
        }
    }

    public function updateMemo()
    {
        date_default_timezone_set('Asia/Manila');
        $memoModel = new \App\Models\memoModel();
        $logModel = new \App\Models\logModel();
        //data
        $memoID = $this->request->getPost('memoID');
        $date = $this->request->getPost("date");
        $from = $this->request->getPost("from");
        $to = $this->request->getPost("to");
        $subject = $this->request->getPost("subject");
        $file = $this->request->getFile("file");
        $originalName = $file->getClientName();
        //validation
        $validation = $this->validate([
            'date'=>'required',
            'from'=>'required',
            'to'=>'required',
            'subject'=>'required',
        ]);

        if(!$validation)
        {
            session()->setFlashdata('fail','Please fill in the form');
            return redirect()->to('HR/Memo/edit-memo/'.$memoID)->withInput();
        }
        else
        {
            if(empty($originalName))
            {
                $values = ['Date'=>$date,'From'=>$from,'To'=>$to,'Subject'=>$subject];
                $memoModel->update($memoID,$values);
            }
            else
            {
                $file->move('Memo/',$originalName);
                $values = ['Date'=>$date,'From'=>$from,'To'=>$to,'Subject'=>$subject,'File'=>$originalName];
                $memoModel->update($memoID,$values);
            }
            //logs
            $value = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Applied changes with '.$subject];
            $logModel->save($value);
            session()->setFlashdata('success','Great! Successfully applied changes');
            return redirect()->to('HR/Memo')->withInput();
        }
    }

    public function archive()
    {
        date_default_timezone_set('Asia/Manila');
        $memoModel = new \App\Models\memoModel();
        $logModel = new \App\Models\logModel();
        //data
        $val = $this->request->getPost('value');
        $values = ['Status'=>0];
        $memoModel->update($val,$values);
        //logs
        $value = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Moved memo to archive'];
        $logModel->save($value);
        echo "success";
    }

    public function unarchive()
    {
        date_default_timezone_set('Asia/Manila');
        $memoModel = new \App\Models\memoModel();
        $logModel = new \App\Models\logModel();
        //data
        $val = $this->request->getPost('value');
        $values = ['Status'=>1];
        $memoModel->update($val,$values);
        //logs
        $value = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Unarchive memo'];
        $logModel->save($value);
        echo "success";
    }
}
