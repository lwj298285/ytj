<?php

namespace App\Policies;

use App\Model\User;
use App\Model\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the role can view the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function view(User $user, Role $post)
    {
        //

    }

    /**
     * Determine whether the role can create posts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {

    }

    /**
     * Determine whether the role can update the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function update(User $user, Role $post)
    {
        //
    }

    /**
     * Determine whether the role can delete the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function delete(User $user, Role $post)
    {
        //
    }

    public function before($user,$ability){

        if($user->isSuperAdmin()){
            return true;

        }

    }
}
