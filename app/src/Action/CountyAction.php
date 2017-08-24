<?php
/**
 * Created by IntelliJ IDEA.
 * User: dkimathi
 * Date: 8/22/2017
 * Time: 5:58 PM
 */

namespace App\Action;


use App\AbstractAction;
use App\Resource\CountyResource;
use Psr\Log\LoggerInterface;


/**
 * @property CountyResource countyresource
 * @property LoggerInterface logger
 */
class CountyAction extends AbstractAction
{
    /**
     * Constructor to intialize all needed objects
     * @property CountyResource countyresource
     * @property LoggerInterface logger
     * @param CountyResource $countyresource
     * @param LoggerInterface $logger
     */

    public function __construct(CountyResource $countyResource, LoggerInterface $logger)
    {
        $this->countyResource = $countyResource;
        $this->logger = $logger;

    }

    /**
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     * function to get all counties
     */

    public function fetch($request, $response, $args)
    {
        $counties = $this->countyResource->get();

        if($counties) :
            return $response->withJson($counties);
        endif;

        return $response->withStatus(400)
                        ->withJson(array('error' => 'No counties found,try again')
                                        );
    }

    /**
     * @param $request
     * @param $response
     * @param $args
     * Function to get one county by the county code
     */
    public function fetchOne($request,$response,$args)
    {
        $county = $this->countyResource->get($args['countyCode']);

        if($county) :
            return $response->withJson($county);
        endif;

        return $response->withStatus(400)
                        ->withJson(array('error' => 'No county found with that county code'));

    }
}