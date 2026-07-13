<?php
namespace App\Models;
use CodeIgniter\Model;
class TransactionModel extends Model {
    protected $table='transactions';
    protected $primaryKey='id';
    protected $allowedFields=['user_id','type','category','amount','date','note'];
    protected $useTimestamps=true;
    protected $updatedField='';
    public function getTotalByType(int $userId,string $type,string $month,string $year):float{
        $month=str_pad((int)$month,2,'0',STR_PAD_LEFT);$year=(int)$year;
        if($month<1||$month>12||$year<2000||$year>2100)return 0.0;
        $row=$this->selectSum('amount')->where('user_id',$userId)->where('type',$type)->where('MONTH(date)',$month)->where('YEAR(date)',$year)->get()->getRow();
        return $row&&$row->amount!==null?(float)$row->amount:0.0;
    }
    public function getTotalByDateRange(int $userId,string $type,string $start,string $end):float{
        $row=$this->selectSum('amount')->where('user_id',$userId)->where('type',$type)->where('date >=',$start)->where('date <=',$end)->get()->getRow();
        return $row&&$row->amount!==null?(float)$row->amount:0.0;
    }
    public function getRecentTransactions(int $userId,int $limit=5):array{return $this->where('user_id',$userId)->orderBy('date','DESC')->orderBy('id','DESC')->limit($limit)->findAll();}
    public function getByMonthAndType(int $userId,string $type,string $month,string $year):array{
        $month=str_pad((int)$month,2,'0',STR_PAD_LEFT);$year=(int)$year;
        if($month<1||$month>12||$year<2000||$year>2100)return[];
        return $this->where('user_id',$userId)->where('type',$type)->where('MONTH(date)',$month)->where('YEAR(date)',$year)->orderBy('date','DESC')->findAll();
    }
    public function getByMonth(int $userId,string $month,string $year):array{return $this->where('user_id',$userId)->where('MONTH(date)',$month)->where('YEAR(date)',$year)->orderBy('date','DESC')->findAll();}
}