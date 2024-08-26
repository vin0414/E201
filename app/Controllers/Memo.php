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
        $memoModel = new \App\Models\memoModel();
        //data
        $user = session()->get('loggedUser');
        $date = $this->request->getPost('date');
        $from = $this->request->getPost('from');
        $to = $this->request->getPost('to');
        $subject = $this->request->getPost('subject');
        $file = $this->request->getFile("file");
        $originalName = $file->getClientName();
        
        $values = ['Date','From','To','Subject','DateCreated','accountID'];
    }
}
