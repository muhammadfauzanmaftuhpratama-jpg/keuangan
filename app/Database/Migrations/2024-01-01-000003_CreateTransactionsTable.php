<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateTransactionsTable extends Migration {
    public function up(){$this->forge->addField(['id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],'user_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true],'type'=>['type'=>'ENUM','constraint'=>['income','expense']],'category'=>['type'=>'VARCHAR','constraint'=>100],'amount'=>['type'=>'DECIMAL','constraint'=>'15,2'],'date'=>['type'=>'DATE'],'note'=>['type'=>'TEXT','null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true]]);$this->forge->addPrimaryKey('id');$this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');$this->forge->createTable('transactions');}
    public function down(){$this->forge->dropTable('transactions');}
}