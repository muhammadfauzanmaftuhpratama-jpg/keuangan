<?php
namespace App\Controllers\User;
use App\Controllers\BaseController;
use App\Models\CategoryModel;
class CategoryController extends BaseController {
    protected $cm;
    public function __construct(){$this->cm=new CategoryModel();}
    private function uid():int{return (int)session()->get('user_id');}
    public function index(){$uid=$this->uid();return view('user/category/index',['title'=>'Kategori','incomeCategories'=>$this->cm->getUserCategories($uid,'income'),'expenseCategories'=>$this->cm->getUserCategories($uid,'expense')]);}
    public function create(){return view('user/category/create',['title'=>'Tambah Kategori']);}
    public function store(){
        if(!$this->validate(['name'=>'required|min_length[2]|max_length[100]','type'=>'required|in_list[income,expense]','color'=>'permit_empty|regex_match[/^#[0-9A-Fa-f]{6}$/]'],['type'=>['in_list'=>'Tipe harus income atau expense!'],'color'=>['regex_match'=>'Format warna tidak valid!']]))return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $uid=$this->uid();$type=$this->request->getPost('type');$name=trim($this->request->getPost('name'));
        if($this->cm->where('user_id',$uid)->where('type',$type)->where('name',$name)->first())return redirect()->back()->withInput()->with('error','Kategori dengan nama ini sudah ada!');
        $this->cm->insert(['user_id'=>$uid,'type'=>$type,'name'=>htmlspecialchars($name),'icon'=>$this->request->getPost('icon')?:'bi bi-tag','color'=>$this->request->getPost('color')?:'#667eea','is_default'=>0]);
        return redirect()->to('/user/category')->with('success','Kategori berhasil ditambahkan!');
    }
    public function edit(int $id){$c=$this->cm->where('id',$id)->where('user_id',$this->uid())->first();if(!$c)return redirect()->to('/user/category')->with('error','Kategori tidak ditemukan!');return view('user/category/edit',['title'=>'Edit Kategori','category'=>$c]);}
    public function update(int $id){
        $c=$this->cm->where('id',$id)->where('user_id',$this->uid())->first();if(!$c)return redirect()->to('/user/category')->with('error','Kategori tidak ditemukan!');
        if(!$this->validate(['name'=>'required|min_length[2]|max_length[100]','color'=>'permit_empty|regex_match[/^#[0-9A-Fa-f]{6}$/]']))return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $uid=$this->uid();$name=trim($this->request->getPost('name'));
        if($this->cm->where('user_id',$uid)->where('type',$c['type'])->where('name',$name)->where('id !=',$id)->first())return redirect()->back()->withInput()->with('error','Kategori dengan nama ini sudah ada!');
        $this->cm->update($id,['name'=>htmlspecialchars($name),'icon'=>$this->request->getPost('icon')?:'bi bi-tag','color'=>$this->request->getPost('color')?:'#667eea']);
        return redirect()->to('/user/category')->with('success','Kategori berhasil diupdate!');
    }
    public function delete(int $id){
        $c=$this->cm->where('id',$id)->where('user_id',$this->uid())->first();if(!$c)return redirect()->to('/user/category')->with('error','Kategori tidak ditemukan!');
        if($c['is_default'])return redirect()->to('/user/category')->with('error','Kategori default tidak bisa dihapus!');
        $this->cm->delete($id);return redirect()->to('/user/category')->with('success','Kategori berhasil dihapus!');
    }
}
