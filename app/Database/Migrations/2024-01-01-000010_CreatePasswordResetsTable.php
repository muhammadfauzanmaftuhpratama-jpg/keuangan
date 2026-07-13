<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreatePasswordResetsTable extends Migration {
    public function up(){$this->forge->addField(['id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],'email'=>['type'=>'VARCHAR','constraint'=>150],'token'=>['type'=>'VARCHAR','constraint'=>100],'expired_at'=>['type'=>'DATETIME'],'created_at'=>['type'=>'DATETIME','null'=>true]]);$this->forge->addPrimaryKey('id');$this->forge->addKey('token');$this->forge->createTable('password_resets');}
    public function down(){$this->forge->dropTable('password_resets');}
}