<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateDebtsTable extends Migration {
    public function up(){$this->forge->addField(['id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],'user_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true],'creditor_name'=>['type'=>'VARCHAR','constraint'=>100],'total_amount'=>['type'=>'DECIMAL','constraint'=>'15,2'],'remaining_amount'=>['type'=>'DECIMAL','constraint'=>'15,2'],'due_date'=>['type'=>'DATE','null'=>true],'status'=>['type'=>'ENUM','constraint'=>['unpaid','paid'],'default'=>'unpaid'],'note'=>['type'=>'TEXT','null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true]]);$this->forge->addPrimaryKey('id');$this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');$this->forge->createTable('debts');}
    public function down(){$this->forge->dropTable('debts');}
}