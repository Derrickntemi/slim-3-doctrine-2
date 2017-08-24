<?php
/**
 * Created by IntelliJ IDEA.
 * User: dkimathi
 * Date: 8/22/2017
 * Time: 6:55 PM
 */

namespace App\Resource;


use App\AbstractResource;
use App\Entity\Constituency;

class ConstituencyResource extends AbstractResource
{

    public function get($constituencyId = null)
{
    if($constituencyId === null) :

        $constituency = $this->entityManager->getRepository(Constituency::class)->findAll();

        $constituency = array_map(

            function($constituency)
            {
                return $constituency->getArrayCopy();
            },
            $constituency

        );
        return $constituency;
        endif;

        $constituency = $this->entityManager->getRepository(Constituency::class)->findOneBy(array('constituencyId' => $constituencyId));

        return ($constituency ? $constituency->getArrayCopy(): false );

}

    public function getObject($constituencyId = null)
    {
        $constituency = $this->entityManager->getRepository(Constituency::class)->findOneBy(array('constituencyId' => $constituencyId));

        if($constituency) :

            return $constituency;
        endif;
        return false;
    }
}