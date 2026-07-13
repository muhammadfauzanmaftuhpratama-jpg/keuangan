<?php
namespace App\Controllers\User;
use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\CategoryModel;
class TransactionController extends BaseController {
    protected $t;
    protected $c;
    public function __construct(){$this->t=new TransactionModel();$this->c=new CategoryModel();}
    private function uid():int{return (int)session()->get('user_id');}
    private function listView(string $type){
        $uid=$this->uid();$month=sanitize_month($this->request->getGet('month'));$year=sanitize_year($this->request->getGet('year'));
        $search=substr(trim(strip_tags($this->request->getGet('search')??'')),0,100);$pp=10;
        $cq=$this->t->where('user_id',$uid)->where('type',$type)->where('MONTH(date)',$month)->where('YEAR(date)',$year);
        if($search!=='')$cq->groupStart()->like('category',$search,'both',null,true)->orLike('note',$search,'both',null,true)->groupEnd();
        $total=$cq->countAllResults();
        $dq=$this->t->where('user_id',$uid)->where('type',$type)->where('MONTH(date)',$month)->where('YEAR(date)',$year);
        if($search!=='')$dq->groupStart()->like('category',$search,'both',null,true)->orLike('note',$search,'both',null,true)->groupEnd();
        $rows=$dq->orderBy('date','DESC')->orderBy('id','DESC')->paginate($pp,'default');
        return view('user/transaction/'.($type==='income'?'income':'expense'),['title'=>$type==='income'?'Pemasukan':'Pengeluaran','transactions'=>$rows,'total'=>$this->t->getTotalByType($uid,$type,$month,$year),'month'=>$month,'year'=>$year,'search'=>$search,'pager'=>$this->t->pager,'perPage'=>$pp,'totalData'=>$total,'categories'=>$this->c->getUserCategories($uid,$type)]);
    }
    public function income(){return $this->listView('income');}
    public function expense(){return $this->listView('expense');}
    public function createIncome(){return view('user/transaction/create',['title'=>'Tambah Pemasukan','type'=>'income','categories'=>$this->c->getUserCategories($this->uid(),'income')]);}
    public function createExpense(){return view('user/transaction/create',['title'=>'Tambah Pengeluaran','type'=>'expense','categories'=>$this->c->getUserCategories($this->uid(),'expense')]);}
    public function storeIncome(){return $this->storeData('income','/user/income');}
    public function storeExpense(){return $this->storeData('expense','/user/expense');}
    public function editIncome(int $id){return $this->editView($id,'income','/user/income');}
    public function editExpense(int $id){return $this->editView($id,'expense','/user/expense');}
    public function updateIncome(int $id){return $this->updateData($id,'income','/user/income');}
    public function updateExpense(int $id){return $this->updateData($id,'expense','/user/expense');}
    public function deleteIncome(int $id){return $this->deleteData($id,'/user/income');}
    public function deleteExpense(int $id){return $this->deleteData($id,'/user/expense');}
    private function storeData(string $type,string $redirect){
        if(!$this->validate(['category'=>'required|max_length[100]','amount'=>'required|numeric|greater_than[0]','date'=>'required|valid_date','note'=>'permit_empty|max_length[255]']))
            return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $this->t->save(['user_id'=>$this->uid(),'type'=>$type,'category'=>htmlspecialchars(trim($this->request->getPost('category'))),'amount'=>(float)$this->request->getPost('amount'),'date'=>$this->request->getPost('date'),'note'=>htmlspecialchars(trim($this->request->getPost('note')??''))]);
        return redirect()->to($redirect)->with('success',($type==='income'?'Pemasukan':'Pengeluaran').' berhasil ditambahkan!');
    }
    private function editView(int $id,string $type,string $redirect){
        $trx=$this->t->where('id',$id)->where('user_id',$this->uid())->first();
        if(!$trx)return redirect()->to($redirect)->with('error','Data tidak ditemukan!');
        return view('user/transaction/edit',['title'=>'Edit '.($type==='income'?'Pemasukan':'Pengeluaran'),'type'=>$type,'transaction'=>$trx,'categories'=>$this->c->getUserCategories($this->uid(),$type)]);
    }
    private function updateData(int $id,string $type,string $redirect){
        $trx=$this->t->where('id',$id)->where('user_id',$this->uid())->first();
        if(!$trx)return redirect()->to($redirect)->with('error','Data tidak ditemukan!');
        if(!$this->validate(['category'=>'required|max_length[100]','amount'=>'required|numeric|greater_than[0]','date'=>'required|valid_date']))
            return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $this->t->update($id,['category'=>htmlspecialchars(trim($this->request->getPost('category'))),'amount'=>(float)$this->request->getPost('amount'),'date'=>$this->request->getPost('date'),'note'=>htmlspecialchars(trim($this->request->getPost('note')??''))]);
        return redirect()->to($redirect)->with('success','Data berhasil diupdate!');
    }
    private function deleteData(int $id,string $redirect){
        $trx=$this->t->where('id',$id)->where('user_id',$this->uid())->first();
        if(!$trx)return redirect()->to($redirect)->with('error','Data tidak ditemukan!');
        $this->t->delete($id);
        return redirect()->to($redirect)->with('success','Data berhasil dihapus!');
    }
}
