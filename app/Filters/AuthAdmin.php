<?php
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
class AuthAdmin implements FilterInterface {
    public function before(RequestInterface $request, $arguments = null) {
        if(!session()->get('isLoggedIn')) return redirect()->to('/login')->with('error','Silakan login terlebih dahulu.');
        if(session()->get('role')!=='admin') return redirect()->to('/user/dashboard')->with('error','Akses ditolak!');
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}