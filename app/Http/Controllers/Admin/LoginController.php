<?php
/**
 * Created by PhpStorm.
 * User: 11495
 * Date: 2018/9/8
 * Time: 15:42
 */

namespace App\Http\Controllers\Admin;

use App\common\Geetestlib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Psy\Exception\ParseErrorException;
use Illuminate\Support\Facades\Session;
use App\Model\User;
use Validator;
class LoginController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){

        return view("/login");

    }

    /**用户登录
     * author 李文俊
     * @param Request $request
     * @return string
     */
    public function doLogin(Request $request){

         $user=trim($request->username);
         $password=trim($request->input('password'));
       if (config('app.verify_type')==true) {

             $GtSdk = new Geetestlib(config('app.gee_id'), config('app.gee_key'));
             $user_id = session('user_id');
             if (session('gtserver') == 1) {
                 $result = $GtSdk->success_validate($request->input('geetest_challenge'), $request->input('geetest_validate'), $request->input('geetest_seccode'), $user_id);
                 //极验服务器状态正常的二次验证接口
                 if (!$result) {

                     return json_encode(['code' => -6, 'data' => '', 'msg' => '请先拖动验证码到相应位置']);

                 }
             } else {

                 if (!$GtSdk->fail_validate($request->input('geetest_challenge'), $request->input('geetest_validate'),$request->input('geetest_seccode'))) {
                     //极验服务器状态宕机的二次验证接口

                     return json_encode(['code' => -6, 'data' => '', 'msg' => '请先拖动验证码到相应位置']);
                 }
             }

         }

         try{

             $rules=[ 'username' => 'required',
                       'password' => 'required'];
             $message=[
                 'username.required' => '用户名不能为空！',
                 'password.required'  => '密码不能为空！',
                     ];

             $validate= Validator::make(['username'=>$user,'password'=>$password],$rules,$message);
             if($validate->fails()){
                 writelog(session('uid'),session('username'),'用户【'.session('username').'】验证用户字段失败！',0);
               return json_encode(['code' => -5, 'data' => '', 'msg' => $validate->errors()->all()]);

             }else{

                 $hasUser=DB::table('admin as a')
                      ->leftJoin('auth_group as r','a.groupid','=','r.id')
                     ->orWhere('username',$user)
                     ->orWhere('email',$user)
                     ->orWhere('mobile',$user)
                     ->select('a.*','r.status as groupstatus','r.rules','r.isdel as auisdel')
                     ->where('a.isdel',1)
                      ->first();

                 $eStr=encrypts($hasUser->jobnum.$password,'E');
                 if ($eStr!= $hasUser->password) {
                     writelog(session('uid'),session('username'),'用户【'.session('username').'】输入密码不正确',0);
                     return json_encode(['code' => -2, 'data' => $eStr, 'msg' => '您所输入密码不正确']);
                 }

                 if(1!=$hasUser->groupstatus){
                     writelog(session('uid'),session('username'),'用户【'.session('username').'】所对应角色被封',0);
                     return json_encode(['code' => -6, 'data' => '', 'msg' => '您的账号被封请联系管理员']);

                 }
                 if(1!=$hasUser->status){
                     writelog(session('uid'),session('username'),'用户【'.session('username').'】用户账号被封',0);
                     return json_encode(['code' => -6, 'data' => '', 'msg' => '您的账号被封请联系管理员']);

                 }
                 if(1!=$hasUser->auisdel){
                     writelog(session('uid'),session('username'),'用户【'.session('username').'】对应角色不惨在',0);
                     return json_encode(['code' => -6, 'data' => '', 'msg' => '您的账号被封请联系管理员']);

                 }

             }

         }catch (\PDOException $e){

             return json_encode(['code' => -6, 'data' => '', 'msg' =>$e->getMessage()]);


         }


         $result=DB::table('auth_group')->where(['id'=>$hasUser->groupid,'isdel'=>1])->first();

         if(is_null($result->rules)){
             $where = '';

          }else{

             $where = explode(',',$result->rules);

        }
        if((int)$result->id==1){

            $res = DB::table('auth_rule')->select('name')->get();

        }else{

            $res = DB::table('auth_rule')->select('name')->whereIn('id',$where)->get();
        }

        foreach($res as $key=>$vo){

            if('#' != $vo->name){
                $result->name[] = $vo->name;
            }
        }


        session(['username'=>$hasUser->username]);
        session(['uid'=> $hasUser->id]);
        session(['realname'=>$hasUser->real_name]);
        session(['jobnum'=> $hasUser->jobnum]);
        session(['groupid'=>$hasUser->groupid]);
        session(['rolename'=> $result->title]);  //角色名
        session(['rule'=>$result->rules]);  //角色节点
        session(['name'=> $result->name]);  //角色权限
        //更新管理员状态
        $param = [
            'loginnum' => $hasUser->loginnum + 1,
            'last_login_ip' => $request->ip(),
            'last_login_time' => time()
        ];

        DB::beginTransaction();
        try{
           $update=User::where('id',$hasUser->id)->update($param);
           Db::commit();

        }catch (\PDOException  $e){

            DB::rollback();
            return json_encode(['code'=>-6,'data'=>'','msg'=>$e->getMessage()]);

        }
        writelog(session('uid'),session('username'),'用户【'.session('username').'】登录成功',1);
         return json_encode(['code' => 1, 'data' =>url('/'), 'msg' =>'登录成功！']);

    }


    /**退出操作
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginOut(Request $request)
 {
     writelog(session('uid'),session('username'),'用户【'.session('username').'】退出登录！',1);
      $request->session()->flush();
     return redirect()->action('LoginController@index');
   }

    //极验验证
    public function getVerify(){
        $GtSdk = new Geetestlib(config('app.gee_id'), config('app.gee_key'));
        $user_id = "web";
        $status = $GtSdk->pre_process($user_id);
        session(['gtserver'=>$status]);
        session(['user_id'=>$user_id]);
        echo $GtSdk->get_response_str();
    }

    /**
     * author 李文俊
     * @param Request $request
     * @return string
     */
    public function resetpwd(Request $request){

         if($request->isMethod('post')){
             if($request->ajax()){
                 $parqam['username']=$request->resetpwdusername;
                 $parqam['email']=$request->resetpwdemail;
                 $parqam['_token']=$request->_token;
                 try{

                     $rules=[ 'email' => 'required|email',"username"=>'required'];
                     $message=[
                         'username.required' => '用户名不能为空！',
                         'email.required' => '邮箱不能为空！',
                         'email.email'  => '邮箱不合法！！',
                     ];

                     $validate= Validator::make(['username'=>$parqam['username'],'email'=>$parqam['email']],$rules,$message);
                     if($validate->fails()){

                         return json_encode(['code'=>0,'data'=>'','msg'=>$validate->errors()->all()]);

                     }else{

                         $result=User::where(['username'=>$parqam['username'],'email'=>$parqam['email']])->select('username','mobile')->get();

                         if($result==false){

                             return json_encode(['code'=>0,'data'=>'','msg'=>'用户名不存在！']);

                         }else{

                             $flag=User::where('email',$request->resetpwdemail)->update(['_token'=>$request->_token]);


                                Mail::send('email',['user'=>$parqam],function($message) use($parqam) {

                                     $message ->to($parqam['email'])->subject('重置密码');

                                 });

                                 if(count(Mail::failures())>0 && $flag==false){

                                     return json_encode(['code'=>0,'data'=>'','msg'=>'邮件发送失败！']);

                                 }else{

                                     return json_encode(['code'=>1,'data'=>'','msg'=>'邮件发送成功！']);
                                 }

                         }

                     }


                 }catch (\PDOException $e){


                     return json_encode(['code'=>0,'data'=>'','msg'=>$e->getMessage()]);

                 }

             }


         }


    }

    /**
     * author 李文俊
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function resetemail(Request $request){

        $token=User::where('_token',$request->_token)->select('email','_token','jobnum','username')->first();
     if($token->_token==$request->_token){

         if($request->isMethod('post')){

             try{

                 $param['email']=$request->email;
                 $param['password']=$request->password;
                 $repassword=$request->repassword;
                 $rules=[ 'email' => 'required|email'];

                 $message=[
                     'email.required' => '邮箱不能为空！',
                     'email.email'  => '邮箱不合法！！',
                 ];

                 $validate= Validator::make(['email'=>$param['email']],$rules,$message);
                 if($validate->fails()){

                     return json_encode(['code'=>0,'data'=>'','msg'=>$validate->errors()->all()]);

                 }else{

                     if($repassword!=$param['password']){

                         return json_encode(['code'=>0,'data'=>'','msg'=>'两次密码不一致！']);

                     }else{

                         $result=User::where(['jobnum'=>$token->jobnum])->update(['password'=>encrypts($token->jobnum.$param['password'],'E')]);

                         if($result===false){
                             writelog(session('uid'),$token->username,'用户【'.$token->username.'】重置密码失败',0);
                             return json_encode(['code'=>0,'data'=>'','msg'=>'重置密码失败！']);

                         } else{
                             writelog(session('uid'),$token->username,'用户【'.$token->username.'】重置密码成功',1);
                             return json_encode(['code'=>1,'data'=>'','msg'=>'重置密码成功！']);

                         }
                     }


                 }

             }catch (\PDOException $e){

                 return json_encode(['code'=>0,'data'=>'','msg'=>$e->getMessage()]);

             }

         }

         return view('/resetpwd',['token'=>$token]);
     }else{


         return view("/login");
     }


    }

}