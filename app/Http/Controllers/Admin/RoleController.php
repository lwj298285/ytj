<?php
namespace App\Http\Controllers\Admin;
use App\Model\Menu;
use App\Model\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Model\Role as Rule;
use Validator;
class RoleController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('role.index');
    }

    /**
     * author 李文俊
     * @param Request $request
     * @return array
     */
    public function getRole(Request $request){

        if($request->isMethod('get')){

            $key=$request->rule;
            try{
                if(empty($key))
                    $role=DB::table('auth_group')->where(['isdel'=>1])->get();
                else
                    $role=DB::table('auth_group')->where(['isdel'=>1,'title'=>$key])->get();

                return ['code'=>1,'data'=>$role,'msg'=>''];


            }catch (\PDOException $e){

                return ['code'=>0,'data'=>'','msg'=>$e->getMessage()];
            }

        }

    }

    /**
     * author 李文俊
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(Request $request){

        if($request->isMethod('post')){
            if($request->ajax()){
                try{
                    DB::beginTransaction();
                    $param['title']=$request->title;
                    $param['status']=$request->status;
                    $param['id']=Rule::max('id')+1;
                    $rule=[ 'title'=>'required|unique:auth_group|max:100'];
                    $message=[
                        'title.required' => '角色名称不能为空！',
                        'title.max'  => '超出字段长度！',
                        'title.unique'  => '角色名已存在！',
                    ];

                    $validate= Validator::make($param,$rule,$message);
                    if($validate->fails()){
                        DB::rollBack();
                        writelog(session('uid'),session('username'),'用户【'.session('username').'】角色字段验证失败！',0);
                        return json_encode(['code'=>0,'data'=>'','msg'=>$validate->errors()->all()]);

                    }else{
                        $rule=new Rule;
                        $rule->id=$param['id'];
                        $rule->title=$param['title'];
                        $rule->status=$param['status'];
                        $result=$rule->save();
                        if($result==true){
                            DB::commit();
                            writelog(session('uid'),session('username'),'用户【'.session('username').'】添加角色成功！',1);
                            return json_encode(['code'=>1,'data'=> $param,'msg'=>'添加角色成功！']);

                        }else{
                            DB::rollBack();
                            writelog(session('uid'),session('username'),'用户【'.session('username').'】添加角色失败！',1);
                            return json_encode(['code'=>0,'data'=>'','msg'=>$this->errors()]);
                        }

                    }
                }catch (\PDOException $e){
                    DB::rollBack();
                    return json_encode(['code'=>0,'data'=>'','msg'=>$e->getMessage()]);
                }
            }
        }
        return view('role.roleadd');
    }

    /**
     * author 李文俊
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public  function edit(Request $request){

        if($request->isMethod('post')){
            if($request->ajax()){
                try{
                    DB::beginTransaction();
                    $param['title']=$request->title;
                    $param['status']=$request->status;
                    $param['id']= $request->id;
                    $rules=[
                        'title'=>"required|max:100,unique:auth_group".$request->id
                    ];
                    $message=[
                        'title.required' => '角色名称不能为空！',
                        'title.max'  => '超出字段长度！',
                        'title.unique'  => '角色名已存在！',
                    ];

                    $validate= Validator::make($param,$rules,$message);
                    if($validate->fails()){
                        DB::rollBack();
                        writelog(session('uid'),session('username'),'用户【'.session('username').'】角色字段验证失败！',1);
                        return json_encode(['code'=>0,'data'=>'','msg'=>$validate->errors()->all()]);

                    }else {
                        $rule=Rule::find($request->id);
                        $rule->title=$request->title;
                        $rule->status=$request->status;
                        if(empty( $rule->status))
                            $rule->status=0;
                        $result=$rule->save();
                        if($result==true){

                            DB::commit();
                            writelog(session('uid'),session('username'),'用户【'.session('username').'】编辑角色成功！',1);
                            return json_encode(['code'=>1,'data'=> '','msg'=>'编辑角色成功！']);

                        }else{
                            DB::rollBack();
                            writelog(session('uid'),session('username'),'用户【'.session('username').'】编辑角色失败！',0);
                            return json_encode(['code'=>0,'data'=>'','msg'=>$this->errors()]);
                        }
                    }
                }catch (\PDOException $e){

                    DB::rollBack();
                    return json_encode(['code'=>0,'data'=>'','msg'=>$e->getMessage()]);

                }

            }

        }
        $id=$request->id;
        $data=DB::table('auth_group')->where('id',$id)->get();


            $action=$request->route()->getAction();

        return view('role.roleedit',['data'=>$data,'action'=>$action]);

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
                 $rule=Rule::find($request->id);
                 if($rule->delete()){

                     DB::commit();
                     writelog(session('uid'),session('username'),'用户【'.session('username').'】删除角色成功！',1);
                     return json_encode(['code'=>1,'data'=> '','msg'=>'删除角色成功！']);

                 }else{

                     DB::rollBack();
                     writelog(session('uid'),session('username'),'用户【'.session('username').'】删除角色失败！',0);
                     return json_encode(['code'=>0,'data'=>'','msg'=>$this->errors()]);

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
    public function status(Request $request){

        if($request->ajax()){
            try{
                DB::beginTransaction();
                $rule=Rule::find($request->id);

                $rule->status=$request->status;
                $result=$rule->save();
                $data=Rule::where(['isdel'=>1])->get();
                if($result==true){
                        if($request->id==session('groupid')){

                            $request->session()->flush();
                        }
                    DB::commit();
                    writelog(session('uid'),session('username'),'用户【'.session('username').'】更新角色状态成功！',1);
                    return json_encode(['code'=>1,'data'=> $data,'msg'=>'更新角色状态成功！']);

                }else{

                    DB::rollBack();
                    writelog(session('uid'),session('username'),'用户【'.session('username').'】更新角色状态失败！',0);
                    return json_encode(['code'=>0,'data'=>'','msg'=>$this->errors()]);

                }

            }catch (\PDOException $e){

                DB::rollBack();
                return json_encode(['code'=>0,'data'=>'','msg'=>$e->getMessage()]);

            }

        }

    }

    /**
     * 获取角色节点及节点分配
     * author 李文俊
     * @return mixed
     */
    public function giveAccess(Request $request)
    {
        if ($request->ajax()) {
          try{

            $role=Menu::where('isdel',1)->select('id','title','name','pid','sort')->get();
            $str = "";
            $id=$request->id;
            $group=DB::table('auth_group')->where('id',$id)->first();
            if(!empty($group)){
                $title=explode(',',$group->rules);
            }
            foreach($role as $key=>$vo){
                $str .= '{ "id": "' . $vo['id'] . '", "pId":"' . $vo['pid'] . '", "name":"' . $vo['title'].'"';

                if(!empty($title) && in_array($vo['id'], $title)){
                    $str .= ' ,"checked":1';
                }

                $str .= '},';
            }
            if($request->type=='get'){

                return json_encode(['code'=>1,'data'=>"[" . substr($str, 0, -1) . "]",'msg'=>'']);

            }

            if($request->type=="give"){

                $groupid=$request->id;
                $rule=$request->rule;

               $rules=Role::find($groupid);
                $rules->id=$groupid;
                $rules->rules= $rule;
                $result=$rules->save();
                  if($result==false){
                      writelog(session('uid'),session('username'),'用户【'.session('username').'】权限分配失败！',0);
                     return json_encode(['code'=>0,'data'=>'','msg'=>'权限分配失败！']);

                  }else{
                      writelog(session('uid'),session('username'),'用户【'.session('username').'】权限分配成功！',1);
                      return json_encode(['code'=>1,'data'=>'','msg'=>'权限分配成功！']);
                  }

            }
            }catch (\PDOException $e){

              return json_encode(['code'=>0,'data'=>'','msg'=>$e->getMessage()]);

          }

        }
    }
}