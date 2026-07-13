<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\UserModel;
class UserController extends BaseController {
    protected $userModel;
    public function __construct(){$this->userModel=new UserModel();}
    public function index(){return view('admin/users/index',['title'=>'Manajemen User','users'=>$this->userModel->orderBy('created_at','DESC')->findAll()]);}
    public function delete(int $id){
        if($id==session()->get('user_id'))return redirect()->to('/admin/users')->with('error','Tidak bisa menghapus akun sendiri!');
        if(!$this->userModel->find($id))return redirect()->to('/admin/users')->with('error','User tidak ditemukan!');
        $this->userModel->delete($id);
        return redirect()->to('/admin/users')->with('success','User berhasil dihapus!');
    }
}
