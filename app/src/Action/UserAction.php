<?php
/**
 * Created by IntelliJ IDEA.
 * User: dkimathi
 * Date: 8/17/2017
 * Time: 6:37 PM
 */

namespace App\Action;


use App\AbstractAction;
use App\Resource\ConstituencyResource;
use App\Resource\CountyResource;
use App\Resource\RoleResource;
use App\Resource\UserResource;
use psr\Log\LoggerInterface;



class UserAction extends AbstractAction
{

    private $userResource;
    private $constituencyResource;
    private $countyResource;
    private $roleResource;


    /**
     * constructor to initialize all the required objects
     * UserResource $userResource
     * LoggerInterface $loggerInterface
     *  * @property CountyResource countyResource
     * @param UserResource $userResource
     * @param LoggerInterface $logger
     */
    public function __construct(UserResource $userResource, CountyResource $countyResource,ConstituencyResource $constituencyResource, RoleResource $roleResource,LoggerInterface $logger)
    {
        $this->userResource = $userResource;
        $this->countyResource = $countyResource;
        $this->constituencyResource = $constituencyResource;
        $this->roleResource = $roleResource;
        $this->logger = $logger;
    }

    /**
     * @param $request
     * @param $response
     * @param $args
     * function to get aall users from the entity
     */
    public function fetch($request, $response, $args)
    {
        $user = $this->userResource->get();

        if($user)
        {
            return $response->withJson($user);
        }

        else
        {
          return $response->withStatus(400)
                        ->withJson(array('error' => 'No users found'));
        }
    }

    public function fetchOne($request,$response ,$args)
    {
        $user = $this->userResource->get($args['phoneNo']);


        if($user)
        {
            return $response->withJson($user);
        }
            return $response->withStatus(400)
                            ->withJson(array('error' => 'No user found with that phone number'));

    }

    /**
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * Function to register a new user
     */
    public function RegisterUser($request, $response, $args)
    {
        $error_array = array();

        if (!$request->getParams('phoneNo')): $error_array[] = array('name' => 'Phone number', 'text' => 'Phone number not provided'); endif;
        if (!$request->getParams('password')): $error_array[] = array('name' => 'password', 'text' => 'Password not provided'); endif;
        if (!$request->getParams('emailAddress')): $error_array[] = array('name' => 'emailAddress', 'text' => 'emailAddress not provided'); endif;
        if (!$request->getParams('idNo')): $error_array[] = array('name' => 'Id number', 'text' => 'Id number  not provided'); endif;
        if (!$request->getParams('firstName')): $error_array[] = array('name' => 'firstName', 'text' => 'firstName not provided'); endif;
        if (!$request->getParams('secondName')): $error_array[] = array('name' => 'secondName', 'text' => 'secondName not provided'); endif;
        if (!$request->getParams('surname')): $error_array[] = array('name' => 'surname', 'text' => 'surname not provided'); endif;
        if (!$request->getParams('dob')): $error_array[] = array('name' => 'date of birth', 'text' => 'date of birth not provided'); endif;
        if (!$request->getParams('gender')): $error_array[] = array('name' => 'gender', 'text' => 'gender  not provided'); endif;
        if (!$request->getParams('disabled')): $error_array[] = array('name' => 'disabled', 'text' => 'disability  not provided'); endif;
        if (!$request->getParams('nationality')): $error_array[] = array('name' => 'nationality', 'text' => 'nationality  not provided'); endif;
        if (!$request->getParams('county')): $error_array[] = array('name' => 'county', 'text' => 'county  not provided'); endif;
        if (!$request->getParams('constituency')): $error_array[] = array('name' => 'constituency', 'text' => 'constituency  not provided'); endif;

        if(count($error_array) !== 0):
            return $response->withStatus(400)
                ->withJson(array('error' => $error_array));
        endif;


        /**
         * Check whether the user account already exists
         */

        $user_exists = $this->userResource->login($request);

        if($user_exists) :
            $this->logger->error(
                                serialize(
                                    array(
                                        'Firstname' => $request->getParams('firstName'),
                                        'Secondname' => $request->getParams('secondName'),
                                        'Phone number'=> $request->getParams('phoneNo'),
                                        'text'=> 'This user already exists'
                                    )
                                )
                            );
        return $response->withStatus(400)
                        ->withJson(array('error' => 'The user identified with this phone number already exists'));

            endif;
        $role  = ($request->getParam('role') ? : "2");
        $role_id = $this->roleResource->getObject($role);

        $county = $request->getParam('county');

        $constituency = $request->getParam('constituency');

        $county = $this->countyResource->getObject($county);

        $constituency = $this->constituencyResource->getObject($constituency);

        $user = $this->userResource->insert($request,$county,$constituency,$role_id);

        if($user)
        {
            return $response->withJson($user);
        }

        return $response->withStatus(400)
                        ->withJson(array('error' => 'User account cannot be created, an error occurred during the process'));

}

