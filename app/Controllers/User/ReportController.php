<?php
namespace App\Controllers\User;
use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\SavingModel;
use App\Models\DebtModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportController extends BaseController {
    protected $t;protected $s;protected $d;
    public function __construct(){$this->t=new TransactionModel();$this->s=new SavingModel();$this->d=new DebtModel();}
    private function uid():int{return (int)session()->get('user_id');}
    private function getData(int $uid,string $m,string $y):array{return['month'=>$m,'year'=>$y,'totalIncome'=>$this->t->getTotalByType($uid,'income',$m,$y),'totalExpense'=>$this->t->getTotalByType($uid,'expense',$m,$y),'incomes'=>$this->t->getByMonthAndType($uid,'income',$m,$y),'expenses'=>$this->t->getByMonthAndType($uid,'expense',$m,$y),'savings'=>$this->s->getUserSavings($uid),'debts'=>$this->d->getUserDebts($uid),'totalSaving'=>$this->s->getTotalSavings($uid),'totalDebt'=>$this->d->getTotalDebt($uid)];}

    public function index(){
        $uid=$this->uid();$m=sanitize_month($this->request->getGet('month'));$y=sanitize_year($this->request->getGet('year'));
        $data=$this->getData($uid,$m,$y);
        $pm=date('m',mktime(0,0,0,(int)$m-1,1,(int)$y));$py=date('Y',mktime(0,0,0,(int)$m-1,1,(int)$y));
        $pi=$this->t->getTotalByType($uid,'income',$pm,$py);$pe=$this->t->getTotalByType($uid,'expense',$pm,$py);
        return view('user/report/index',array_merge($data,['title'=>'Laporan Keuangan','monthName'=>date('F',mktime(0,0,0,(int)$m,1)),'prevMonthName'=>date('F',mktime(0,0,0,(int)$pm,1)),'prevIncome'=>$pi,'prevExpense'=>$pe,'incomeChange'=>$pi>0?round((($data['totalIncome']-$pi)/$pi)*100,1):0,'expenseChange'=>$pe>0?round((($data['totalExpense']-$pe)/$pe)*100,1):0]));
    }

    public function yearly(){
        $uid=$this->uid();$y=sanitize_year($this->request->getGet('year'));
        $md=[];$ti=$te=0;
        for($m=1;$m<=12;$m++){$mo=str_pad($m,2,'0',STR_PAD_LEFT);$i=$this->t->getTotalByType($uid,'income',$mo,$y);$e=$this->t->getTotalByType($uid,'expense',$mo,$y);$md[]=['month'=>$m,'month_name'=>date('F',mktime(0,0,0,$m,1)),'income'=>$i,'expense'=>$e,'balance'=>$i-$e];$ti+=$i;$te+=$e;}
        return view('user/report/yearly',['title'=>'Laporan Tahunan '.$y,'year'=>$y,'monthlyData'=>$md,'totalYearIncome'=>$ti,'totalYearExpense'=>$te,'totalYearBalance'=>$ti-$te,'chartLabels'=>json_encode(array_column($md,'month_name')),'chartIncome'=>json_encode(array_column($md,'income')),'chartExpense'=>json_encode(array_column($md,'expense'))]);
    }

    public function exportCsv(){
        $uid=$this->uid();$m=sanitize_month($this->request->getGet('month'));$y=sanitize_year($this->request->getGet('year'));
        $mn=date('F',mktime(0,0,0,(int)$m,1));$data=$this->getData($uid,$m,$y);
        header('Content-Type: text/csv; charset=UTF-8');header('Content-Disposition: attachment; filename="laporan-'.$mn.'-'.$y.'.csv"');header('Cache-Control: max-age=0');
        $out=fopen('php://output','w');fputs($out,chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($out,['LAPORAN KEUANGAN - '.strtoupper($mn.' '.$y)]);fputcsv($out,['Diekspor: '.date('d/m/Y H:i')]);fputcsv($out,[]);
        fputcsv($out,['RINGKASAN']);fputcsv($out,['Total Pemasukan',$data['totalIncome']]);fputcsv($out,['Total Pengeluaran',$data['totalExpense']]);fputcsv($out,['Saldo',$data['totalIncome']-$data['totalExpense']]);fputcsv($out,['Total Tabungan',$data['totalSaving']]);fputcsv($out,['Total Hutang',$data['totalDebt']]);fputcsv($out,[]);
        fputcsv($out,['DETAIL PEMASUKAN']);fputcsv($out,['No','Kategori','Nominal','Tanggal','Catatan']);foreach($data['incomes'] as $i=>$t)fputcsv($out,[$i+1,$t['category'],$t['amount'],date('d/m/Y',strtotime($t['date'])),$t['note']??'']);fputcsv($out,['','TOTAL',$data['totalIncome'],'','']);fputcsv($out,[]);
        fputcsv($out,['DETAIL PENGELUARAN']);fputcsv($out,['No','Kategori','Nominal','Tanggal','Catatan']);foreach($data['expenses'] as $i=>$t)fputcsv($out,[$i+1,$t['category'],$t['amount'],date('d/m/Y',strtotime($t['date'])),$t['note']??'']);fputcsv($out,['','TOTAL',$data['totalExpense'],'','']);
        fclose($out);exit;
    }

    public function exportPdf(){
        $uid=$this->uid();$m=sanitize_month($this->request->getGet('month'));$y=sanitize_year($this->request->getGet('year'));
        $data=$this->getData($uid,$m,$y);$mn=date('F',mktime(0,0,0,(int)$m,1));$bal=$data['totalIncome']-$data['totalExpense'];
        $ir=$er='';
        foreach($data['incomes'] as $i=>$t)$ir.='<tr><td>'.($i+1).'</td><td>'.esc($t['category']).'</td><td>Rp '.number_format($t['amount'],0,',','.').'</td><td>'.date('d/m/Y',strtotime($t['date'])).'</td><td>'.esc($t['note']??'').'</td></tr>';
        foreach($data['expenses'] as $i=>$t)$er.='<tr><td>'.($i+1).'</td><td>'.esc($t['category']).'</td><td>Rp '.number_format($t['amount'],0,',','.').'</td><td>'.date('d/m/Y',strtotime($t['date'])).'</td><td>'.esc($t['note']??'').'</td></tr>';
        $html='<!DOCTYPE html><html><head><meta charset="UTF-8"><style>body{font-family:DejaVu Sans,sans-serif;font-size:11px}.header{text-align:center;margin-bottom:16px}h2{font-size:15px;color:#667eea;margin:0}h3{font-size:11px;color:#667eea;border-bottom:1px solid #667eea;padding-bottom:3px;margin-top:14px}table{width:100%;border-collapse:collapse;margin-bottom:10px}th{background:#667eea;color:white;padding:5px;font-size:10px}td{padding:4px;border-bottom:1px solid #eee;font-size:10px}.tot td{font-weight:bold;background:#e0e7ff}.sum td{padding:6px;border:1px solid #e2e8f0}.l{background:#f8fafc;font-weight:bold}.v{text-align:right}</style></head><body>
<div class="header"><h2>LAPORAN KEUANGAN PRIBADI</h2><p>Periode: '.$mn.' '.$y.' | Dicetak: '.date('d F Y H:i').'</p></div>
<h3>RINGKASAN</h3><table class="sum"><tr><td class="l">Total Pemasukan</td><td class="v" style="color:#10b981">Rp '.number_format($data['totalIncome'],0,',','.').'</td></tr><tr><td class="l">Total Pengeluaran</td><td class="v" style="color:#ef4444">Rp '.number_format($data['totalExpense'],0,',','.').'</td></tr><tr><td class="l" style="background:#667eea;color:white">Saldo Akhir</td><td style="background:#667eea;color:white;text-align:right">Rp '.number_format($bal,0,',','.').'</td></tr><tr><td class="l">Total Tabungan</td><td class="v">Rp '.number_format($data['totalSaving'],0,',','.').'</td></tr><tr><td class="l">Total Hutang</td><td class="v" style="color:#ef4444">Rp '.number_format($data['totalDebt'],0,',','.').'</td></tr></table>
<h3>DETAIL PEMASUKAN</h3><table><thead><tr><th>#</th><th>Kategori</th><th>Nominal</th><th>Tanggal</th><th>Catatan</th></tr></thead><tbody>'.($ir?:'<tr><td colspan="5" style="text-align:center">Tidak ada data</td></tr>').'</tbody><tfoot><tr class="tot"><td colspan="2">TOTAL</td><td>Rp '.number_format($data['totalIncome'],0,',','.').'</td><td colspan="2"></td></tr></tfoot></table>
<h3>DETAIL PENGELUARAN</h3><table><thead><tr><th>#</th><th>Kategori</th><th>Nominal</th><th>Tanggal</th><th>Catatan</th></tr></thead><tbody>'.($er?:'<tr><td colspan="5" style="text-align:center">Tidak ada data</td></tr>').'</tbody><tfoot><tr class="tot"><td colspan="2">TOTAL</td><td>Rp '.number_format($data['totalExpense'],0,',','.').'</td><td colspan="2"></td></tr></tfoot></table>
</body></html>';
        $opt=new Options();$opt->set('isHtml5ParserEnabled',true);$opt->set('defaultFont','DejaVu Sans');
        $pdf=new Dompdf($opt);$pdf->loadHtml($html);$pdf->setPaper('A4','portrait');$pdf->render();
        $pdf->stream('laporan-'.$mn.'-'.$y.'.pdf',['Attachment'=>true]);exit;
    }

    public function exportExcel(){
        $uid=$this->uid();$m=sanitize_month($this->request->getGet('month'));$y=sanitize_year($this->request->getGet('year'));
        $data=$this->getData($uid,$m,$y);$mn=date('F',mktime(0,0,0,(int)$m,1));
        $sp=new Spreadsheet();$sh=$sp->getActiveSheet()->setTitle('Ringkasan');
        $sh->mergeCells('A1:B1');$sh->setCellValue('A1','LAPORAN - '.strtoupper($mn.' '.$y));
        $sh->getStyle('A1')->applyFromArray(['font'=>['bold'=>true,'size'=>13,'color'=>['rgb'=>'FFFFFF']],'fill'=>['fillType'=>Fill::FILL_SOLID,'startColor'=>['rgb'=>'667EEA']]]);
        $r=3;foreach([['Total Pemasukan',$data['totalIncome']],['Total Pengeluaran',$data['totalExpense']],['Saldo',$data['totalIncome']-$data['totalExpense']],['Tabungan',$data['totalSaving']],['Hutang',$data['totalDebt']]] as $row){$sh->setCellValue('A'.$r,$row[0]);$sh->setCellValue('B'.$r,$row[1]);$sh->getStyle('B'.$r)->getNumberFormat()->setFormatCode('"Rp "#,##0');$r++;}
        $sh->getColumnDimension('A')->setWidth(25);$sh->getColumnDimension('B')->setWidth(20);
        foreach([['Pemasukan',$data['incomes'],$data['totalIncome'],'10B981'],['Pengeluaran',$data['expenses'],$data['totalExpense'],'EF4444']] as[$title,$rows,$total,$color]){
            $s=$sp->createSheet()->setTitle($title);foreach(['A'=>'No','B'=>'Kategori','C'=>'Nominal','D'=>'Tanggal','E'=>'Catatan'] as $c=>$h)$s->setCellValue($c.'1',$h);$s->getStyle('A1:E1')->applyFromArray(['font'=>['bold'=>true,'color'=>['rgb'=>'FFFFFF']],'fill'=>['fillType'=>Fill::FILL_SOLID,'startColor'=>['rgb'=>$color]]]);
            $row=2;foreach($rows as $i=>$t){$s->setCellValue('A'.$row,$i+1);$s->setCellValue('B'.$row,$t['category']);$s->setCellValue('C'.$row,$t['amount']);$s->setCellValue('D'.$row,date('d/m/Y',strtotime($t['date'])));$s->setCellValue('E'.$row,$t['note']??'');$s->getStyle('C'.$row)->getNumberFormat()->setFormatCode('"Rp "#,##0');$row++;}
            $s->setCellValue('B'.$row,'TOTAL');$s->setCellValue('C'.$row,$total);$s->getStyle('B'.$row.':C'.$row)->getFont()->setBold(true);$s->getStyle('C'.$row)->getNumberFormat()->setFormatCode('"Rp "#,##0');
            foreach(['A','B','C','D','E'] as $c)$s->getColumnDimension($c)->setAutoSize(true);
        }
        $sp->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');header('Content-Disposition: attachment; filename="laporan-'.$mn.'-'.$y.'.xlsx"');header('Cache-Control: max-age=0');
        (new Xlsx($sp))->save('php://output');exit;
    }
}
