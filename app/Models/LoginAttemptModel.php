<?php
namespace App\Models;
use CodeIgniter\Model;
class LoginAttemptModel extends Model {
    protected $table='login_attempts';
    protected $primaryKey='id';
    protected $allowedFields=['ip_address','email','attempted_at'];
    protected $useTimestamps=false;
    public function logAttempt(string $ip,string $email):void{$this->insert(['ip_address'=>$ip,'email'=>strtolower(trim($email)),'attempted_at'=>date('Y-m-d H:i:s')]);}
    public function countRecentAttempts(string $ip,int $minutes=15):int{return $this->where('ip_address',$ip)->where('attempted_at >=',date('Y-m-d H:i:s',strtotime("-{$minutes} minutes")))->countAllResults();}
    public function clearAttemptsByIp(string $ip,int $minutes=15):void{$this->where('ip_address',$ip)->where('attempted_at >=',date('Y-m-d H:i:s',strtotime("-{$minutes} minutes")))->delete();}
    public function clearOldAttempts(int $minutes=15):void{$this->where('attempted_at <',date('Y-m-d H:i:s',strtotime("-{$minutes} minutes")))->delete();}
    public function isBlocked(string $ip,int $max=5,int $minutes=15):bool{return $this->countRecentAttempts($ip,$minutes)>=$max;}
}