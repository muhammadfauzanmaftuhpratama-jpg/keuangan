<?php
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
class Guest implements FilterInterface {
    public function before(RequestInterface $request, $arguments = null) {
        if(session()->get('isLoggedIn'))
            return session()->get('role')==='admin'?redirect()->to('/admin/dashboard'):redirect()->to('/user/dashboard');
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}