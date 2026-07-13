<?php
namespace App\Controllers\User;
use App\Controllers\BaseController;
use App\Models\NotificationModel;
class NotificationController extends BaseController {
    protected $notifModel;
    public function __construct(){$this->notifModel=new NotificationModel();}
    private function getUserId():int{return (int)session()->get('user_id');}
    public function index(){
        $uid=$this->getUserId();$pp=10;
        $notifs=$this->notifModel->where('user_id',$uid)->orderBy('created_at','DESC')->paginate($pp,'default');
        return view('user/notification/index',['title'=>'Notifikasi','notifications'=>$notifs,'pager'=>$this->notifModel->pager,'perPage'=>$pp,'unreadCount'=>$this->notifModel->countUnread($uid)]);
    }
    public function markRead(int $id){$n=$this->notifModel->where('id',$id)->where('user_id',$this->getUserId())->first();if($n)$this->notifModel->update($id,['is_read'=>1]);return redirect()->back()->with('success','Notifikasi ditandai dibaca.');}
    public function markAllRead(){$this->notifModel->markAllRead($this->getUserId());return redirect()->back()->with('success','Semua notifikasi ditandai dibaca.');}
    public function delete(int $id){$n=$this->notifModel->where('id',$id)->where('user_id',$this->getUserId())->first();if($n)$this->notifModel->delete($id);return redirect()->back()->with('success','Notifikasi dihapus.');}
}
