<?php
namespace App\Controllers\Auth;
use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\LoginAttemptModel;
use App\Models\PasswordResetModel;
use App\Models\CategoryModel;

class AuthController extends BaseController {
    protected $userModel;
    protected $attemptModel;
    protected $resetModel;
    const MAX_ATTEMPTS=5;
    const BLOCK_MINUTES=15;

    public function __construct(){$this->userModel=new UserModel();$this->attemptModel=new LoginAttemptModel();$this->resetModel=new PasswordResetModel();}

    public function login(){return view('auth/login');}

    public function loginProcess(){
        $ip=trim($this->request->getIPAddress());
        $email=trim($this->request->getPost('email')??'');
        $attempts=$this->attemptModel->countRecentAttempts($ip,self::BLOCK_MINUTES);
        if($attempts>=self::MAX_ATTEMPTS)return redirect()->back()->withInput()->with('error','Terlalu banyak percobaan. Coba lagi dalam '.self::BLOCK_MINUTES.' menit.');
        if(!$this->validate(['email'=>'required|valid_email','password'=>'required|min_length[6]']))return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $user=$this->userModel->findByEmail($email);
        if(!$user||!password_verify($this->request->getPost('password'),$user['password'])){
            $this->attemptModel->logAttempt($ip,$email);
            $remaining=max(0,self::MAX_ATTEMPTS-($attempts+1));
            $msg=$remaining>0?"Email atau password salah! Sisa percobaan: {$remaining}x":'Akun dikunci '.self::BLOCK_MINUTES.' menit.';
            return redirect()->back()->withInput()->with('error',$msg);
        }
        $this->attemptModel->clearAttemptsByIp($ip,self::BLOCK_MINUTES);
        $this->userModel->updateLastLogin($user['id']);
        session()->set(['isLoggedIn'=>true,'user_id'=>$user['id'],'name'=>$user['name'],'email'=>$user['email'],'role'=>$user['role'],'avatar'=>$user['avatar']??null]);
        return $user['role']==='admin'?redirect()->to('/admin/dashboard'):redirect()->to('/user/dashboard');
    }

    public function register(){return view('auth/register');}

    public function registerProcess(){
        if(!$this->validate(['name'=>'required|min_length[3]|max_length[50]','email'=>'required|valid_email|is_unique[users.email]','password'=>'required|min_length[8]','confirm_password'=>'required|matches[password]'],['email'=>['is_unique'=>'Email sudah terdaftar!'],'confirm_password'=>['matches'=>'Konfirmasi password tidak cocok!'],'password'=>['min_length'=>'Password minimal 8 karakter!']]))
            return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $db=\Config\Database::connect();$db->transStart();
        try{
            $userId=$this->userModel->insert(['name'=>htmlspecialchars(trim($this->request->getPost('name'))),'email'=>strtolower(trim($this->request->getPost('email'))),'password'=>password_hash($this->request->getPost('password'),PASSWORD_DEFAULT),'role'=>'user']);
            if($userId)(new CategoryModel())->initDefaultCategories($userId);
            $db->transComplete();
            if($db->transStatus()===false)return redirect()->back()->withInput()->with('error','Registrasi gagal. Coba lagi.');
        }catch(\Exception $e){$db->transRollback();log_message('error','Register: '.$e->getMessage());return redirect()->back()->withInput()->with('error','Terjadi kesalahan.');}
        return redirect()->to('/login')->with('success','Registrasi berhasil! Silakan login.');
    }

    public function logout(){session()->destroy();return redirect()->to('/login')->with('success','Berhasil logout.');}

    public function forgotPassword(){return view('auth/forgot_password');}

    public function forgotPasswordProcess(){
        if(!$this->validate(['email'=>'required|valid_email']))return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $email=strtolower(trim($this->request->getPost('email')));
        $user=$this->userModel->findByEmail($email);
        if($user){
            try{
                $token=$this->resetModel->createToken($email);
                $url=base_url('/reset-password/'.$token);
                if(ENVIRONMENT==='development')return redirect()->to('/login')->with('info',"Dev Mode — Reset Link: {$url}");
            }catch(\Exception $e){log_message('error','ForgotPw: '.$e->getMessage());}
        }
        return redirect()->to('/login')->with('success','Jika email terdaftar, link reset telah dikirim.');
    }

    public function resetPassword(string $token){
        $token=preg_replace('/[^a-f0-9]/','', $token);
        if(empty($token))return redirect()->to('/login')->with('error','Token tidak valid.');
        $reset=$this->resetModel->validateToken($token);
        if(!$reset)return redirect()->to('/login')->with('error','Link reset tidak valid atau sudah kadaluarsa.');
        return view('auth/reset_password',['token'=>$token]);
    }

    public function resetPasswordProcess(){
        $token=preg_replace('/[^a-f0-9]/','', $this->request->getPost('token')??'');
        $reset=$this->resetModel->validateToken($token);
        if(!$reset)return redirect()->to('/login')->with('error','Link reset tidak valid atau sudah kadaluarsa.');
        if(!$this->validate(['password'=>'required|min_length[8]','confirm_password'=>'required|matches[password]'],['confirm_password'=>['matches'=>'Konfirmasi password tidak cocok!']]))
            return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        try{
            $user=$this->userModel->findByEmail($reset['email']);
            if($user){$this->userModel->changePassword($user['id'],$this->request->getPost('password'));$this->resetModel->deleteToken($token);}
        }catch(\Exception $e){log_message('error','ResetPw: '.$e->getMessage());return redirect()->back()->with('error','Terjadi kesalahan.');}
        return redirect()->to('/login')->with('success','Password berhasil direset!');
    }
}
