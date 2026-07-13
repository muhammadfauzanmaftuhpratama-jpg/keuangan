<?php
namespace App\Models;
use CodeIgniter\Model;
class MenuModel extends Model {
    protected $table='menus';
    protected $primaryKey='id';
    protected $allowedFields=['name','slug','icon','url','parent_id','sort_order','is_active'];
    protected $useTimestamps=true;
    public function getActiveMenus(string $role='user'):array{$prefix=$role==='admin'?'admin-':'user-';return $this->where('is_active',1)->where('parent_id',null)->like('slug',$prefix,'after')->orderBy('sort_order','ASC')->findAll();}
    public function getSubMenus(int $parentId):array{return $this->where('parent_id',$parentId)->where('is_active',1)->orderBy('sort_order','ASC')->findAll();}
}