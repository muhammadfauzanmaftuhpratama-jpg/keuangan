<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateLoginAttemptsTable extends Migration {
    public function up(){$this->forge->addField(['id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],'ip_address'=>['type'=>'VARCHAR','constraint'=>45],'email'=>['type'=>'VARCHAR','constraint'=>150,'null'=>true],'attempted_at'=>['type'=>'DATETIME','null'=>true]]);$this->forge->addPrimaryKey('id');$this->forge->addKey('ip_address');$this->forge->createTable('login_attempts');}
    public function down(){$this->forge->dropTable('login_attempts');}
}