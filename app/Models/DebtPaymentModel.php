<?php
namespace App\Models;
use CodeIgniter\Model;
class DebtPaymentModel extends Model {
    protected $table='debt_payments';
    protected $primaryKey='id';
    protected $allowedFields=['debt_id','user_id','amount','payment_date','note'];
    protected $useTimestamps=true;
    protected $updatedField='';
    public function getPaymentsByDebt(int $id):array{return $this->where('debt_id',$id)->orderBy('payment_date','DESC')->findAll();}
}