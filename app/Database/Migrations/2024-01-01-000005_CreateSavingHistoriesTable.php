<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateSavingHistoriesTable extends Migration {
    public function up(){$this->forge->addField(['id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],'saving_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true],'user_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true],'type'=>['type'=>'ENUM','constraint'=>['deposit','withdraw']],'amount'=>['type'=>'DECIMAL','constraint'=>'15,2'],'note'=>['type'=>'TEXT','null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true]]);$this->forge->addPrimaryKey('id');$this->forge->addForeignKey('saving_id','savings','id','CASCADE','CASCADE');$this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');$this->forge->createTable('saving_histories');}
    public function down(){$this->forge->dropTable('saving_histories');}
}