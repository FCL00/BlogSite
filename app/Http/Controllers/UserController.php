<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{   
    public function showHomePage(){

        if(auth()->check()){
            return view("homePageFeed", ['posts' => auth()->user()->feedPosts()->latest()->paginate(5)]);
        } else{
            return view("homePage");
        }
    }
    
    public function login(Request $request) {
        $incomingData = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt(['username' => $incomingData['loginusername'], 'password' => $incomingData['loginpassword']])) {
            $request->session()->regenerate();
            return redirect('/')->with("success", "You have successfully login");
        } else {
            return redirect('/')->with("failure", "Login attempt failed Try again");
        }
    }
    public function logout() {
        auth()->logout();
        return redirect('/')->with("success", "You have successfully logout");
    }

    public function register(Request $request){
        $incomingData = $request->validate([
            "username" => ['required', 'min:3', 'max:25', Rule::unique("users", "username")], //use rule set to table name and column name
            "email" => ['required', 'email', Rule::unique("users", "email")], //use rule set to table name and column name
            "password" => ['required', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'] // confirmed is shortcut for retype password
        ]);
        
        $user = User::create($incomingData);
        auth()->login($user); //login the user after registration
        return redirect('/')->with('success', "You are successfuly register");

        // Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised() 
        // Upper Lowercase and special character number strings uncrompromised password
        // encrypt the password before saving in the database
        // $incomingData['password'] = bcrypt($incomingData['password']);
    }

    private function getSharedData($user) {
        $currentlyFollowing = 0;

        if (auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        }

        View::share('sharedData', ['currentlyFollowing' => $currentlyFollowing, 
                                    'avatar' => $user->avatar, 
                                    'username' => $user->username, 
                                    'postCount' => $user->posts()->count(),
                                    'followerCount' => $user->followers()->count(),
                                    'followingCount' => $user->followingTheseUser()->count()
                                ]);
    }
 
    public function profile(User $user) {
        $this->getSharedData($user);
        return view('profile-post', ['posts' => $user->posts()->latest()->get()]);
    }

    public function profileFollowers(User $user) {
        $this->getSharedData($user);
        return view('profile-followers', ['followers' => $user->followers()->latest()->get()]);
    }

    public function profileFollowing(User $user) {
        $this->getSharedData($user);
        return view('profile-following', ['following' => $user->followingTheseUser()->latest()->get()]);
    }


    

    public function showAvatarForm(){
        return view("avatar-form");
    }

    public function storeAvatar(Request $request){

        $incomingFiles = $request->validate([
            "avatar" => 'required|image|max:3000'
        ]);
        
        $user = auth()->user(); 
        // get the user data in auth session 
        $filename = $user->id . '-' . uniqid() . '.jpg'; 
        // set the filename into the user id
        $imageData = Image::make($request->file('avatar'))->fit(120)->encode('jpg'); 
        //resize image and encode it in jpg format
        Storage::put('public/avatars/'. $filename, $imageData);
        // save the image in the public/avatars path


        $oldAvatar =  $user->avatar; 
        // get the old avatar before we change it

        $user->avatar = $filename;
        //update the avatar column in user model 
        $user->save();
        // save the changes to model

        if($oldAvatar != "/fallback-avatar.jpg"){
            // check if theres an old avatar that need to be replace
            // the old avatar path value will look like this: /storage/avatars/12345.jpg
            // so we need to turn it as /public/avatars/12345.jpg to remove the old avatar in public folder
            Storage::delete(str_replace("/storage/", "public/", $oldAvatar));
        }

        return back()->with("success", "successfully update avatar");

        // $request will get the file in input name avatar 
        // the image i will be save at the top level public folder and create folder avatars
        // CLI: php artisan storage:link It creates a symlink to your storage folder so that you can access it easier.
        // uses composer require intervention/image a package to resize the image
    }
}



//-----------------------------------------------------------------
   // public function profile(User $userData){
    //     //type hinting User $userData makes the $userData the instance of User class
    //     $currentlyFollowing=0;
    //     if( auth()->check()){
    //         $currentlyFollowing =  Follow::where([["user_id", "=" , auth()->user()->id], ["followeduser", "=", $userData->id ]])->count();
    //     }

    //     return view("profile-post", ["username" => $userData->username, 
    //                 "posts" => $userData->posts()->latest()->get(), 
    //                 "postCount" => $userData->posts()->count(), 
    //                 "avatar" => $userData->avatar,
    //                 "currentlyFollowing"=> $currentlyFollowing
    //             ]);
    //     // $userData->posts()->latest()->get() will get all the post and sorted in lastest order
    //     // $userData->posts()->count() will count all the post created by the user
    //     // $userData->avatar() get the profile picture of the owner of the post
    // }