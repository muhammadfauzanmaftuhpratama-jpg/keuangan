<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateCategoriesTable extends Migration {
    public function up(){$this->forge->addField(['id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],'user_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true],'type'=>['type'=>'ENUM','constraint'=>['income','expense']],'name'=>['type'=>'VARCHAR','constraint'=>100],'icon'=>['type'=>'VARCHAR','constraint'=>50,'default'=>'bi bi-tag'],'color'=>['type'=>'VARCHAR','constraint'=>20,'default'=>'#667eea'],'is_default'=>['type'=>'TINYINT','constraint'=>1,'default'=>0],'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true]]);$this->forge->addPrimaryKey('id');$this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');$this->forge->createTable('categories');}
    public function down(){$this->forge->dropTable('categories');}
}