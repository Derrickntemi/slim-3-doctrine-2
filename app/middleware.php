<?php


use \Firebase\JWT\JWT;


// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);


//JWT Middleware

/**
 * @param $request
 * @param $response
 * @param $next
 * @return mixed
 */



$jwtMw = function ($request, $response, $next) {
    if(isset($request->getHeader('Authorization')[0]))://if there is a header
        $token = $request->getHeader('Authorization')[0];

        $token_array = explode('Bearer', $token);

        $token = trim($token_array[1]);

    elseif($request->getParam('access_token')): //if it was sent via the post channel
        $token = $request->getParam('access_token');
    else:
        $token = ""; //defaults to an empty string
    endif;
    $key = "R@NDOMS3cUr3KEY@L3RTF0RTUL1P@";

    //$jwt = $this->jwtoken;


    try{

        $decoded = JWT::decode($token, $key, array('HS256'));
        print_r($decoded); exit;
        $response = $next($request, $response);

    }catch (Exception $e) {

        $response_data = array('error'=> 0,'error' => 'Signature Verification Failed');
        $response = $response->withHeader('Content-type', 'application/json')->withJson($response_data, 403);

    }
    return $response;
};