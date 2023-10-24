<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public function showCreateForm(){
        return view("create-post");
    }

    public function storeNewPost(Request $request){
        $incomingData = $request->validate([
            "title" => "required",
            "body" => "required"
        ]);

        $incomingData["title"] =  strip_tags($incomingData["title"] );
        $incomingData["body"] =  strip_tags($incomingData["body"] );
        $incomingData["user_id"] = auth()->id();

        $newPost = Post::create( $incomingData );
        return redirect("/post/{$newPost->id}")->with("success" , "You have successfully create a new post");
    }

    public function viewSinglePost(Post $postId){
        $postId['body'] = Str::markdown($postId->body);
        return view("single-post", ["post" => $postId ]);

        // only allow certain html tags to be view
        // $postId['body'] = strip_tags(Str::markdown($postId->body), '<p><ul><li>...');
    }

    public function delete(Post $post){
        //check if the user cannot delete the post if true then prompt a message
        // if (auth()->user()->cannot('delete', $post)) {
        //     return 'You cannot do that';
        // }
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post successfully deleted.');
    }   

    public function showEditForm(Post $post){
        return view('edit-post', ['post' => $post]);
    }

    public function updatePost(Post $post, Request $request){
        $incomingData = $request->validate([
            "title" => "required",
            "body"=> "required"
        ]);

        $incomingData['title'] = strip_tags($incomingData['title'] );
        $incomingData['body'] = strip_tags($incomingData['body'] );


        $post->update($incomingData);

        // back() return to this page again 
        return back()->with('success','Post Successfully Updated');
    }


    public function search($term){

        $post  = Post::search($term)->get();
        $post->load('user:id,username,avatar'); // include the id username and avatar
        return $post;
        // use the search library build by laravel 
        // composer require laravel/scout
        // to use scout: php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
        // Post model i added Searchable and created a function called toSearchableArray
        // In .env file add this SCOUT_DRIVER=database
    }
}
