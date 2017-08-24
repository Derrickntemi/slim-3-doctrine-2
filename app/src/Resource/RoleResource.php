<?php
/**
 * Created by IntelliJ IDEA.
 * User: dkimathi
 * Date: 8/23/2017
 * Time: 12:02 PM
 */

namespace App\Resource;


use App\AbstractResource;
use App\Entity\Role;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\NoResultException;

class RoleResource extends AbstractResource
{

    public function get($roleId =null)
    {
        if($roleId === null)
        {
            $role = $this->entityManager->getRepository(Role::class)->findAll();

            $role = array_map(

                function($role)
                {
                    return $role->getArrayCopy();
                },
                $role
            );
            return $role;
        }

        $role = $this->entityManager->getRepository(Role::class)->findOneBy(array('roleId' => $roleId));

        if($role) :
            return $role->getArrayCopy();
        endif;

        return false;
    }

    public function getObject($roleId = null)
    {
        $role = $this->entityManager->getRepository(Role::class)->findOneBy(array('roleId' => $roleId));

        if($role) :

            return $role;
        endif;
        return false;
    }

    public function checkRole($request)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder->select('r.roleId','r.roleCreatedDate','r.roleUpdatedDate')
                      ->from(Role::class,'r')
                      ->where('r.roleName = \''.$request->getParam('roleName').'\'')
                      ->andWhere('r.roleDescription = \''.$request->getParam('roleDescription').'\'');
        $query = $queryBuilder->getQuery();

        try
        {
            $result = $query->getArrayResult();


            if ($result) :

                return $result;
            endif;
        }

        catch (NoResultException $e)
        {
            //If query returns 0 rows
            return false;
        }
        return false;
    }

    public function insert($request)
    {
    $role = new Role;

    $role->setRoleName($request->getParam('roleName'));
    $role->setRoleDescription($request->getParam('roleDescription'));

    /**
     * persist to the Entity
     */
    $this->entityManager->persist($role);

    try
    {
        $this->entityManager->flush();
        return true;

    }
    catch(UniqueConstraintViolationException $e)
    {
       return false;
    }
        return false;
    }

}