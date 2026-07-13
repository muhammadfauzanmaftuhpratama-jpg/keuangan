<?php
namespace App\Controllers\User;
use App\Controllers\BaseController;
use App\Models\UserModel;
class ProfileController extends BaseController {
    protected $userModel;
    const AVATAR_DIR=WRITEPATH.'uploads/avatars/';
    const AVATAR_MAX_MB=2;
    const AVATAR_TYPES=['image/jpeg','image/png','image/webp'];
    const MAGIC_BYTES=['image/jpeg'=>"\xFF\xD8\xFF",'image/png'=>"\x89PNG",'image/webp'=>'RIFF'];
    public function __construct(){$this->userModel=new UserModel();if(!is_dir(self::AVATAR_DIR))mkdir(self::AVATAR_DIR,0755,true);}
    private function getUserId():int{return (int)session()->get('user_id');}
    public function index(){
        $u=$this->userModel->find($this->getUserId());
        if(!$u)return redirect()->to('/login')->with('error','Session tidak valid.');
        return view('user/profile/index',['title'=>'Profil Saya','user'=>$u]);
    }
    public function update(){
        $uid=$this->getUserId();
        if(!$this->validate(['name'=>'required|min_length[3]|max_length[50]','phone'=>'permit_empty|min_length[10]|max_length[20]','email'=>"required|valid_email|is_unique[users.email,id,{$uid}]"],['email'=>['is_unique'=>'Email sudah digunakan akun lain!']]))
            return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $data=['name'=>htmlspecialchars(trim($this->request->getPost('name'))),'email'=>strtolower(trim($this->request->getPost('email'))),'phone'=>trim($this->request->getPost('phone')??'')];
        $av=$this->request->getFile('avatar');
        if($av&&$av->isValid()&&!$av->hasMoved()){
            $r=$this->processAvatar($av,$uid);
            if($r['error'])return redirect()->back()->with('error',$r['error']);
            $data['avatar']=$r['filename'];
        }
        try{
            $this->userModel->updateProfile($uid,$data);
            session()->set('name',$data['name']);session()->set('email',$data['email']);
            if(isset($data['avatar']))session()->set('avatar',$data['avatar']);
        }catch(\Exception $e){
            if(isset($data['avatar'])){$f=self::AVATAR_DIR.$data['avatar'];if(file_exists($f))unlink($f);}
            return redirect()->back()->with('error','Gagal menyimpan profil.');
        }
        return redirect()->to('/user/profile')->with('success','Profil berhasil diupdate!');
    }
    public function changePassword(){
        $uid=$this->getUserId();
        if(!$this->validate(['current_password'=>'required','new_password'=>'required|min_length[8]','confirm_password'=>'required|matches[new_password]'],['confirm_password'=>['matches'=>'Konfirmasi password tidak cocok!']]))
            return redirect()->back()->with('errors',$this->validator->getErrors());
        if(!$this->userModel->verifyPassword($uid,$this->request->getPost('current_password')))
            return redirect()->back()->with('error','Password lama tidak sesuai!');
        $u=$this->userModel->find($uid);
        if(password_verify($this->request->getPost('new_password'),$u['password']))
            return redirect()->back()->with('error','Password baru tidak boleh sama dengan lama!');
        $this->userModel->changePassword($uid,$this->request->getPost('new_password'));
        return redirect()->to('/user/profile')->with('success','Password berhasil diubah!');
    }
    private function processAvatar($av,int $uid):array{
        if($av->getSizeByUnit('mb')>self::AVATAR_MAX_MB)return['error'=>'Ukuran foto maksimal '.self::AVATAR_MAX_MB.'MB!','filename'=>null];
        if(!in_array($av->getMimeType(),self::AVATAR_TYPES))return['error'=>'Format foto harus JPG, PNG, atau WEBP!','filename'=>null];
        $h=fopen($av->getTempName(),'rb');$mg=fread($h,4);fclose($h);
        $ok=false;foreach(self::MAGIC_BYTES as $m){if(str_starts_with($mg,$m)){$ok=true;break;}}
        if(!$ok)return['error'=>'File bukan gambar yang valid!','filename'=>null];
        try{
            $fn='avatar_'.$uid.'_'.time().'.'.$av->getExtension();
            $av->move(self::AVATAR_DIR,$fn);
            $u=$this->userModel->find($uid);
            if($u['avatar']&&$u['avatar']!==$fn){$old=self::AVATAR_DIR.$u['avatar'];if(file_exists($old))unlink($old);}
            return['error'=>null,'filename'=>$fn];
        }catch(\Exception $e){log_message('error','Avatar: '.$e->getMessage());return['error'=>'Gagal upload foto.','filename'=>null];}
    }
}
