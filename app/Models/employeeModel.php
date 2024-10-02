<?php

namespace App\Models;

use CodeIgniter\Model;

class employeeModel extends Model
{
    protected $table      = 'tblemployee';
    protected $primaryKey = 'employeeID';

    protected $useAutoIncrement  = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $userSoftDelete = false;
    protected $protectFields = true;
    protected $allowedFields = ['DateCreated','CompanyID','PIN','Surname','Firstname','MI','Suffix',
                                'BirthDate','MaritalStatus','PlaceOfBirth','Address','ContactNo','Gender',
                                'EmailAddress','DateHired','Designation','Department','EmployeeStatus',
                                'SalaryGrade','Guardian1','Guardian2','Spouse','SpouseDOB','Children',
                                'Education','SSS','HDMF','PhilHealth','TIN','Photo','Status','Token'];

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    
    
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];
}