<?php
/**
 * Created by IntelliJ IDEA.
 * User: dkimathi
 * Date: 8/16/2017
 * Time: 6:23 PM
 */

namespace App;

use \Firebase\JWT\JWT;
abstract class AbstractAction
{

    /**
     * @var key to be used for encryption
     */
   private $key = "R@ND0MK3YF0RJ1G0V3RN@P1";

   /**
    * @param data to be encoded
    * @return JWT token
    */
    public function getToken($toBeEncoded)
    {
        return JWT::encode($toBeEncoded,$this->key);

    }

    /**
     * @param request The request object
     * @return the decoded data from the token
     * @throws \UnexpectedValueException
     * @throws \Firebase\JWT\SignatureInvalidException
     * @throws \Firebase\JWT\ExpiredException
     * @throws \Firebase\JWT\BeforeValidException
     * @throws \DomainException
     */
    public function decodedData($request)
    {
        $token = $request->getHeader('Authorization')[0];
        $token_array = explode('Bearer', $token);
        $token = trim($token_array[1]);

        return JWT::decode($token, $this->key, array('HS256'))->data;
    }
}