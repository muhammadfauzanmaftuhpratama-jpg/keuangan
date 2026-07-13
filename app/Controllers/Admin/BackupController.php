<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
class BackupController extends BaseController {
    protected $backupPath;
    public function __construct(){$this->backupPath=WRITEPATH.'backups/';if(!is_dir($this->backupPath))mkdir($this->backupPath,0755,true);}
    public function index(){return view('admin/backup/index',['title'=>'Backup Database','files'=>$this->getFiles()]);}
    public function download(){
        $db=\Config\Database::connect();
        $sql="-- Backup: {$db->getDatabase()}\n-- Date: ".date('Y-m-d H:i:s')."\nSET FOREIGN_KEY_CHECKS=0;\n\n";
        foreach($db->listTables() as $tbl)$sql.=$this->dumpTable($db,$tbl);
        $sql.="SET FOREIGN_KEY_CHECKS=1;\n";
        $fn='backup_'.$db->getDatabase().'_'.date('Ymd_His').'.sql';
        file_put_contents($this->backupPath.$fn,$sql);
        return $this->response->setHeader('Content-Type','application/octet-stream')->setHeader('Content-Disposition','attachment; filename="'.$fn.'"')->setHeader('Content-Length',filesize($this->backupPath.$fn))->setBody(file_get_contents($this->backupPath.$fn));
    }
    public function deleteFile(string $fn){
        $fn=basename($fn);if(!str_ends_with($fn,'.sql'))return redirect()->to('/admin/backup')->with('error','File tidak valid!');
        $fp=$this->backupPath.$fn;if(file_exists($fp)){unlink($fp);return redirect()->to('/admin/backup')->with('success','File backup dihapus!');}
        return redirect()->to('/admin/backup')->with('error','File tidak ditemukan!');
    }
    private function dumpTable($db,string $tbl):string{
        $sql="-- Table: `{$tbl}`\nDROP TABLE IF EXISTS `{$tbl}`;\n";
        $cr=$db->query("SHOW CREATE TABLE `{$tbl}`")->getRow();
        $sql.=($cr->{'Create Table'}??'').";\n\n";
        $rows=$db->query("SELECT * FROM `{$tbl}`")->getResultArray();
        if($rows){$cols='`'.implode('`, `',array_keys($rows[0])).'`';$sql.="INSERT INTO `{$tbl}` ({$cols}) VALUES\n";$vals=[];foreach($rows as $r){$esc=array_map(fn($v)=>$v===null?'NULL':"'".$db->escapeStr($v)."'",array_values($r));$vals[]='('.implode(', ',$esc).')';}$sql.=implode(",\n",$vals).";\n\n";}
        return $sql;
    }
    private function getFiles():array{$files=glob($this->backupPath.'*.sql');if(!$files)return[];$r=[];foreach($files as $f)$r[]=['name'=>basename($f),'size'=>$this->fmtSize(filesize($f)),'created'=>date('d M Y H:i',filemtime($f)),'filepath'=>$f];usort($r,fn($a,$b)=>filemtime($b['filepath'])-filemtime($a['filepath']));return $r;}
    private function fmtSize(int $b):string{if($b>=1048576)return round($b/1048576,2).' MB';if($b>=1024)return round($b/1024,2).' KB';return $b.' B';}
}
