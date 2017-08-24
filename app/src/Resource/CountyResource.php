<?php
/**
 * Created by IntelliJ IDEA.
 * User: dkimathi
 * Date: 8/22/2017
 * Time: 5:20 PM
 */

namespace App\Resource;


use App\AbstractResource;
use App\Entity\County;

class CountyResource extends AbstractResource
{
    public function get($countyCode = null)
    {
        if($countyCode === null)
        {
            $county = $this->entityManager->getRepository(County::class)->findAll();
            $county = array_map(
                function($county)
                {
                    return $county->getArrayCopy();
                },
                $county
            );
            return $county;
        }



        $county = $this->entityManager->getRepository(County::class)->findOneBy(array('countyCode' => $countyCode));

        return($county ? $county->getArrayCopy(): false);




    }

    public function getObject($countyId= null)
{
    $county = $this->entityManager->getRepository(County::class)->findOneBy(array('countyId' => $countyId));
    if($county) :
        return $county;
    endif;

    return false;
}
}