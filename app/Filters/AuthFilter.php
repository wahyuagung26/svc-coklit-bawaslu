<?php

namespace App\Filters;

use App\Libraries\JwtAuth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Throwable;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeader("Authorization");
        $token = null;
  
        // extract the token from the header
        if(!empty($header)) {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        }
  
        // check if token is null or empty
        if(is_null($token) || empty($token)) {
            $response = service('response');
            $response->setStatusCode(401)->setJSON([
                'messages' => 'Unauthorized request'
            ]);
            return $response;
        }
  
        try {
            $decode = JWT::decode($token, new Key(getenv('JWT_SECRET'), getenv('JWT_ALGO')));
            JwtAuth::setUserId($decode->id ?? '');
        } catch (Throwable $ex) {
            $response = service('response');
            $response->setStatusCode(401)->setJSON([
                'messages' => 'Unauthorized request'
            ]);
            return $response;
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
