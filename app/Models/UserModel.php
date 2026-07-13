<?php
namespace App\Models;
use CodeIgniter\Model;
class UserModel extends Model {
    protected $table='users';
    protected $primaryKey='id';
    protected $allowedFields=['name','email','password','role','phone','avatar','currency','last_login'];
    protected $useTimestamps=true;
    public function findByEmail(string $email):?array{return $this->where('email',$email)->first();}
    public function updateLastLogin(int $id):void{$this->update($id,['last_login'=>date('Y-m-d H:i:s')]);}
    public function updateProfile(int $id,array $data):bool{return $this->update($id,$data);}
    public function changePassword(int $id,string $pw):bool{return $this->update($id,['password'=>password_hash($pw,PASSWORD_DEFAULT)]);}
    public function verifyPassword(int $id,string $pw):bool{$u=$this->find($id);return $u&&password_verify($pw,$u['password']);}
}