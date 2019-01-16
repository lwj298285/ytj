<?php

namespace App\Http\Controllers\Admin;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Admin\Controller;
use Illuminate\Support\Facades\Log;
use Validator;
class UserController extends Controller
{
    /**
     * author 李文俊
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public  function index(){

        $role=DB::table('auth_group')->where('isdel',1)->select('id','title')->get();
        return view('user.index',['role'=>$role]);
    }

    /**
     * author 李文俊
     * @param Request $request
     * @return string
     */
    public function getUser(Request $request)
    {
        if($request->ajax()){
            try{
                $param=$request->all();
                $map=[];
                if(!empty($param['groupid'])){
                    $map['groupid'] =$param['groupid'];
                    $map['a.isdel']=1;
                    $userdata=DB::table('admin as a')
                        ->join('auth_group as r','groupid','=','r.id')
                        ->where($map)
                        ->select('a.*','r.title')
                        ->orderBy('jobnum','asc')
                        ->get();
                }else{

                    $map['a.isdel']=1;
                    $userdata=DB::table('admin as a')
                        ->join('auth_group as r','groupid','=','r.id')
                        ->where($map)
                        ->select('a.*','r.title')
                        ->orderBy('jobnum','asc')
                        ->get();
                }


                return json_encode(['code' => 1,  'data' => $userdata, 'msg' =>'ok']);

            }catch (\PDOException $e){

                return json_encode(['code' => 0,  'data' => '', 'msg' => $e->getMessage()]);

            }
        }

    }

