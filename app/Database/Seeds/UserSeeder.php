<?php
namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;
class UserSeeder extends Seeder {
    public function run() {
        $this->db->table('users')->insertBatch([
            ['name'=>'Administrator','email'=>'admin@keuangan.com','password'=>password_hash('admin123',PASSWORD_DEFAULT),'role'=>'admin','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['name'=>'User Demo','email'=>'user@keuangan.com','password'=>password_hash('user123',PASSWORD_DEFAULT),'role'=>'user','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
        ]);
        echo "UserSeeder done\n";
    }
}