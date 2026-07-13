<?php
namespace App\Controllers\User;
use App\Controllers\BaseController;
use App\Models\SavingModel;
use App\Models\SavingHistoryModel;
class SavingController extends BaseController {
    protected $sm;
    protected $hm;
    public function __construct(){$this->sm=new SavingModel();$this->hm=new SavingHistoryModel();}
    private function uid():int{return (int)session()->get('user_id');}
    public function index(){
        $uid=$this->uid();$search=substr(trim(strip_tags($this->request->getGet('search')??'')),0,100);$pp=9;
        $cq=$this->sm->where('user_id',$uid);if($search!=='')$cq->groupStart()->like('name',$search,'both',null,true)->orLike('note',$search,'both',null,true)->groupEnd();$total=$cq->countAllResults();
        $dq=$this->sm->where('user_id',$uid);if($search!=='')$dq->groupStart()->like('name',$search,'both',null,true)->orLike('note',$search,'both',null,true)->groupEnd();$rows=$dq->orderBy('created_at','DESC')->paginate($pp,'default');
        return view('user/saving/index',['title'=>'Tabungan','savings'=>$rows,'totalSaving'=>$this->sm->getTotalSavings($uid),'search'=>$search,'pager'=>$this->sm->pager,'perPage'=>$pp,'totalData'=>$total]);
    }
    public function create(){return view('user/saving/create',['title'=>'Tambah Tabungan']);}
    public function store(){
        if(!$this->validate(['name'=>'required|min_length[3]|max_length[100]','target_amount'=>'required|numeric|greater_than[0]','deadline'=>'permit_empty|valid_date']))
            return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $this->sm->save(['user_id'=>$this->uid(),'name'=>htmlspecialchars(trim($this->request->getPost('name'))),'target_amount'=>(float)$this->request->getPost('target_amount'),'current_amount'=>0,'deadline'=>$this->request->getPost('deadline')?:null,'note'=>htmlspecialchars(trim($this->request->getPost('note')??''))]);
        return redirect()->to('/user/saving')->with('success','Target tabungan berhasil dibuat!');
    }
    public function detail(int $id){
        $s=$this->sm->where('id',$id)->where('user_id',$this->uid())->first();
        if(!$s)return redirect()->to('/user/saving')->with('error','Tabungan tidak ditemukan!');
        $pp=10;$h=$this->hm->where('saving_id',$id)->orderBy('created_at','DESC')->paginate($pp,'history');
        return view('user/saving/detail',['title'=>'Detail Tabungan','saving'=>$s,'histories'=>$h,'pager'=>$this->hm->pager,'perPage'=>$pp]);
    }
    public function edit(int $id){$s=$this->sm->where('id',$id)->where('user_id',$this->uid())->first();if(!$s)return redirect()->to('/user/saving')->with('error','Tabungan tidak ditemukan!');return view('user/saving/edit',['title'=>'Edit Tabungan','saving'=>$s]);}
    public function update(int $id){
        if(!$this->sm->where('id',$id)->where('user_id',$this->uid())->first())return redirect()->to('/user/saving')->with('error','Tabungan tidak ditemukan!');
        if(!$this->validate(['name'=>'required|min_length[3]|max_length[100]','target_amount'=>'required|numeric|greater_than[0]']))return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $this->sm->update($id,['name'=>htmlspecialchars(trim($this->request->getPost('name'))),'target_amount'=>(float)$this->request->getPost('target_amount'),'deadline'=>$this->request->getPost('deadline')?:null,'note'=>htmlspecialchars(trim($this->request->getPost('note')??''))]);
        return redirect()->to('/user/saving')->with('success','Tabungan berhasil diupdate!');
    }
    public function delete(int $id){if(!$this->sm->where('id',$id)->where('user_id',$this->uid())->first())return redirect()->to('/user/saving')->with('error','Tabungan tidak ditemukan!');$this->sm->delete($id);return redirect()->to('/user/saving')->with('success','Tabungan berhasil dihapus!');}
    public function deposit(int $id){
        $s=$this->sm->where('id',$id)->where('user_id',$this->uid())->first();
        if(!$s)return redirect()->to('/user/saving')->with('error','Tabungan tidak ditemukan!');
        if(!$this->validate(['amount'=>'required|numeric|greater_than[0]']))return redirect()->back()->with('errors',$this->validator->getErrors());
        $amt=(float)$this->request->getPost('amount');$db=\Config\Database::connect();$db->transStart();
        try{$this->sm->update($id,['current_amount'=>$s['current_amount']+$amt]);$this->hm->save(['saving_id'=>$id,'user_id'=>$this->uid(),'type'=>'deposit','amount'=>$amt,'note'=>htmlspecialchars(trim($this->request->getPost('note')??'')),'created_at'=>date('Y-m-d H:i:s')]);$db->transComplete();if($db->transStatus()===false)return redirect()->back()->with('error','Gagal menyimpan setoran!');}
        catch(\Exception $e){$db->transRollback();return redirect()->back()->with('error','Terjadi kesalahan!');}
        return redirect()->to('/user/saving/detail/'.$id)->with('success','Setoran berhasil disimpan!');
    }
    public function withdraw(int $id){
        $s=$this->sm->where('id',$id)->where('user_id',$this->uid())->first();
        if(!$s)return redirect()->to('/user/saving')->with('error','Tabungan tidak ditemukan!');
        if(!$this->validate(['amount'=>'required|numeric|greater_than[0]']))return redirect()->back()->with('errors',$this->validator->getErrors());
        $amt=(float)$this->request->getPost('amount');
        if($amt>$s['current_amount'])return redirect()->back()->with('error','Saldo tidak mencukupi! Saldo: '.rupiah($s['current_amount']));
        $db=\Config\Database::connect();$db->transStart();
        try{$this->sm->update($id,['current_amount'=>$s['current_amount']-$amt]);$this->hm->save(['saving_id'=>$id,'user_id'=>$this->uid(),'type'=>'withdraw','amount'=>$amt,'note'=>htmlspecialchars(trim($this->request->getPost('note')??'')),'created_at'=>date('Y-m-d H:i:s')]);$db->transComplete();if($db->transStatus()===false)return redirect()->back()->with('error','Gagal menyimpan penarikan!');}
        catch(\Exception $e){$db->transRollback();return redirect()->back()->with('error','Terjadi kesalahan!');}
        return redirect()->to('/user/saving/detail/'.$id)->with('success','Penarikan berhasil!');
    }
}