    /**
     * author 李文俊
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function useradd(Request $request){

        if($request->isMethod('post')){
           if($request->ajax()) {
               try {
                   DB::beginTransaction();
                   $param = $request->all();
                   unset($param['repassword']);
                   $param['id'] = User::max('id') + 1;
                   $jobnum = User::max('jobnum');
                   $jobnums=explode('-',$jobnum);
                   if($jobnums[0]==date('ymd',time())){

                       $param['jobnum']=$jobnums[0]."-00".($jobnums[1]+1);

                   }else{

                       $param['jobnum']=date('ymd',time())."-001";

                   }

                   if (empty($param['status']))
                       $param['status'] = 0;
                   $param['password']=encrypts($param['jobnum'].$param['password'],'E');
                   $rules = [
                       'username' => "required|max:50|unique:admin",
                       'email' => "required|unique:admin",
                       'mobile' => "required|max:15|unique:admin",
                   ];
                   $message = [
                       'username.required' => '用户名不能为空！',
                       'mobile.max' => '手机号不合法！',
                       'mobile.required' => '手机号不能为空！',
                       'mobile.unique' => '手机号已存在！',
                       'username.unique' => '用户名已存在！',
                       'email.required' => '邮箱不能为空！',
                       'email.unique' => '邮箱已存在！',
                   ];

                   $validate = Validator::make($param, $rules, $message);

                   if ($validate->fails()) {

                       DB::rollBack();
                       writelog(session('uid'),session('username'),'用户【'.session('username').'】验证用户字段失败！',0);
                       return json_encode(['code' => 0, 'data' => '', 'msg' => $validate->errors()->all()]);

                   } else {

                       $result = User::create($param);
                       $group_access=DB::table('auth_group_access')->insert(['uid'=>$param['id'],'group_id'=>$param['groupid']]);
                        if($result==false &&$group_access){
                            DB::rollBack();
                            writelog(session('uid'),session('username'),'用户【'.session('username').'】注册用户失败！',0);
                            return json_encode(['code'=>0,'data'=>'','msg'=>$this->errors()]);

                        }else{
                            DB::commit();
                            writelog(session('uid'),session('username'),'用户【'.session('username').'】注册用户成功！',1);
                            return json_encode(['code'=>1,'data'=> $param,'msg'=>'注册用户成功！']);
                        }
                   }
               } catch (\PDOException $e) {

                   DB::rollBack();
                   return json_encode(['code' => 0, 'data' => '', 'msg' => $e->getMessage()]);
               }

           }

        }
        $role=DB::table('auth_group')->where('isdel',1)->select('id','title')->get();
        return view('user.useradd',['role'=>$role]);

    }

    /**
     * author 李文俊
     * @param Request $request
     * @return string
     */
    public function userEdit(Request $request){

        if($request->isMethod('post')){
            if($request->ajax()) {
                DB::beginTransaction();
                try {
                    $param = $request->all();
                    if (empty($param['status']))
                        $param['status'] = 0;
                    $param['password']=encrypts($param['jobnum'].$param['repassword'],'E');
                    $rules = [
                        'username' => "required|max:50,unique:admin".$request->jobnum,
                        'email' => "required|max:100,unique:admin".$request->jobnum,
                        'mobile' => "required|max:15,unique:admin".$request->jobnum
                    ];
                    $message = [
                        'username.required' => '用户名不能为空！',
                        'username.unique' => '用户名已存在！',
                        'mobile.max' => '手机号不合法！',
                        'mobile.required' => '手机号不能为空！',
                        'mobile.unique' => '手机号已存在！',
                        'email.required' => '邮箱不能为空！',
//                        'email.email' => '邮箱不合法！',
                        'email.unique' => '邮箱已存在！',
                    ];

                    $validate = Validator::make($param, $rules, $message);

                    if ($validate->fails()) {

                        DB::rollBack();
                        writelog(session('uid'),session('username'),'用户【'.session('username').'】验证用户字段失败！',0);
                        return json_encode(['code' => 0, 'data' => '', 'msg' => $validate->errors()->all()]);

                    } else {

                           $pass=DB::table('admin')->where('jobnum',$param['jobnum'])->value('password');

                           if($pass!= $param['password']){

                               writelog(session('uid'),session('username'),'用户【'.session('username').'】登录密码不正确！',0);
                               return json_encode(['code'=>-2,'data'=>'','msg'=>'登录密码不正确！']);


                           }else{
                                unset($param['repassword']);
                               $result=User::where('jobnum',$param['jobnum'])->update($param);;
                               $group_access=DB::table('auth_group_access')->where('uid',$param['id'])->update(['group_id'=>$param['groupid']]);
                               if($result==false &&$group_access){
                                   DB::rollBack();
                                   writelog(session('uid'),session('username'),'用户【'.session('username').'】更新用户信息失败！',0);
                                   return json_encode(['code'=>0,'data'=>'','msg'=>$this->errors()]);

                               }else{
                                   DB::commit();
                                   writelog(session('uid'),session('username'),'用户【'.session('username').'】更新用户信息成功！',1);
                                   return json_encode(['code'=>1,'data'=> $param,'msg'=>'更新成功！']);
                               }

                           }

                    }
                } catch (\PDOException $e) {

                    DB::rollBack();
                    return json_encode(['code' => 0, 'data' => '', 'msg' => $e->getMessage()]);
                }

            }

        }

    }
    /**
     * author 李文俊
     * @param Request $request
     * @return string
     */
    public function del(Request $request){

            if($request->ajax()){
                try{
                    DB::beginTransaction();
                    $rule=User::where('id',$request->id)->delete();
                    $group_access=DB::table('auth_group_access')->where('uid',$request->id)->delete();
                    if($rule && $group_access){

                        DB::commit();
                        writelog(session('uid'),session('username'),'用户【'.session('username').'】删除用户成功！',1);
                        return json_encode(['code'=>1,'data'=> '','msg'=>'删除用户成功！']);

                    }else{

                        DB::rollBack();
                        writelog(session('uid'),session('username'),'用户【'.session('username').'】删除用户失败！',0);
                        return json_encode(['code'=>0,'data'=>'','msg'=>'删除用户失败！']);

                    }

                }catch (\PDOException $e){

                    DB::rollBack();
                    return json_encode(['code'=>0,'data'=>'','msg'=>$e->getMessage()]);

                }
            }

    }

    /**
     * author 李文俊
     * @param Request $request
     * @return string
     */
    public function user_state(Request $request){
        if($request->isMethod('post')) {
            if ($request->ajax()) {
                try {

                    $result=User::where('jobnum',$request->jobnum)->update(['status'=>$request->status]);

                    if ($result===false) {

                        writelog(session('uid'),session('username'),'用户【'.session('username').'】更新用户状态失败！',0);

                        return ['code' => -2, 'data' => '', 'msg' => '状态更新失败！'];

                    } else {

                        writelog(session('uid'),session('username'),'用户【'.session('username').'】更新用户状态成功！',1);

                        if ($request->status == 1)
                            return ['code' => 1, 'data' => '', 'msg' => '开启'];
                        else
                            return ['code' => 0, 'data' => '', 'msg' => '禁用'];
                    }

                } catch (\PDOException $e) {

                    return ['code' => -2, 'data' => '', 'msg' => $this->errors()];

                }
            }
        }
    }

}
