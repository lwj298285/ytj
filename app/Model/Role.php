<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class Role extends Model{

    protected $table = 'auth_group';
    public $incrementing=false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    /**
     * @return int|string
     */
    protected function getDateFormat()
    {
        return time();//return当前时间戳
        //输出的时候已经帮我们格式化了不想格式化需加函数asDateTime()
    }

    public function user(){

        return $this->belongsToMany(User::class);
    }

    public function menu(){

        return $this->belongsToMany(Menu::class);

    }

}