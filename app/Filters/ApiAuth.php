<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ApiAuth implements FilterInterface
{
    // Token sederhana untuk autentikasi API
    protected $validToken = 'my-secret-token';

    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (empty($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status'  => 401,
                    'message' => 'Unauthorized. Token is required.',
                ]);
        }

        $token = substr($authHeader, 7); // Ambil token setelah "Bearer "

        if ($token !== $this->validToken) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status'  => 401,
                    'message' => 'Unauthorized. Invalid token.',
                ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
