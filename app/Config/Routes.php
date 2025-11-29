<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::index');
$routes->get('register', 'Auth::register');
$routes->post('/register/submit', 'Auth::submit');

// Login routes
$routes->post('/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('verify/(:any)', 'Auth::verify/$1');

// Password Reset routes
$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password/send', 'Auth::sendResetLink');
$routes->get('reset-password/(:any)', 'Auth::resetPassword/$1');
$routes->post('reset-password/update', 'Auth::updatePassword');

// Dashboard routes
$routes->get('/studentDash_main', 'Student::dashboard');
$routes->get('/itsoDash_main', 'It::dashboard');
$routes->get('/assoDash_main', 'Associate::dashboard');
$routes->get('Student/getAvailableItems', 'Student::getAvailableItems');
$routes->get('Student/getItemStock/(:num)', 'Student::getItemStock/$1');
$routes->get('Student/getUserBorrowedQty/(:num)', 'Student::getUserBorrowedQty/$1');
$routes->post('Student/process', 'Student::process');
$routes->post('Student/returnEquipment', 'Student::returnEquipment');

// About Page
$routes->get('about', 'About::index');

// Media serving routes
$routes->get('media/(:segment)/(:segment)/(:segment)', 'Media::serve/$1/$2/$3');
$routes->get('media/(:segment)/(:segment)', 'Media::serve/$1/$2');

// Associate Dashboard - ADD THIS LINE
$routes->get('associate/reservation', 'Associate::reservation');
$routes->get('associate/records', 'Associate::records');
$routes->post('associate/process', 'Associate::process');
$routes->post('associate/borrow', 'Associate::borrowEquipment');
$routes->post('associate/return', 'Associate::returnEquipment');
$routes->post('associate/reservation/create', 'Associate::createReservation');
$routes->post('associate/reservation/cancel', 'Associate::cancelReservation');
$routes->post('associate/reservation/reschedule', 'Associate::rescheduleReservation');

//ITSO Dashboard
$routes->get('itso/items', 'It::items');
$routes->get('itso/records', 'It::records');
$routes->get('itso/reports', 'It::reports');

// Equipment Management Routes
$routes->get('itso/equipment/get/(:num)', 'It::getEquipment/$1');
$routes->post('itso/equipment/add', 'It::addEquipment');
$routes->post('itso/equipment/update', 'It::updateEquipment');
$routes->post('itso/equipment/deactivate', 'It::deactivateEquipment');

// User Management Routes
$routes->get('itso/users', 'It::users');
$routes->get('itso/users/get/(:num)', 'It::getUser/$1');
$routes->post('itso/users/update', 'It::updateUser');
$routes->post('itso/users/deactivate', 'It::deactivateUser');
$routes->post('itso/users/activate', 'It::activateUser');