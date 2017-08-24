<?php
namespace App;

use Doctrine\ORM\EntityManager;
use \Firebase\JWT\JWT;
use \PHPMailer;

abstract class AbstractResource
{

    /**
     * @var $key to be used for encryption of JWT token
     */
    private $key = "R@ND0MK3YF0RJ1G0V3RN@P1";

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager = null;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param toBeEncodedData The data that is to be encoded
     * @return the JWT token
     */
    public function getToken($toBeEncodedData)
    {
        return JWT::encode($toBeEncodedData, $this->key);
    }

    /**
     * @param request The request object
     * @return the decoded data from the token
     */
    public function decodedData($request)
    {
        $token = $request->getHeader('Authorization')[0];
        $token_array = explode('Bearer', $token);
        $token = trim($token_array[1]);

        return JWT::decode($token, $this->key, array('HS256'))->data;
    }
    /**
     * given a password, it returns the encrypted value of it
     * @param  string $password - user password
     * @return string           the encryption value
     */
    public function encrypt($password)
    {
        return hash_hmac('sha256', $password, $this->key);
    }

    public function sendEmail($email_Address,$message)
    {
        $mail = new PHPMailer;



        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'derrickntemi@gmail.com';                 // SMTP username
        $mail->Password = 'K1m@7h1D3r';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom('derrickntemi@gmail.com', 'Mailer');
        $mail->addAddress('dkimathi@webtribe.co.ke', 'Joe User');     // Add a recipient
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'JIGOVERN PASSWORD RESET';
        $mail->Body    = $message;



        if(!$mail->send()) {
           return false;
        } else {
            return true;
        }
    }

}