      public function login($request,$response,$args)
      {

        $error_array = array();

        if(!$request->getParam('phoneNo')) : $error_array[] = array('name' => 'phoneNo', 'text' => 'The Phone number has not been provided'); endif;
        if(!$request->getParam('password')) : $error_array[] = array('name' => 'password', 'text' => 'The password has not been provided'); endif;

        if(count($error_array) !== 0)
        {
            return $response->withStatus(400)
                            ->withJson(array($error_array));
        }
         $user = $this->userResource->login($request);

        if($user) :
          return $response->withJson(
                            array_merge(array('access_token' => $user['access_token']),
                                        array('user_data' => $user['user_data'])
                            )
                            );
        $this->logger->info(serialize($user));
        return $response->withJson($user);
        endif;
        $this->logger->error(
                        serialize(
                            array(
                                'Phone number' => $request->getParam('phoneNo'),
                                'password'     => $request->getParam('password'),
                                'host'         => $request->getParam('Host')
                            )
                        )
                    );
        return $response->withJson(array('error' => array('message' => 'The credentials you have provided are invalid')));

    }

    public function forgotpassword($request, $response, $args)
    {
        $error_array = array();

        if(!$request->getParam('emailAddress')): $error_array[] = array('name'=>'email','text'=>'email address missing'); endif;

        if(count($error_array) !== 0):
            return $response->withStatus(400)
                ->withJSON(
                    array('error' => $error_array)
                );
        endif;


        $reset = $this->userResource->resetPassword($request);
        if ($reset) {
            $this->logger->info(
                serialize(
                    array(
                        'email'         => $request->getParam('emailAddress'),
                        'host'          => $request->getHeader('Host')
                    )));
            return $response->withJSON($reset);
        }
        $this->logger->error(
            serialize(
                array(
                    'email'         => $request->getParam('emailAddress'),
                    'host'          => $request->getHeader('Host')
                )));
        return $response->withStatus(400)
            ->withJSON(
                array('error' => array(
                    'message'=>'That email address does not exist') )
            );
    }

    /**
     * View user profile; the data encoded in the JWT token
     * @param $request
     * @param $response
     * @param $args
     * @return
     * @throws \UnexpectedValueException
     * @throws \Firebase\JWT\SignatureInvalidException
     * @throws \Firebase\JWT\ExpiredException
     * @throws \Firebase\JWT\BeforeValidException
     * @throws \DomainException
     */
    public function viewprofile($request,$response,$args)
    {
       $user_data = $this->decodedData($request);

       if($user_data) :
           return $response->withJson($user_data);
       endif;

          return $response->withStatus(400)
                          ->withJson(array('error' => 'Error retrieving the user profile'));
    }

    /**
     * @param $request
     * @param $response
     * @return mixed
     * @throws \UnexpectedValueException
     * @throws \Firebase\JWT\SignatureInvalidException
     * @throws \Firebase\JWT\ExpiredException
     * @throws \Firebase\JWT\BeforeValidException
     * @throws \DomainException
     * Updates the user profile
     */

    public function doupdateprofile($request, $response)
    {
        $exists = $this->userResource->confirmResource($request->getParam('emailAddress'),'emailAddress');
        if($exists) :
            return $response->withStatus(400)
                            ->withJson(array('error' => 'The email address already exists'));
        endif;

        $exists = $this->userResource->confirmResource($request->getParam('idNo'),idNo);
        if($exists) :
            return $response->withStatus(400)
                            ->withJson(array('error' => 'The Id number already exists'));
        endif;

        $update = $this->userResource->updateProfile($request, $this->decodedData($request)->phoneNo);

        if($update) :
            return $response->withJson(array('status' => 'success',
                                              'message' => 'Your profile has been updated successfully'
                ));
        endif;

           return $response->withStatus(400)
                           ->withJson(array('error' => 'There was an error during the update process, please try again'));
    }

}