<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function followUser(User $user){
        // you cannot follow yourself
        if( $user->id == auth()->user()->id ){
            return back()->with("failure", "You cant follow yourself");
        }
        // you are not allow to follow someone you're already following
        $checkFollowing = Follow::where([["user_id", "=" , auth()->user()->id], ["followeduser", "=", $user->id ]])->count();

        if($checkFollowing > 0){
            return back()->with("failure","You already following this user");
        }

        $newFollow = new Follow;
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();

        return back()->with("success","You follow this user");
    }

    public function UnFollowUser(User $user){
        Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->delete();
        return back()->with('success', 'User succesfully unfollowed.');

       
    }
}
