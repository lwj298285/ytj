<?php

namespace App\Http\Controllers\Admin;

use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Model\Menu;
class IndexController extends Controller
{


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $menu=new Menu();
          $rule=DB::table('auth_group')->where('id',session('groupid'))->first();
        $param=$menu->getMenu($rule->rules);

        return view('/index',['menu'=>$param]);
    }

    /**
     * author 李文俊
     * 清除缓存
     * @param Request $request
     * @return string
     */
    public function clear_cache(Request $request){
        if($request->ajax()){
            $result=Cache::flush();
            if($request->type=='get'){

                if($result==false){
                    writelog(session('uid'),session('username'),'用户【'.session('username').'】清除缓存失败',0);
                    return json_encode(['code' => 0, 'data' => '', 'msg' => '清除缓存失败']);

              }else{
                    writelog(session('uid'),session('username'),'用户【'.session('username').'】缓存清除成功',1);
                  return json_encode(['code' => 1, 'data' => '', 'msg' => '缓存已清除']);


              }
            }



        }


    }

    /**
     * author 李文俊
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info(){

        return view('index/index');

    }

    /**
     * 密码更新
     * author 李文俊
     * @param Request $request
     * @return string
     */
    public function change(Request $request){

        if($request->isMethod('post')){
            if($request->ajax()){
                $password=$request->password;
                $newpassword=$request->newpassword;
                session(['_token'=>$request->_token]);

                try{
                 if($request->_token=session('_token')){

                     $oldpassword=DB::table('admin')->where('id',session('uid'))->value('password');
                     if(encrypts(session('jobnum').$password,'E')!=$oldpassword){
                         writelog(session('uid'),session('username'),'用户【'.session('username').'】登录密码不正确',0);
                         return json_encode(['code'=>-2,'data'=>'','msg'=>'登录密码不正确！']);

                     }else {
                         $user = User::find(session('uid'));
                         $user->password = encrypts(session('jobnum') . $newpassword, 'E');
                         $result = $user->save();
                         if ($result == false){
                             writelog(session('uid'),session('username'),'用户【'.session('username').'】修改密码失败',0);
                             return json_encode(['code' => 0, 'data' => '', 'msg' => '修改失败！']);

                     }else {
                     writelog(session('uid'),session('username'),'用户【'.session('username').'】修改密码成功！',1);
                     return json_encode(['code' => 1, 'data' => '', 'msg' => '修改成功！']);

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fxrz(){

        return view("/qxrz");

    }

}
