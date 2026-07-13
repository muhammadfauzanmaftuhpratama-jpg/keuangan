<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\MenuModel;
class MenuController extends BaseController {
    protected $menuModel;
    public function __construct(){$this->menuModel=new MenuModel();}
    public function index(){return view('admin/menu/index',['title'=>'Manajemen Menu','menus'=>$this->menuModel->orderBy('sort_order','ASC')->findAll()]);}
    public function create(){return view('admin/menu/create',['title'=>'Tambah Menu','parentMenus'=>$this->menuModel->where('parent_id',null)->findAll()]);}
    public function store(){
        if(!$this->validate(['name'=>'required|min_length[2]','slug'=>'required|is_unique[menus.slug]','url'=>'required']))return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $this->menuModel->save(['name'=>$this->request->getPost('name'),'slug'=>url_title($this->request->getPost('slug'),'-',true),'icon'=>$this->request->getPost('icon')?:'bi bi-circle','url'=>$this->request->getPost('url'),'parent_id'=>$this->request->getPost('parent_id')?:null,'sort_order'=>(int)$this->request->getPost('sort_order'),'is_active'=>$this->request->getPost('is_active')?1:0]);
        return redirect()->to('/admin/menu')->with('success','Menu berhasil ditambahkan!');
    }
    public function edit(int $id){$m=$this->menuModel->find($id);if(!$m)return redirect()->to('/admin/menu')->with('error','Menu tidak ditemukan!');return view('admin/menu/edit',['title'=>'Edit Menu','menu'=>$m,'parentMenus'=>$this->menuModel->where('parent_id',null)->where('id !=',$id)->findAll()]);}
    public function update(int $id){
        if(!$this->validate(['name'=>'required|min_length[2]','slug'=>"required|is_unique[menus.slug,id,{$id}]",'url'=>'required']))return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $this->menuModel->update($id,['name'=>$this->request->getPost('name'),'slug'=>url_title($this->request->getPost('slug'),'-',true),'icon'=>$this->request->getPost('icon')?:'bi bi-circle','url'=>$this->request->getPost('url'),'parent_id'=>$this->request->getPost('parent_id')?:null,'sort_order'=>(int)$this->request->getPost('sort_order'),'is_active'=>$this->request->getPost('is_active')?1:0]);
        return redirect()->to('/admin/menu')->with('success','Menu berhasil diupdate!');
    }
    public function delete(int $id){if(!$this->menuModel->find($id))return redirect()->to('/admin/menu')->with('error','Menu tidak ditemukan!');$this->menuModel->where('parent_id',$id)->delete();$this->menuModel->delete($id);return redirect()->to('/admin/menu')->with('success','Menu berhasil dihapus!');}
}
