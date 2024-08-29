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
        return view('Employee/index');
    }

}
