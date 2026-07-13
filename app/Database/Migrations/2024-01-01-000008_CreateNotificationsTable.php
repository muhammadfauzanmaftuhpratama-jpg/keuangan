<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateNotificationsTable extends Migration {
    public function up(){$this->forge->addField(['id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],'user_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true],'debt_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'null'=>true],'message'=>['type'=>'TEXT'],'is_read'=>['type'=>'TINYINT','constraint'=>1,'default'=>0],'created_at'=>['type'=>'DATETIME','null'=>true]]);$this->forge->addPrimaryKey('id');$this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');$this->forge->createTable('notifications');}
    public function down(){$this->forge->dropTable('notifications');}
}