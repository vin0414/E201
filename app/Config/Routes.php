<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

//authentication
//hr
$routes->post('/auth','Auth::auth');
$routes->get('/logout','Auth::logout');
//employee
$routes->post('/employeeAuth','Auth::employeeAuth');
$routes->get('/signout','Auth::employeeLogout');
//functions for User Accounts
$routes->post('save-account','Home::saveUserData');
$routes->post('modify-account','Home::modifyAccount');
$routes->post('reset-password','Home::resetPassword');
$routes->post('account-security','Home::accountSecurity');
//employee functions
$routes->post('save-employee','Home::saveEmployee');
$routes->post('update-employee','Home::updateEmployee');
$routes->post('save-work','Home::saveWork');
$routes->get('list-work','Home::listWork');
$routes->get('fetch-data','Home::fetchData');
$routes->post('update-data','Home::updateData');
$routes->post('save-promotion','Home::savePromotion');
$routes->post('change-pin','Employee::changePIN');
//memo
$routes->post('upload-file','Memo::uploadFile');
$routes->post('move-to-archive','Memo::archive');
$routes->post('move-to-unarchive','Memo::unarchive');
$routes->post('update-memo','Memo::updateMemo');
//concern
$routes->post('resolve','Employee::resolve');
$routes->post('denied','Employee::denied');
//maintenance
$routes->post('restore','Restore::restoreFile');
$routes->get('download','Download::downloadFile');
//concern entry
$routes->post('save-entry','Home::saveEntry');

$routes->group('',['filter'=>'AuthCheck'],function($routes)
{
    //Admin module
    $routes->get('HR/overview','Home::Overview');
    //employee
    $routes->get('HR/employee','Home::Employee');
    $routes->get('HR/new-employee','Home::newEmployee');
    $routes->get('HR/edit-employee/(:any)','Home::editEmployee/$1');
    $routes->get('HR/view/(:any)','Home::viewEmployee/$1');
    //memo
    $routes->get('HR/Memo','Home::Memo');
    $routes->get('HR/Memo/Upload','Home::Upload');
    $routes->get('HR/Memo/edit-memo/(:any)','Home::editMemo/$1');
    //users
    $routes->get('HR/users','Home::Users');
    $routes->get('HR/new-account','Home::newUser');
    $routes->get('HR/edit/(:any)','Home::editUser/$1');
    //system logs
    $routes->get('HR/logs','Home::systemLogs');
    //account settings
    $routes->get('HR/account','Home::Account');
    //Performance
    $routes->get('HR/performance','Home::Performance');
    //Evaluation
    $routes->get('HR/Evaluation','Home::Evaluation');
    //Report
    $routes->get('HR/report','Home::Report');
    //maintenance
    $routes->get('HR/maintenance','Home::Maintenance');
});

$routes->group('',['filter'=>'AlreadyLoggedIn'],function($routes)
{
    $routes->get('/', 'Home::index');
});


//employee
$routes->group('',['filter'=>'EmployeeAuthCheck'],function($routes)
{
    $routes->get('Employee/overview','Employee::overview');
    $routes->get('Employee/write','Employee::writeConcern');
    $routes->get('Employee/concerns','Employee::concerns');
    $routes->get('Employee/request','Employee::request');
    $routes->get('Employee/memo','Employee::memo');
    $routes->get('Employee/account','Employee::account');
});

$routes->group('',['filter'=>'EmployeeAlreadyLoggedIn'],function($routes)
{
    $routes->get('/employee', 'Employee::employeeIndex');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
