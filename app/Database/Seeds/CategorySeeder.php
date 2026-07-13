<?php
namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;
use App\Models\CategoryModel;
class CategorySeeder extends Seeder {
    public function run() {
        $categoryModel = new CategoryModel();
        $users = $this->db->table('users')->get()->getResultArray();
        foreach ($users as $user) {
            $exists = $this->db->table('categories')->where('user_id',$user['id'])->countAllResults();
            if ($exists == 0) {
                $categoryModel->initDefaultCategories($user['id']);
                echo "Categories initialized for: {$user['email']}\n";
            }
        }
    }
}