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
$routes->post('/auth','Auth::auth');
$routes->get('/logout','Auth::logout');
//functions for User Accounts
$routes->post('save-account','Home::saveUserData');
$routes->post('modify-account','Home::modifyAccount');

$routes->group('',['filter'=>'AuthCheck'],function($routes)
{
    //Admin module
    $routes->get('HR/overview','Home::Overview');
    //employee
    $routes->get('HR/employee','Home::Employee');
    //memo
    $routes->get('HR/Memo','Home::Memo');
    $routes->get('HR/Memo/Upload','Home::Upload');
    //users
    $routes->get('HR/users','Home::Users');
    $routes->get('HR/new-account','Home::newUser');
    $routes->get('HR/edit/(:any)','Home::editUser/$1');
    //system logs
    $routes->get('HR/logs','Home::systemLogs');
    //account settings
    $routes->get('HR/account','Home::Account');
    //Evaluation
    //Report
});

$routes->group('',['filter'=>'AlreadyLoggedIn'],function($routes)
{
    $routes->get('/', 'Home::index');
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
