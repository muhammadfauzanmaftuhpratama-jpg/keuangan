<?php
namespace App\Models;
use CodeIgniter\Model;
class SavingHistoryModel extends Model {
    protected $table='saving_histories';
    protected $primaryKey='id';
    protected $allowedFields=['saving_id','user_id','type','amount','note'];
    protected $useTimestamps=true;
    protected $updatedField='';
    public function getHistoryBySaving(int $id):array{return $this->where('saving_id',$id)->orderBy('created_at','DESC')->findAll();}
}