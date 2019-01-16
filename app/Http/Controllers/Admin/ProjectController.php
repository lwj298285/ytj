<?php
/**
 * Created by PhpStorm.
 * User: 22162
 * Date: 2018/9/20
 * Time: 18:31
 */

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
class ProjectController extends Controller
{

    public function index(){

        return view("project.index");

    }

    public function Info(Request $request){

        if($request->ajax()){

            $id=$request->id;
            $result=DB::table('cate')->where(['id'=>$id])->first();

                return json_encode($result);

        }

    }

    public function add(Request $request){

        if($request->isMethod('post')){
            if($request->ajax()){
                try{
                    DB::beginTransaction();
                    $param=$request->all();
                    if(empty($param['id'])){

                        $param['add_time']=time();
                        unset($param['_token']);
                        $rules=[ 'title' => 'required|max:20|unique:cate'];
                        $message=[
                            'title.required' => '栏目名称不能为空！',
                            'title.max'  => '栏目名称超出字段长度！',
                            'title.unique'  => '栏目类型已存在！',
                        ];

                        $validate= Validator::make($param,$rules,$message);
                        if($validate->fails()){
                            DB::rollBack();
                            writelog(session('uid'),session('username'),'用户【'.session('username').'】验证失败！',0);
                            return json_encode(['code'=>0,'data'=>'','msg'=>$validate->errors()->all()]);

                        }else{
                            $param['id']=DB::table('cate')->max('id')+1;
                            $param['sort']=$param['id'];
                            $result=DB::table('cate')->insert($param);
                            if($result==true){
                                DB::commit();
                                writelog(session('uid'),session('username'),'用户【'.session('username').'】添加栏目类型成功！',1);
                                return json_encode(['code'=>1,'data'=> $param,'msg'=>'添加栏目类型成功！']);

                            }else{
                                DB::rollBack();
                                writelog(session('uid'),session('username'),'用户【'.session('username').'】添加栏目类型失败！',0);
                                return json_encode(['code'=>0,'data'=>$param,'msg'=>$this->errors()]);
                            }

                        }

                    }else{

                        $param['end_time']=time();
                        if(empty($param['status']))
                            $param['status']=0;

                        unset($param['_token']);
                        $rules=[ 'title' => 'required|max:20,unique:cate'.$request->param['title']];
                        $message=[
                            'title.required' => '栏目名称不能为空！',
                            'title.max'  => '栏目名称超出字段长度！',
                            'title.unique'  => '栏目类型已存在！',
                        ];
                        $validate= Validator::make($param,$rules,$message);
                        if($validate->fails()){
                            DB::rollBack();
                            writelog(session('uid'),session('username'),'用户【'.session('username').'】验证失败！',0);
                            return json_encode(['code'=>0,'data'=>'','msg'=>$validate->errors()->all()]);

                        }else{
                            $result=DB::table('cate')->where(['id'=>$param['id']])->update($param);
                            if($result==true){
                                DB::commit();
                                writelog(session('uid'),session('username'),'用户【'.session('username').'】更新栏目类型成功！',1);
                                return json_encode(['code'=>1,'data'=> $param,'msg'=>'更新栏目类型成功！']);

                            }else{
                                DB::rollBack();
                                writelog(session('uid'),session('username'),'用户【'.session('username').'】更新栏目类型失败！',0);
                                return json_encode(['code'=>0,'data'=>$param,'msg'=>$this->errors()]);
                            }

                        }

                    }

                }catch (\PDOException $e){
                    DB::rollBack();
                    return json_encode(['code'=>0,'data'=>'','msg'=>$e->getMessage()]);
                }
            }
        }else{


            return view("project.add");


        }



    }

    /**
     * @return \think\response\Json
     */

    public function giveCate(Request $request)
    {
        if ($request->ajax()) {
            $result = DB::table('cate')->where('isdel', 1)->orderBy('sort')->get();

            $str = '[{"id":"0","name":"栏目类型", "open":"true","isParent":"true","childOuter":"false","children":[';

            if ($result) {
                foreach ($result as $key => $vo) {
                    $str .= '{ "id": "' . $vo->id . '", "pid":"0","name":"' . $vo->title . '"},';

                }

                $str = substr($str, 0, -1);

            }

            $str .= ']}]';


            return json_encode(['code' => 1, 'data' => $str, "msg" => "OK"]);
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
                $group_access=DB::table('cate')->where('id',$request->id)->delete();
                if($rule && $group_access){

                    DB::commit();
                    writelog(session('uid'),session('username'),'用户【'.session('username').'】删除栏目类型成功！',1);
                    return json_encode(['code'=>1,'data'=> '','msg'=>'删除栏目类型成功！']);

                }else{

                    DB::rollBack();
                    writelog(session('uid'),session('username'),'用户【'.session('username').'】删除栏目类型失败！',0);
                    return json_encode(['code'=>0,'data'=>'','msg'=>'删除栏目类型失败！']);

                }

            }catch (\PDOException $e){

                DB::rollBack();
                return json_encode(['code'=>0,'data'=>'','msg'=>$e->getMessage()]);

            }
        }

    }

    //   调整服务行业排序
    public function softEdit(Request $request)
    {
        DB::beginTransaction();
        try{
          $param=$request->all();
            $softId=$this->where(['id'=>$param['id']])->value('sort');
            $targerSoftId=DB::table('cate')->where(['id'=>$param['targetid']])->value('sort');

            if ($softId >$targerSoftId)
                $map['softid']=['between',$targerSoftId.','. $softId];
            else
                $map['softid']=['between',$softId.','.$targerSoftId];


            if ($param['type']=="prev") {

                if ($softId >$targerSoftId)
                {
//                    $map['softid'] = ['between', $targerSoftId . ',' . ($softId-1)];
                    DB::table('cate')->whereBetween('sort',[$targerSoftId,($softId-1)])->increment('sort',1);
                    DB::table('cate')->where('id', $param['id'])->update('sort', $targerSoftId);
                } else{
//                    $map['softid']=['between',($softId+1).','.($targerSoftId-1)];
                    DB::table('cate')->whereBetween('sort',[($softId+1),($targerSoftId-1)])->decrement('sort',1);
                    DB::table('cate')->where('id', $param['id'])->update('sort', $targerSoftId-1);
                }

            }else{

                if ($softId >$targerSoftId)
                {
//                    $map['softid'] = ['between', ($targerSoftId+1) . ',' . ($softId-1)];
                    DB::table('cate')->whereBetween('sort',[($targerSoftId+1),($softId-1)])->increment('sort');
                    DB::table('cate')->where('id', $param['id'])->update('sort', $targerSoftId+1);
                } else{
//                    $map['softid']=['between',($softId+1).','.$targerSoftId];
                    DB::table('cate')->whereBetween('sort',[($softId+1),$targerSoftId])->decrement('sort');
                    DB::table('cate')->where('id', $param['id'])->update('sort', $targerSoftId);
                }

            }

            Db::commit();
            return json_encode(['code' => 1, 'data' => '', 'msg' => '调整栏目类型成功']);

        }catch( PDOException $e){
            Db::rollback();
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

}