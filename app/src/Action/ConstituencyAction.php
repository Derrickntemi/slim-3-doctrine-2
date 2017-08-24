<?php
/**
 * Created by IntelliJ IDEA.
 * User: dkimathi
 * Date: 8/22/2017
 * Time: 7:10 PM
 */

namespace App\Action;


use App\AbstractAction;
use App\Resource\ConstituencyResource;
use Psr\Log\LoggerInterface;



class ConstituencyAction extends AbstractAction
{


    /**
     * @property ConstituencyResource constituencyResource
     * @param ConstituencyResource $constituencyResource
     * @param LoggerInterface $logger
     * @property ConstituencyResource constituencyResource
     * @property LoggerInterface logger
     * Constructor to intialize all needed objects in this class
     */

    public function __construct(ConstituencyResource $constituencyResource, LoggerInterface $logger)
    {
        $this->constituencyResource = $constituencyResource;
        $this->logger = $logger;
    }

    /**
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     * Function to get all constituencies
     */

    public function fetch($request , $response, $args)
    {
        $constituencies = $this->constituencyResource->get();

        if($constituencies) :
        return $response->withJson($constituencies);
        endif;
        return $response->withStatus(400)
                        ->withJson(array('error' => 'No constituencies found ,try again'));
    }

    public function fetchOne($request, $response , $args)
    {
        $constituency = $this->constituencyResource->get($args['constituencyId']);
        if($constituency) :
            return $response->withJson($constituency);
        endif;

        return $response->withStatus(400)
            ->withJson(array('error' => 'No constituency found with that county code'));

    }


}