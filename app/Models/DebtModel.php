<?php
namespace App\Models;
use CodeIgniter\Model;
class DebtModel extends Model {
    protected $table='debts';
    protected $primaryKey='id';
    protected $allowedFields=['user_id','creditor_name','total_amount','remaining_amount','due_date','status','note'];
    protected $useTimestamps=true;
    public function getUserDebts(int $userId):array{return $this->where('user_id',$userId)->orderBy('due_date','ASC')->findAll();}
    public function getTotalDebt(int $userId):float{$row=$this->selectSum('remaining_amount')->where('user_id',$userId)->where('status','unpaid')->get()->getRow();return $row&&$row->remaining_amount!==null?(float)$row->remaining_amount:0.0;}
    public function getDueSoonDebts(int $userId):array{return $this->where('user_id',$userId)->where('status','unpaid')->where('due_date <=',date('Y-m-d',strtotime('+7 days')))->where('due_date >=',date('Y-m-d'))->findAll();}
}