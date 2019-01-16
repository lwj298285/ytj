<?php

namespace App\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'admin';
    public $timestamps = false;
    protected $guarded = [];
    /**
     * @param $haspermison
     * @return bool
     */
    public function haspermison($haspermison){

        $data=DB::table('auth_group_access as a')->join('auth_group as r','group_id','=','id')
            ->where(['uid'=>session('uid'),'r.status'=>1,'r.isdel'=>1])
            ->select('rules')
            ->first();
        $rules=explode(',',$data->rules);
        if(in_array($haspermison,$rules)){
           return true;
        }
         return false;
    }

}
