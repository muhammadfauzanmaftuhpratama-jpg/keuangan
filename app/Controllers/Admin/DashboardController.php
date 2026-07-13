<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\TransactionModel;
use App\Models\DebtModel;
use App\Models\SavingModel;
use App\Models\MenuModel;

class DashboardController extends BaseController {
    public function index(){
        $userId=(int)session()->get('user_id');
        $month=sanitize_month($this->request->getGet('month'));
        $year=sanitize_year($this->request->getGet('year'));
        $t=new TransactionModel();$s=new SavingModel();$d=new DebtModel();
        $inc=$t->getTotalByType($userId,'income',$month,$year);
        $exp=$t->getTotalByType($userId,'expense',$month,$year);
        $chart=$this->getChart($t,$userId,$month,$year);
        return view('admin/dashboard/index',['title'=>'Dashboard Admin','month'=>$month,'year'=>$year,'monthName'=>date('F',mktime(0,0,0,(int)$month,1)),'totalUsers'=>(new UserModel())->countAll(),'totalMenus'=>(new MenuModel())->where('is_active',1)->countAllResults(),'myIncome'=>$inc,'myExpense'=>$exp,'mySaving'=>$s->getTotalSavings($userId),'myDebt'=>$d->getTotalDebt($userId),'myBalance'=>$inc-$exp,'recentTransactions'=>$t->getRecentTransactions($userId,5),'chartLabels'=>json_encode($chart['labels']),'chartIncome'=>json_encode($chart['income']),'chartExpense'=>json_encode($chart['expense'])]);
    }
    private function getChart($t,int $uid,string $m,string $y):array{
        $labels=$inc=$exp=[];
        $days=cal_days_in_month(CAL_GREGORIAN,(int)$m,(int)$y);
        $weeks=['Minggu 1'=>[1,7],'Minggu 2'=>[8,14],'Minggu 3'=>[15,21],'Minggu 4'=>[22,28]];
        if($days>28)$weeks['Minggu 5']=[29,$days];
        foreach($weeks as $lbl=>$r){
            $s=$y.'-'.$m.'-'.str_pad($r[0],2,'0',STR_PAD_LEFT);
            $e=$y.'-'.$m.'-'.str_pad(min($r[1],$days),2,'0',STR_PAD_LEFT);
            $labels[]=$lbl;$inc[]=$t->getTotalByDateRange($uid,'income',$s,$e);$exp[]=$t->getTotalByDateRange($uid,'expense',$s,$e);
        }
        return compact('labels','inc','exp')+['income'=>$inc,'expense'=>$exp];
    }
}
