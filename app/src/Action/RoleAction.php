<?php
/**
 * Created by IntelliJ IDEA.
 * User: dkimathi
 * Date: 8/23/2017
 * Time: 6:26 PM
 */

namespace App\Action;


use App\AbstractAction;
use App\Resource\RoleResource;
use Psr\Log\LoggerInterface;

class RoleAction extends AbstractAction
{
private $roleResource;


    /**
     * Constructor to initialize all the required objects in the class
     * @property LoggerInterface logger
     */
    public function __construct(RoleResource $roleResource,LoggerInterface $logger)
{
    $this->roleResource = $roleResource;
    $this->logger = $logger;
}
/**
 * function to get all roles from the db
 */

    public function fetch($request,$response,$args)
    {
        $role = $this->roleResource->get();

        if($role) :
            return $response->withJson($role);
        endif;

        return $response->withStatus(400)
                        ->withJson(array('error' => 'No roles found, please try again'));

    }

    public function fetchOne($request,$response,$args)
    {
        $role = $this->roleResource->get($args['roleId']);

        if($role) :
            return $response->withJson($role);
        endif;

        return $response->withStatus(400)
                        ->withJson(array('error' => 'No role with that ID found'));

    }

    public function createRole($request,$response,$args)
    {
        $error_array = array();

        if(!$request->getParam('roleName')) : $error_array[] = array('name' => 'role name','message' =>'The name of the role is missing'); endif;
        if(!$request->getParam('roleDescription')) : $error_array[] = array('name' => 'role description','message' =>'The description of the role is missing'); endif;


        if(count($error_array) !== 0):
            return $response->withStatus(400)
                ->withJson(array('error' => $error_array));
        endif;

        $exists = $this->roleResource->checkRole($request);

        if($exists) : return $response->withJson(array('role' => $exists, 'error' => 'The role you have entered already exists')); endif;

        $role = $this->roleResource->insert($request);

        if($role) :
            return $response->withStatus(200)
                            ->withJson($role);
        endif;

        return $response->withStatus(400)
                        ->withJson(array('error' => 'An error occured,please try again'));
    }

}