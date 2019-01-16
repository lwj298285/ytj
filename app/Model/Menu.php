<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Validator;
class Menu extends Model{

    protected $table = 'auth_rule';
    public $incrementing=false;
    protected $guarded = [];

    public function role(){

        return $this->belongsToMany(Role::class);
    }

    /**
     * @param string $nodeStr
     * @return array
     */
    public function getMenu($nodeStr = '')
    {
        //超级管理员没有节点数组
        $arr['isdel']=1;
        $arr['status']=1;
        $arr=explode(',',$nodeStr);
        if(empty($nodeStr))
            $result = DB::table('auth_rule')->where(['isdel'=>1,'status'=>1])->orderBy('sort')->get();
        else
            $result = DB::table('auth_rule')->whereIn('id',$arr)->where(['isdel'=>1,'status'=>1])->orderBy('sort')->get();

        $menu = prepareMenu(json_decode($result,true));
        return $menu;
    }

    /**
     * @return int|string
     */
    protected function getDateFormat()
    {
        return time();//return当前时间戳
        //输出的时候已经帮我们格式化了不想格式化需加函数asDateTime()
    }
}