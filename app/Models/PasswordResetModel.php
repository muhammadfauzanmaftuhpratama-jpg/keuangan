<?php
namespace App\Models;
use CodeIgniter\Model;
class PasswordResetModel extends Model {
    protected $table='password_resets';
    protected $primaryKey='id';
    protected $allowedFields=['email','token','expired_at','created_at'];
    protected $useTimestamps=false;
    public function createToken(string $email):string{$this->where('email',$email)->delete();$raw=bin2hex(random_bytes(32));$this->insert(['email'=>strtolower(trim($email)),'token'=>hash('sha256',$raw),'expired_at'=>date('Y-m-d H:i:s',strtotime('+1 hour')),'created_at'=>date('Y-m-d H:i:s')]);return $raw;}
    public function validateToken(string $raw):?array{$raw=preg_replace('/[^a-f0-9]/','', $raw);if(empty($raw)||strlen($raw)!==64)return null;return $this->where('token',hash('sha256',$raw))->where('expired_at >=',date('Y-m-d H:i:s'))->first();}
    public function deleteToken(string $raw):void{$this->where('token',hash('sha256',$raw))->delete();}
}