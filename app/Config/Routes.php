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
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/debug', 'Home::index');

/**
 * ---------------------------------------------------------------------
 * API Routing V1
 * ---------------------------------------------------------------------
 */
$routes->group('api', static function ($routes) {
    $routes->group('v1', static function ($routes) {
        $routes->post('login', '\Core\Users\Controllers\AuthController::login');
        $routes->get('profile', '\Core\Users\Controllers\AuthController::profile');

        $routes->get('users', '\Core\Users\Controllers\GetUserController::index');
        $routes->post('users', '\Core\Users\Controllers\ManageUserController::create');
        $routes->put('users', '\Core\Users\Controllers\ManageUserController::update');
        $routes->get('users/(:any)', '\Core\Users\Controllers\GetUserController::getById/$1');
        $routes->delete('users/(:any)', '\Core\Users\Controllers\ManageUserController::delete/$1');

        $routes->post('voters/(:any)/submit/(:any)', '\Core\Voters\Controllers\SubmitVotersController::execute/$1/$2');
        $routes->post('voters/(:any)/generate/(:any)', '\Core\Voters\Controllers\GenerateController::generate/$1/$2');
        $routes->get('voters/(:any)/coklit/(:any)', '\Core\Voters\Controllers\GetVotersController::coklitSummary/$1/$2');
        $routes->put('voters/(:any)/profile', '\Core\Voters\Controllers\ProfileVotersController::edit/$1');
        $routes->put('voters/(:any)/status', '\Core\Voters\Controllers\StatusVotersController::edit/$1');
        $routes->post('voters/(:any)', '\Core\Voters\Controllers\CreateVotersController::create/$1');
        $routes->get('voters/(:any)', '\Core\Voters\Controllers\GetVotersController::index/$1');

        $routes->get('summaries/(:any)', '\Core\Recaps\Controllers\GetRecapController::index/$1');

        $routes->get('districts', '\Core\Regions\Controllers\RegionsController::getDistricts');
        $routes->get('districts/(:any)/villages', '\Core\Regions\Controllers\RegionsController::getVillages/$1');
        $routes->get('villages/(:any)', '\Core\Regions\Controllers\GetVillageController::getVillageById/$1');
    });
});

$routes->cli('tools/import', '\Core\Voters\Controllers\ImportController::run');

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
