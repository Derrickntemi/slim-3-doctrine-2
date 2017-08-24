<?php
/**
 * Sets the errror reporting configuration
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * The default route
 */
$app->get('/', function($request,$response, $args)
{
return $response->withStatus(404)
                ->withJson(array('error' => 'Invalid route, please refer to the documentation'));
});

/**
 * These routes handle the registration of new users,resetting the password,login of existing users,listing of all users,listing of mps and their constituencies,
 * listing of senators and their counties, listing of women leaders,listing of gender and disability ratios in the houses,listing of performance,
 *
 */
/**
 * This route gets all users
 */
$app->get('/users','App\Action\UserAction:fetch')->setName('GetAllUsers');

/**
 * This route gets a single user by phone number
 */
$app->get('/validate/{phoneNo}','App\Action\UserAction:fetchOne')->setName('GetOneUserByPhoneNo');

/**
 * This route registers a new user
 */
$app->post('/register','App\Action\UserAction:RegisterUser')->setName('RegisterNewUser');

/**
 * This route allows the user to login into the app
 */
$app->post('/login','App\Action\UserAction:login')->setName('login');

/**
 * Allows the user to reset the password
 */
$app->post('/resetpassword','App\Action\UserAction:forgotpassword')->setName('resetPassword');

/**
 * view profile
 */
$app->get('/viewprofile','App\Action\UserAction:viewprofile')->setName('ViewUserProfile');

/**
 * Update user profile
 */
$app->put('/updateprofile','App\Action\UserAction:doupdateprofile')->setName('UpdateYourProfile');
/**
 * view all counties
 */
$app->get('/counties','App\Action\CountyAction:fetch')->setName('GetAllCounties');
/**
 * View one county by county code
 */
$app->get('/counties/{countyCode}','App\Action\CountyAction:fetchOne')->setName('GetOneCountyByCountyCode');
/**
 * gets all constituencies
 */
$app->get('/constituencies','App\Action\ConstituencyAction:fetch')->setName('GetAllConstituencies');
/**
 * Gets one constituency by Constituency code
 */
$app->get('/constituency/{constituencyId}','App\Action\ConstituencyAction:fetchOne')->setName('GetConstituencyByConstituencyCode');

/**
 * Get All roles
 */
$app->get('/roles','App\Action\RoleAction:fetch')->setName('GetAllRoles');

/**
 * Get one role by Id
 */
$app->get('/role/{roleId}','App\Action\RoleAction:fetchOne')->setName('GetOneRoleById');
/**
 * create role
 */
$app->post('/createrole','App\Action\RoleAction:createRole')->setName('CreateRole');