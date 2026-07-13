<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateDebtPaymentsTable extends Migration {
    public function up(){$this->forge->addField(['id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],'debt_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true],'user_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true],'amount'=>['type'=>'DECIMAL','constraint'=>'15,2'],'payment_date'=>['type'=>'DATE'],'note'=>['type'=>'TEXT','null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true]]);$this->forge->addPrimaryKey('id');$this->forge->addForeignKey('debt_id','debts','id','CASCADE','CASCADE');$this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');$this->forge->createTable('debt_payments');}
    public function down(){$this->forge->dropTable('debt_payments');}
}