<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateMenusTable extends Migration {
    public function up(){$this->forge->addField(['id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],'name'=>['type'=>'VARCHAR','constraint'=>100],'slug'=>['type'=>'VARCHAR','constraint'=>100],'icon'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true,'default'=>'bi bi-circle'],'url'=>['type'=>'VARCHAR','constraint'=>200,'null'=>true],'parent_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'null'=>true,'default'=>null],'sort_order'=>['type'=>'INT','constraint'=>11,'default'=>0],'is_active'=>['type'=>'TINYINT','constraint'=>1,'default'=>1],'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true]]);$this->forge->addPrimaryKey('id');$this->forge->createTable('menus');}
    public function down(){$this->forge->dropTable('menus');}
}