<?php
/**
 * Created by IntelliJ IDEA.
 * User: dkimathi
 * Date: 8/17/2017
 * Time: 6:43 PM
 */

namespace App\Resource;


use App\AbstractResource;
use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\QueryException;


/**
 * Class UserResource
 * @package App\Resource
 */

class UserResource extends AbstractResource
{

    /**
     * @param null $phoneNo
     * @return array|bool|null|object handles the interaction with the db and return an array
     * handles the interaction with the db and return an array
     * @internal param null $phone_no
     */
    public function get($phoneNo = null)
    {
        if($phoneNo === null)
        {
            $user = $this->entityManager->getRepository(User::class)->findAll();
            $user = array_map(
                function($user)
                {
                    return $user->getArrayCopy();
                },
                $user
                );
            return $user;
        }



            $user = $this->entityManager->getRepository(User::class)->findOneBy(array('phoneNo' => $phoneNo));

            return($user ? $user->getArrayCopy(): false);




    }

    public function login($request)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder->select('u.firstName','u.secondName','u.emailAddress','u.userId','u.idNo','u.surname','u.dob','u.gender','u.disabled','u.nationality','u.dateUpdated','u.dateCreated')
                    ->from('App\Entity\User', 'u')
                    ->where('u.phoneNo = \''.$request->getParam('phoneNo').'\'')
                    ->andWhere('u.password = \''.$this->encrypt($request->getParam('password')).'\'');
        $query = $queryBuilder->getQuery();

        /**
         * catch errors from the entity
         */
        try{
            $result = $query->getSingleResult();

            if($result) :
                //Logic for assigning the JWT token
                $data_array = array('email'=> $request->getParam('email'), 'scope' => 'all');
            $toBeEncodedData = array(
                "iss"    => $request->getHeader('Host'),
                "jti"    => uniqid('', true),
                "iat"    => time(),
                "nbf"    => time()+1,
                "exp"    => time()+14400,
                "data"   => $result//$data_array
         );                                                       
 return array('status' => 'success',                              
              'access_token' => $this->getToken($toBeEncodedData),
              'user_data' => $result);                            
                endif;
           }
        catch(NoResultException $e)
        {
            //If query returns 0 rows
           return false;
        }
           return false;
    }

    /**
     * Creates new user in the database(called in UserAction)
     * @param $request
     * @param $county
     * @param $constituency
     * @param $role_id
     * @return array|bool
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function insert($request,$county,$constituency,$role_id)
    {
        $user = new User;
        $user->setFirstName($request->getParam('firstName'));
        $user->setSecondName($request->getParam('secondName'));
        $user->setSurname($request->getParam('surname'));
        $user->setPhoneNo($request->getParam('phoneNo'));
        $user->setEmailAddress($request->getParam('emailAddress'));
        $user->setIdNo($request->getParam('idNo'));
        $user->setNationality($request->getParam('nationality'));
        $user->setGender($request->getParam('gender'));
        $user->setDisabled($request->getParam('disabled'));
        $user->setDob($request->getParam('dob'));
        $user->setCounty($county);
        $user->setConstituency($constituency);
        $user->setRole($role_id);
        $user->setPassword($this->encrypt($request->getParam('password')));
        $user->setDateCreated();
        $user->setDateUpdated();



        /**
         * Persist the user object
         */
        $this->entityManager->persist($user);

        /**
         * commit to the database
         */
        try
        {
            $this->entityManager->flush();
            return array('message' => 'User has been added successfully');
        }
        catch(UniqueConstraintViolationException $e)
        {
            return false;
        }
            return false;
}
   public function resetPassword($request)
   {
       $queryBuilder = $this->entityManager->createQueryBuilder();
       $new_password = substr(md5(uniqid('', true)),0,6);

       $q =  $queryBuilder->update('App\Entity\User','u')
                    ->set('u.password','?1')
                    ->where('u.emailAddress =?2')
                    ->setParameter(1,$this->encrypt($new_password))
                    ->setParameter(2,$request->getParam('emailAddress'))
                    ->getQuery();




       $message = 'Your new temporary password is '.$new_password.'.</br> Kindly ensure you keep it safe. You can change this password once you log into your jigovern account, in your personal profile section. </br> </br> Jigovern Support Team</br>';

       if($q->execute()) :
            $this->sendEmail($request->getParam('emailAddress'),$message);
            return array('status' => 'success',
                         'message' => 'Your new password has been sent to your email');
           endif;

           return false;
   }

    /**
     * @param $resource
     * @param $resourceType
     * @return bool
     */
   public function confirmResource($resource,$resourceType)
   {
       //ensure both the resource and the resource type are provided
       if($resource === null  || $resourceType === null ) : return false; endif;
        $user = $this->entityManager->getRepository(User::class)->findOneBy(array($resourceType,$resource));

        return ($user ? true:false);
   }

    /**
     * @param $request
     * @param $phoneNumber
     * Checks for the posted parameters and creates the update query
     * Runs the update query and returns true if successful
     */
   public function updateProfile($request ,$phoneNumber)
   {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder->update(User::class,'u');
        if($request->getParam('firstName') && strlen($request->getParam('firstName'))>2) :
        $queryBuilder->set('u.firstName',$queryBuilder->expr()->literal($request->getParam('firstName')));
            endif;
       if($request->getParam('secondName') && strlen($request->getParam('secondName'))>2) :
           $queryBuilder->set('u.secondName',$queryBuilder->expr()->literal($request->getParam('secondName')));
       endif;
       if($request->getParam('surname') && strlen($request->getParam('surname'))>2) :
       $queryBuilder->set('u.surname',$queryBuilder->expr()->literal($request->getParam('surname')));
       endif;
       if($request->getParam('emailAddress') && strlen($request->getParam('emailAddress'))>2) :
           $queryBuilder->set('u.emailAddress',$queryBuilder->expr()->literal($request->getParam('emailAddress')));
       endif;
       if($request->getParam('idNo') && strlen($request->getParam('idNo'))>2) :
       $queryBuilder->set('u.idNo',$queryBuilder->expr()->literal($request->getParam('idNo')));
       endif;
       if($request->getParam('gender') && strlen($request->getParam('gender'))>2) :
           $queryBuilder->set('u.gender',$queryBuilder->expr()->literal($request->getParam('gender')));
       endif;
       if($request->getParam('disabled') && strlen($request->getParam('disabled'))>2) :
           $queryBuilder->set('u.disabled',$queryBuilder->expr()->literal($request->getParam('disabled')));
       endif;
       if($request->getParam('dob') && strlen($request->getParam('dob'))>2) :
           $queryBuilder->set('u.dob',$queryBuilder->expr()->literal($request->getParam('dob')));
       endif;
       if($request->getParam('nationality') && strlen($request->getParam('nationality'))>2) :
           $queryBuilder->set('u.nationality',$queryBuilder->expr()->literal($request->getParam('nationality')));
       endif;
       $queryBuilder->where('u.phoneNo = '.$phoneNumber);
        try{

            if($queryBuilder->getQuery()->execute()) :
                return true ;
            endif;

        }
       catch (QueryException $e)
       {
           return false;
       }
   }




}