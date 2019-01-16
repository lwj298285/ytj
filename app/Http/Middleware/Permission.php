<?php

namespace App\Http\Middleware;
use Closure;
use http\Url;
use App\Model\Menu;
use App\Model\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $routes=$request->path();
        $urls=$request->route()->getActionName();
        list($controller,$action)=explode('@',$urls);
        $getaction=strtolower(substr($action,0,3));
        $giveaction=strtolower(substr($action,0,4));

        $route=chop(preg_replace("/[0-9]/","",$routes),'/');
        if(session('groupid')==(int)1)
            return true;

        if($route=="/")
            return $next($request);

        if($getaction=='get' || $giveaction=='give')
            return $next($request);

        $url=new Menu();
        $result=$url->where('name',$route)->value('id');
        if($result){
            $user=new User();

            if($user->haspermison($result))
                return $next($request);
        }

        echo "没有权限，请联系管理员";exit;

    }

}
