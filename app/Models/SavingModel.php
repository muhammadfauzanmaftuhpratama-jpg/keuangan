<?php
namespace App\Models;
use CodeIgniter\Model;
class SavingModel extends Model {
    protected $table='savings';
    protected $primaryKey='id';
    protected $allowedFields=['user_id','name','target_amount','current_amount','deadline','note'];
    protected $useTimestamps=true;
    public function getUserSavings(int $userId):array{return $this->where('user_id',$userId)->orderBy('created_at','DESC')->findAll();}
    public function getTotalSavings(int $userId):float{$row=$this->selectSum('current_amount')->where('user_id',$userId)->get()->getRow();return $row&&$row->current_amount!==null?(float)$row->current_amount:0.0;}
}