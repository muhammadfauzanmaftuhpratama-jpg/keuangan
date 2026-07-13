<?php
namespace App\Models;
use CodeIgniter\Model;
class CategoryModel extends Model {
    protected $table='categories';
    protected $primaryKey='id';
    protected $allowedFields=['user_id','type','name','icon','color','is_default'];
    protected $useTimestamps=true;
    public static function defaultIncomeCategories():array{return[['name'=>'Gaji','icon'=>'bi bi-briefcase','color'=>'#10b981'],['name'=>'Freelance','icon'=>'bi bi-laptop','color'=>'#3b82f6'],['name'=>'Bisnis','icon'=>'bi bi-shop','color'=>'#8b5cf6'],['name'=>'Investasi','icon'=>'bi bi-graph-up','color'=>'#f59e0b'],['name'=>'Hadiah','icon'=>'bi bi-gift','color'=>'#ec4899'],['name'=>'Bonus','icon'=>'bi bi-star','color'=>'#14b8a6'],['name'=>'Lainnya','icon'=>'bi bi-three-dots','color'=>'#6b7280']];}
    public static function defaultExpenseCategories():array{return[['name'=>'Makanan & Minuman','icon'=>'bi bi-cup-hot','color'=>'#ef4444'],['name'=>'Transportasi','icon'=>'bi bi-car-front','color'=>'#f97316'],['name'=>'Belanja','icon'=>'bi bi-bag','color'=>'#a855f7'],['name'=>'Tagihan','icon'=>'bi bi-receipt','color'=>'#6366f1'],['name'=>'Kesehatan','icon'=>'bi bi-heart-pulse','color'=>'#ef4444'],['name'=>'Pendidikan','icon'=>'bi bi-book','color'=>'#0ea5e9'],['name'=>'Hiburan','icon'=>'bi bi-controller','color'=>'#f43f5e'],['name'=>'Investasi','icon'=>'bi bi-graph-up-arrow','color'=>'#22c55e'],['name'=>'Lainnya','icon'=>'bi bi-three-dots','color'=>'#6b7280']];}
    public function getUserCategories(int $userId,string $type):array{return $this->where('user_id',$userId)->where('type',$type)->orderBy('is_default','DESC')->orderBy('name','ASC')->findAll();}
    public function getCategoryNames(int $userId,string $type):array{$rows=$this->select('name')->where('user_id',$userId)->where('type',$type)->orderBy('is_default','DESC')->orderBy('name','ASC')->findAll();return array_column($rows,'name');}
    public function initDefaultCategories(int $userId):bool{
        $existing=$this->where('user_id',$userId)->countAllResults();if($existing>0)return true;
        $data=[];foreach(self::defaultIncomeCategories() as $cat)$data[]=array_merge($cat,['user_id'=>$userId,'type'=>'income','is_default'=>1]);
        foreach(self::defaultExpenseCategories() as $cat)$data[]=array_merge($cat,['user_id'=>$userId,'type'=>'expense','is_default'=>1]);
        try{$r=$this->insertBatch($data);if($r===false){log_message('error',"CategoryModel: insertBatch failed uid={$userId}");return false;}return true;}catch(\Exception $e){log_message('error',"CategoryModel: error uid={$userId}: ".$e->getMessage());return false;}
    }
}