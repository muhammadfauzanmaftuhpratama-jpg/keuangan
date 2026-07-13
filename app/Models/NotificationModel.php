<?php
namespace App\Models;
use CodeIgniter\Model;
class NotificationModel extends Model {
    protected $table='notifications';
    protected $primaryKey='id';
    protected $allowedFields=['user_id','debt_id','message','is_read'];
    protected $useTimestamps=true;
    protected $updatedField='';
    public function getUnread(int $userId):array{return $this->where('user_id',$userId)->where('is_read',0)->orderBy('created_at','DESC')->findAll();}
    public function countUnread(int $userId):int{return $this->where('user_id',$userId)->where('is_read',0)->countAllResults();}
    public function markAllRead(int $userId):void{$this->where('user_id',$userId)->set('is_read',1)->update();}
}