<?php

namespace App\Http\Controllers\Admin;

use App\Model\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;

class LogController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){

        return view('log.index');
    }

    /**
     * author 李文俊
     * @param Request $request
     * @return string
     */
    public function operate_log(Request $request)
    {

        if ($request->ajax()) {
            try {
                $admin_name = $request->admin_name;
                $startdate = $request->startdate;
                $enddate = $request->enddate;
                if (empty(trim($admin_name))) {
                    $admin_name = session('username');
                }
                $startdate=strtotime($startdate."00:00:00");
                $enddate=strtotime($enddate."23:59:59");

                $result = Log::where('admin_name', 'like', '%' . $admin_name . '%')->where('isdel', 1)->whereBetween('add_time', [$startdate, $enddate])->orderBy('add_time', 'desc')->get();
                if ($request == false)
                    return json_encode(['code' => 0, 'data' => '', 'msg' => '获取日志记录失败！']);
                else
                    return json_encode(['code' => 1, 'data' => $result, 'msg' => '']);
            } catch (\PDOException $e) {

                return json_encode(['code' => 0, 'data' => '', 'msg' => $e->getMessage()]);
            }
    }
    }

    /**
     * author 李文俊
     * @param Request $request
     * @return string
     */
    public function del(Request $request){

        if($request->ajax()) {
            try {
                $id = $request->id;
                $result = Log::where('id',$id)->delete();
                if ($result == false) {

                    return json_encode(['code'=>0,'data'=>'','msg'=>'删除失败！']);

                }else{

                    return json_encode(['code'=>1,'data'=>'','msg'=>'删除成功！']);
                }

            }catch (\PDOException $e){

                return json_encode(['code'=>0,'data'=>'','msg'=>$e->getMessage()]);

            }
        }
    }

    public function curdel(Request $request){

        if($request->ajax()){
            try{
                $admin_name=$request->admin_name;
                $startdate=$request->startdate;
                $enddate=$request->enddate;

                $result=Log::where('admin_name',$admin_name)->where('isdel',1)->whereBetween('add_time',[$startdate,$enddate])->orderBy('add_time','desc')->delete();
                if ($result == false) {

                    return json_encode(['code'=>0,'data'=>'','msg'=>'删除失败！']);

                }else{

                    return json_encode(['code'=>1,'data'=>'','msg'=>'删除成功！']);
                }


            }catch (\PDOException $e){

                return json_encode(['code'=>0,'data'=>'','msg'=>$e->getMessage()]);

            }


        }

    }

    /***
     * author 李文俊
     * @param Request $request
     * @return string
     */
    public function Alldel(Request $request){

        if($request->ajax()){

            try{
                $result=Log::where('isdel',1)->delte();

                if ($result == false) {

                    return json_encode(['code'=>0,'data'=>'','msg'=>'删除失败！']);

                }else{

                    return json_encode(['code'=>1,'data'=>'','msg'=>'删除成功！']);
                }

            }catch (\PDOException $e){

                return json_encode(['code'=>0,'data'=>'','msg'=>$e->getMessage()]);

            }

        }

    }

}
