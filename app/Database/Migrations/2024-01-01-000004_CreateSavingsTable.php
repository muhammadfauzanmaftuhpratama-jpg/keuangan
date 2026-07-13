<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateSavingsTable extends Migration {
    public function up(){$this->forge->addField(['id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],'user_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true],'name'=>['type'=>'VARCHAR','constraint'=>100],'target_amount'=>['type'=>'DECIMAL','constraint'=>'15,2','default'=>0],'current_amount'=>['type'=>'DECIMAL','constraint'=>'15,2','default'=>0],'deadline'=>['type'=>'DATE','null'=>true],'note'=>['type'=>'TEXT','null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true]]);$this->forge->addPrimaryKey('id');$this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');$this->forge->createTable('savings');}
    public function down(){$this->forge->dropTable('savings');}
}