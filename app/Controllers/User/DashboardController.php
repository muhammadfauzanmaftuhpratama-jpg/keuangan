<?php
namespace App\Controllers\User;
use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\SavingModel;
use App\Models\DebtModel;
use App\Models\NotificationModel;
class DashboardController extends BaseController {
    public function index(){
        $userId=(int)session()->get('user_id');
        $month=sanitize_month($this->request->getGet('month'));
        $year=sanitize_year($this->request->getGet('year'));
        $t=new TransactionModel();$s=new SavingModel();$d=new DebtModel();$n=new NotificationModel();
        $this->checkDebts($userId,$d,$n);
        $inc=$t->getTotalByType($userId,'income',$month,$year);
        $exp=$t->getTotalByType($userId,'expense',$month,$year);
        $chart=$this->getChart($t,$userId,$month,$year);
        return view('user/dashboard/index',['title'=>'Dashboard','month'=>$month,'year'=>$year,'monthName'=>date('F',mktime(0,0,0,(int)$month,1)),'totalIncome'=>$inc,'totalExpense'=>$exp,'totalSaving'=>$s->getTotalSavings($userId),'totalDebt'=>$d->getTotalDebt($userId),'balance'=>$inc-$exp,'recentTransactions'=>$t->getRecentTransactions($userId,7),'unreadCount'=>$n->countUnread($userId),'chartLabels'=>json_encode($chart['labels']),'chartIncome'=>json_encode($chart['income']),'chartExpense'=>json_encode($chart['expense'])]);
    }
    private function getChart($t,int $uid,string $m,string $y):array{
        $labels=$inc=$exp=[];$days=cal_days_in_month(CAL_GREGORIAN,(int)$m,(int)$y);
        $weeks=['Minggu 1'=>[1,7],'Minggu 2'=>[8,14],'Minggu 3'=>[15,21],'Minggu 4'=>[22,28]];
        if($days>28)$weeks['Minggu 5']=[29,$days];
        foreach($weeks as $lbl=>$r){$s=$y.'-'.$m.'-'.str_pad($r[0],2,'0',STR_PAD_LEFT);$e=$y.'-'.$m.'-'.str_pad(min($r[1],$days),2,'0',STR_PAD_LEFT);$labels[]=$lbl;$inc[]=$t->getTotalByDateRange($uid,'income',$s,$e);$exp[]=$t->getTotalByDateRange($uid,'expense',$s,$e);}
        return ['labels'=>$labels,'income'=>$inc,'expense'=>$exp];
    }
    private function checkDebts(int $uid,$d,$n):void{
        $key='debt_check_'.$uid.'_'.date('Y-m-d');
        if(session()->get($key))return;
        try{foreach($d->getDueSoonDebts($uid) as $debt){$ex=$n->where('user_id',$uid)->where('debt_id',$debt['id'])->where('DATE(created_at)',date('Y-m-d'))->first();if(!$ex){$dl=max(0,(int)ceil((strtotime($debt['due_date'])-time())/86400));$n->save(['user_id'=>$uid,'debt_id'=>$debt['id'],'message'=>"Hutang ke {$debt['creditor_name']} sebesar ".rupiah($debt['remaining_amount']).($dl===0?' jatuh tempo HARI INI!':" jatuh tempo dalam {$dl} hari ({$debt['due_date']})"),'is_read'=>0,'created_at'=>date('Y-m-d H:i:s')]);}}
        }catch(\Exception $e){log_message('error','checkDebts: '.$e->getMessage());}
        session()->set($key,true);
    }
}
