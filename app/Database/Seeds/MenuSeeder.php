<?php
namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;
class MenuSeeder extends Seeder {
    public function run() {
        $now = date('Y-m-d H:i:s');
        $this->db->table('menus')->insertBatch([
            ['name'=>'Dashboard','slug'=>'admin-dashboard','icon'=>'bi bi-speedometer2','url'=>'/admin/dashboard','parent_id'=>null,'sort_order'=>1,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Manajemen Menu','slug'=>'admin-menu','icon'=>'bi bi-menu-button-wide','url'=>'/admin/menu','parent_id'=>null,'sort_order'=>2,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Manajemen User','slug'=>'admin-users','icon'=>'bi bi-people','url'=>'/admin/users','parent_id'=>null,'sort_order'=>3,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Backup Database','slug'=>'admin-backup','icon'=>'bi bi-database','url'=>'/admin/backup','parent_id'=>null,'sort_order'=>4,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Pemasukan','slug'=>'admin-income','icon'=>'bi bi-arrow-down-circle','url'=>'/admin/income','parent_id'=>null,'sort_order'=>5,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Pengeluaran','slug'=>'admin-expense','icon'=>'bi bi-arrow-up-circle','url'=>'/admin/expense','parent_id'=>null,'sort_order'=>6,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Tabungan','slug'=>'admin-saving','icon'=>'bi bi-piggy-bank','url'=>'/admin/saving','parent_id'=>null,'sort_order'=>7,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Hutang','slug'=>'admin-debt','icon'=>'bi bi-credit-card','url'=>'/admin/debt','parent_id'=>null,'sort_order'=>8,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Laporan','slug'=>'admin-report','icon'=>'bi bi-file-earmark-bar-graph','url'=>'/admin/report','parent_id'=>null,'sort_order'=>9,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Kategori','slug'=>'admin-category','icon'=>'bi bi-tags','url'=>'/admin/category','parent_id'=>null,'sort_order'=>10,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Profil','slug'=>'admin-profile','icon'=>'bi bi-person-circle','url'=>'/admin/profile','parent_id'=>null,'sort_order'=>11,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Dashboard','slug'=>'user-dashboard','icon'=>'bi bi-house','url'=>'/user/dashboard','parent_id'=>null,'sort_order'=>1,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Pemasukan','slug'=>'user-income','icon'=>'bi bi-arrow-down-circle','url'=>'/user/income','parent_id'=>null,'sort_order'=>2,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Pengeluaran','slug'=>'user-expense','icon'=>'bi bi-arrow-up-circle','url'=>'/user/expense','parent_id'=>null,'sort_order'=>3,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Tabungan','slug'=>'user-saving','icon'=>'bi bi-piggy-bank','url'=>'/user/saving','parent_id'=>null,'sort_order'=>4,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Hutang','slug'=>'user-debt','icon'=>'bi bi-credit-card','url'=>'/user/debt','parent_id'=>null,'sort_order'=>5,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Laporan','slug'=>'user-report','icon'=>'bi bi-file-earmark-bar-graph','url'=>'/user/report','parent_id'=>null,'sort_order'=>6,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Kategori','slug'=>'user-category','icon'=>'bi bi-tags','url'=>'/user/category','parent_id'=>null,'sort_order'=>7,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Profil','slug'=>'user-profile','icon'=>'bi bi-person-circle','url'=>'/user/profile','parent_id'=>null,'sort_order'=>8,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
        ]);
        echo "MenuSeeder done\n";
    }
}