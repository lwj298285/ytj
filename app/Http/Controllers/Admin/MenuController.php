<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\common\leftnav;
use Illuminate\Support\Facades\Log;
use App\Model\Menu;
use Validator;
class MenuController extends Controller
{
    /**
     * 渲染菜单信息
     * author 李文俊
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu=DB::table('auth_rule')->where('isdel',1)->orderBy('sort','asc')->get();
        $nav=new leftnav();
        $arr=$nav::rule(json_decode($menu,true));
        return view('menu.index',['menu'=> $arr]);
    }

    /**
     * 新增菜单
     * author 李文俊
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function create(Request $request){

        if($request->isMethod('post')){
            if($request->ajax()){
                try{
                    DB::beginTransaction();
                    $param=$request->all();
                    unset($param['_token']);
                    $param['id']=Menu::max('id')+1;
                    $rules=[ 'title' => 'required|max:20',
                        'name' => 'required|max:80',];
                    $message=[
                        'title.required' => '菜单名称不能为空！',
                        'title.max'  => '菜单名称超出字段长度！',
                        'name.required'  => '节点不能为空！',
                        'name.max'  => '节点超出字段长度！',
                    ];

                    $validate= Validator::make($param,$rules,$message);
                    if($validate->fails()){
                        DB::rollBack();
                        writelog(session('uid'),session('username'),'用户【'.session('username').'】验证失败！',0);
                        return json_encode(['code'=>0,'data'=>'','msg'=>$validate->errors()->all()]);

                    }else{

                        $result=Menu::create($param);
                        if($result==true){
                            DB::commit();
                            writelog(session('uid'),session('username'),'用户【'.session('username').'】添加菜单成功！',1);
                            return json_encode(['code'=>1,'data'=> $param,'msg'=>'添加菜单成功！']);

                        }else{
                            DB::rollBack();
                            writelog(session('uid'),session('username'),'用户【'.session('username').'】添加菜单失败！',0);
                            return json_encode(['code'=>0,'data'=>'','msg'=>$this->errors()]);
                        }

                    }
                }catch (\PDOException $e){
                    DB::rollBack();
                    return json_encode(['code'=>0,'data'=>'','msg'=>$e->getMessage()]);
                }
            }
        }
    }
    /**
     * 菜单排序
     * author 李文俊
     * @param Request $request
     * @return array
     */
    public function softEdit(Request $request){
        if($request->isMethod('post')){
            if($request->ajax()){
                DB::beginTransaction();
                try{
                    $rules=[
                        'sort'=>"integer"
                    ];
                    $message=[
                        'sort.integer' => '字段为整型！',
                    ];
                    $param=$request->all();
                    unset($param['_token']);
                    $validate= Validator::make($param,$rules,$message);
                    if($validate->fails()){
                        DB::rollback();
                        writelog(session('uid'),session('username'),'用户【'.session('username').'】验证失败！',0);
                        return json_encode(['code' => 0, 'data' => '', 'msg' =>$validate->errors()->all()]);

                    }else{

                        $arr=array();
                        if(!empty($param)){
                            $i=1;
                            foreach ($param as $id => $sortid){
                                if(!empty($sortid)){
                                    $arr[$i]['id'] = $id;
                                    $arr[$i]['sort'] = $sortid;
                                    $result=Menu::where('id', $id)->update(['sort'=>$sortid]);
                                    $i++;
                                }
                            }

                        }

                        if($result==false){
                            DB::rollback();
                            writelog(session('uid'),session('username'),'用户【'.session('username').'】菜单排序失败！',0);
                            return json_encode(['code' => 0, 'data' => '', 'msg' =>'']);

                        }else{
                            DB::commit();
                            writelog(session('uid'),session('username'),'用户【'.session('username').'】菜单排序成功！',1);
                            return json_encode(['code' => 1, 'data' => '', 'msg' =>'菜单排序成功！']);

                        }
                    }
                }catch (\PDOException $e){
                    DB::rollback();
                    return json_encode(['code' => 0, 'data' => '', 'msg' =>$e->getMessage()]);

                }

            }

        }

    }

    /**
     * 更新菜单
     * author 李文俊
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public  function edit_rule(Request $request){

        if($request->isMethod('post')){
            if($request->ajax()){
                try{
                    DB::beginTransaction();
                    $param=$request->all();
                   unset($param['_token']);
                    $rules=[ 'title' => 'required|max:20',
                             'name' => 'required|max:80,unique:auth_rule'.$request->id,];
                    $message=[
                        'title.required' => '菜单名称不能为空！',
                        'title.max'  => '菜单名称超出字段长度！',
                        'name.unique'  => '节点已存在！',
                        'name.required'  => '节点不能为空！',
                        'name.max'  => '节点超出字段长度！',
                    ];
                    $validate= Validator::make($param,$rules,$message);
                    if($validate->fails()){
                        DB::rollBack();
                        return json_encode(['code'=>0,'data'=>'','msg'=>$validate->errors()->all()]);

                    }else {
                        $result=Menu::where('id',$param['id'])->update($param);;

                        if($result==true){

                            DB::commit();
                            return json_encode(['code'=>1,'data'=> '','msg'=>'编辑菜单成功！']);

                        }else{
                            DB::rollBack();
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
        $data=DB::table('auth_rule')->where('id',$id)->get();

        return view('menu.edit_rule',['data'=>$data]);

    }
    /**
     * author 李文俊
     * @param Request $request
     * @return string
     */
    public function del(Request $request){

            if ($request->ajax()) {
                try {
                    DB::beginTransaction();
                    $rule = Menu::find($request->id);
                    if ($rule->delete()) {

                        DB::commit();
                        return json_encode(['code' => 1, 'data' => '', 'msg' => '删除菜单成功！']);

                    } else {

                        DB::rollBack();
                        return json_encode(['code' => 0, 'data' => '', 'msg' => $this->errors()]);

                    }

                } catch (\PDOException $e) {

                    DB::rollBack();
                    return json_encode(['code' => 0, 'data' => '', 'msg' => $e->getMessage()]);

                }

            }
    }

    /**
     * 菜单状态
     * author 李文俊
     * @param Request $request
     * @return string
     */
    public function status(Request $request){

        if($request->ajax()){
            try{
                DB::beginTransaction();
                $rule=Menu::find($request->id);
                $rule->status=$request->status;
                $status=Db::table('auth_rule')->where('id',$request->id)->value('status');
                if($status==1)
                    $status=0;
                elseif ($status==0)
                    $status=1;

                $rule->status=$status;
                $result=$rule->save();
                if($result==true){

                    DB::commit();
                    if($status==1)
                           return json_encode(['code'=>1,'data'=> $status,'msg'=>'开启！']);
                    else
                           return json_encode(['code'=>0,'data'=> $status,'msg'=>'禁用！']);
                }else{

                    DB::rollBack();
                    return json_encode(['code'=>0,'data'=>'','msg'=>$this->errors()]);

                }

            }catch (\PDOException $e){

                DB::rollBack();
                return json_encode(['code'=>0,'data'=>'','msg'=>$e->getMessage()]);

            }

        }

    }
}
