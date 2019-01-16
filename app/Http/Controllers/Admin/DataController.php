<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use Illuminate\Support\Facades\DB;
class DataController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){

        $tables = DB::select('show table status');
        return view('data.index',["data"=>$tables]);
    }

    public function back(){


        return view("data.back");
    }

    /**
     * author 李文俊
     * 数据库表简单优化
     * @param Request $request
     * @return array|string
     */
    public function optimize(Request $request){

        if($request->ajax()){

            $table=$request->ids;
            try{

                if(empty($table)){

                    return json_encode("请指定优化的表");
                }

                if(is_array($table)){

                   foreach ($table as $v){

                       $flag=DB::select("OPTIMIZE  TABLE `$v`");
                   }
                    if($flag){

                        return (['code' => 1, 'data' => '', 'msg' => ("【数据库优化成功！】")]);
                    }else{


                        return (['code' => 0, 'data' => '', 'msg' =>("【数据库优化失败！】")]);
                    }

                }else{

                    $result=DB::select("OPTIMIZE  TABLE `$table`");
                    if($result){

                        return (['code' => 1, 'data' => '', 'msg' => ("【数据库".$table."优化成功！】")]);
                    }else{


                        return (['code' => 0, 'data' => '', 'msg' =>("【数据库".$table."优化失败！】")]);
                    }

                }

            }catch (\PDOException $e){

                return (['code' => 0, 'data' => '', 'msg' =>utf8_encode($e->getMessage())]);

            }
        }



       }

    /**
     * author 李文俊
     * 数据库表简单修复
     * @param Request $request
     * @return array|string
     */
    public function repair(Request $request){

        if($request->ajax()){

            $table=$request->ids;

            try{

                if(empty($table)){

                    return json_encode("请指定修复的表");
                }

                if(is_array($table)){

                    foreach ($table as $v){

                        $flag=DB::select("REPAIR  TABLE `$v`");
                    }
                    if($flag){

                        return (['code' => 1, 'data' => '', 'msg' => ("【数据库修复成功！】")]);
                    }else{


                        return (['code' => 0, 'data' => '', 'msg' =>("【数据库修复失败！】")]);
                    }
                }else{

                    $result=DB::select("REPAIR TABLE `$table`");
                    if($result){


                        return (['code' => 1, 'data' => '', 'msg' => ("【数据库".$table."修复成功！】")]);
                    }else{


                        return (['code' => 0, 'data' => '', 'msg' =>("【数据库".$table."修复失败！】")]);
                    }

                }

            }catch (\PDOException $e){

                return (['code' => 0, 'data' => '', 'msg' =>utf8_encode($e->getMessage())]);

            }
        }



    }


    public function export(Request $request,$id=null){

        if($request->isMethod('post')){

            try{

                $tables= $request->ids;
                $type= $request->type;
                if (!empty($tables) && is_array($tables)) { //初始化
                    $path = config('app.data_backup_path');
                    is_dir($path) || mkdir($path, 0755, true);
                    //读取备份配置
                    $config = [
                        'path' => realpath($path) . DIRECTORY_SEPARATOR,
                        'part' => config('app.data_backup_part_size'),
                        'compress' => config('app.data_backup_compress'),
                        'level' => config('app.data_backup_compress_level'),
                    ];

                    //检查是否有正在执行的任务
                    $lock = "{$config['path']}backup.lock";
                    if (is_file($lock)) {

                        return (['code' => 0, 'data' => '', 'msg' => "检测到有一个备份任务正在执行，请稍后再试！"]);

                    }
                    file_put_contents($lock, time()); //创建锁文件
                    //检查备份目录是否可写
                    if(!is_writeable($config['path'])){

                        return (['code' => 0, 'data' => '', 'msg' => "备份目录不存在或不可写，请检查后重试！"]);
                    }
                    session(['backup_config',$config]);
                    //生成备份文件信息
                    $file = ['name' => date('Ymd-His', time()),];
                    session('backup_file', $file);
                    $filename=$config['path'].$file['name'].".sql";
                    $mysql = config('database.connections.mysql'); //从配置文件中获取数据库信息
                    $tables=implode(",",$tables);
                    $tables=str_replace(',',' ',$tables);
                    if($type==0){

                             $start=exec("D:\wamp64\bin\mysql\mysql5.7.14\bin\mysqldump   -u".$mysql['username']." -p".$mysql['password']." --no-data --databases ".$mysql['database']." --tables ".$tables." > ".$filename); //指定表结构

                    }else{

                            $start = exec("D:\wamp64\bin\mysql\mysql5.7.14\bin\mysqldump   -u" . $mysql['username'] . " -p" . $mysql['password'] . " --default-character-set=utf8 " . $mysql['database'] . " -n --tables " . $tables . " > " . $filename); //指定表结构和数据
                    }
                    if (false ===$start) { //出错
                        return (['code' => 0, 'data' => '', 'msg' =>'备份出错！']);
                    }
                    else {

                            //备份完成，清空缓存
                            unlink($lock);
                            session()->forget('backup_tables');
                            session()->forget('backup_file');
                            session()->forget('backup_config');
                            return (['code' => 1, 'data' => '', 'msg' => '备份完成！']);
                    }
                }else{

                    return (['code' => 0, 'data' => '', 'msg' =>'请选择要备份的数据表！']);
                }

            }catch (\PDOException $e){


                return (['code' => 0, 'data' => '', 'msg' =>utf8_encode($e->getMessage())]);

            }

        }



    }

}
