<?php
namespace App\Controllers\User;
use App\Controllers\BaseController;
use App\Models\DebtModel;
use App\Models\DebtPaymentModel;
class DebtController extends BaseController {
    protected $dm;
    protected $pm;
    public function __construct(){$this->dm=new DebtModel();$this->pm=new DebtPaymentModel();}
    private function uid():int{return (int)session()->get('user_id');}
    public function index(){
        $uid=$this->uid();$search=substr(trim(strip_tags($this->request->getGet('search')??'')),0,100);$status=$this->request->getGet('status')??'';$pp=10;
        $cq=$this->dm->where('user_id',$uid);if($search!=='')$cq->groupStart()->like('creditor_name',$search,'both',null,true)->orLike('note',$search,'both',null,true)->groupEnd();if(in_array($status,['paid','unpaid']))$cq->where('status',$status);$total=$cq->countAllResults();
        $dq=$this->dm->where('user_id',$uid);if($search!=='')$dq->groupStart()->like('creditor_name',$search,'both',null,true)->orLike('note',$search,'both',null,true)->groupEnd();if(in_array($status,['paid','unpaid']))$dq->where('status',$status);$rows=$dq->orderBy('due_date','ASC')->paginate($pp,'default');
        return view('user/debt/index',['title'=>'Hutang','debts'=>$rows,'totalDebt'=>$this->dm->getTotalDebt($uid),'search'=>$search,'status'=>$status,'pager'=>$this->dm->pager,'perPage'=>$pp,'totalData'=>$total]);
    }
    public function create(){return view('user/debt/create',['title'=>'Tambah Hutang']);}
    public function store(){
        if(!$this->validate(['creditor_name'=>'required|min_length[2]|max_length[100]','total_amount'=>'required|numeric|greater_than[0]','due_date'=>'required|valid_date']))return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $amt=(float)$this->request->getPost('total_amount');
        $this->dm->save(['user_id'=>$this->uid(),'creditor_name'=>htmlspecialchars(trim($this->request->getPost('creditor_name'))),'total_amount'=>$amt,'remaining_amount'=>$amt,'due_date'=>$this->request->getPost('due_date'),'status'=>'unpaid','note'=>htmlspecialchars(trim($this->request->getPost('note')??''))]);
        return redirect()->to('/user/debt')->with('success','Hutang berhasil ditambahkan!');
    }
    public function detail(int $id){
        $d=$this->dm->where('id',$id)->where('user_id',$this->uid())->first();if(!$d)return redirect()->to('/user/debt')->with('error','Data tidak ditemukan!');
        $pp=10;$p=$this->pm->where('debt_id',$id)->orderBy('payment_date','DESC')->paginate($pp,'payments');
        return view('user/debt/detail',['title'=>'Detail Hutang','debt'=>$d,'payments'=>$p,'pager'=>$this->pm->pager,'perPage'=>$pp]);
    }
    public function edit(int $id){$d=$this->dm->where('id',$id)->where('user_id',$this->uid())->first();if(!$d)return redirect()->to('/user/debt')->with('error','Data tidak ditemukan!');return view('user/debt/edit',['title'=>'Edit Hutang','debt'=>$d]);}
    public function update(int $id){
        if(!$this->dm->where('id',$id)->where('user_id',$this->uid())->first())return redirect()->to('/user/debt')->with('error','Data tidak ditemukan!');
        if(!$this->validate(['creditor_name'=>'required|min_length[2]|max_length[100]','due_date'=>'required|valid_date']))return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $this->dm->update($id,['creditor_name'=>htmlspecialchars(trim($this->request->getPost('creditor_name'))),'due_date'=>$this->request->getPost('due_date'),'note'=>htmlspecialchars(trim($this->request->getPost('note')??''))]);
        return redirect()->to('/user/debt')->with('success','Hutang berhasil diupdate!');
    }
    public function delete(int $id){if(!$this->dm->where('id',$id)->where('user_id',$this->uid())->first())return redirect()->to('/user/debt')->with('error','Data tidak ditemukan!');$this->dm->delete($id);return redirect()->to('/user/debt')->with('success','Hutang berhasil dihapus!');}
    public function pay(int $id){
        $d=$this->dm->where('id',$id)->where('user_id',$this->uid())->first();if(!$d)return redirect()->to('/user/debt')->with('error','Data tidak ditemukan!');
        if($d['status']==='paid')return redirect()->to('/user/debt/detail/'.$id)->with('error','Hutang ini sudah lunas!');
        if(!$this->validate(['amount'=>'required|numeric|greater_than[0]','payment_date'=>'required|valid_date']))return redirect()->back()->with('errors',$this->validator->getErrors());
        $amt=(float)$this->request->getPost('amount');
        if($amt>$d['remaining_amount'])return redirect()->back()->with('error','Nominal melebihi sisa hutang! Sisa: '.rupiah($d['remaining_amount']));
        $rem=round($d['remaining_amount']-$amt,2);$status=$rem<=0?'paid':'unpaid';
        $db=\Config\Database::connect();$db->transStart();
        try{$this->pm->save(['debt_id'=>$id,'user_id'=>$this->uid(),'amount'=>$amt,'payment_date'=>$this->request->getPost('payment_date'),'note'=>htmlspecialchars(trim($this->request->getPost('note')??'')),'created_at'=>date('Y-m-d H:i:s')]);$this->dm->update($id,['remaining_amount'=>max(0,$rem),'status'=>$status]);$db->transComplete();if($db->transStatus()===false)return redirect()->back()->with('error','Gagal mencatat pembayaran!');}
        catch(\Exception $e){$db->transRollback();return redirect()->back()->with('error','Terjadi kesalahan!');}
        $msg=$status==='paid'?'Selamat! Hutang lunas! 🎉':'Pembayaran berhasil! Sisa: '.rupiah(max(0,$rem));
        return redirect()->to('/user/debt/detail/'.$id)->with('success',$msg);
    }
}
