<x-layout doctitle="Editing Post: {{$post->title}}">
    <div class="container py-md-5 container--narrow">
        <form action="/post/{{$post->id}}" method="POST">
          <p><small><a href="/post/{{ $post->id }}">&laquo; Return to the post</a></small></p>
          @csrf
          @method('PUT')
          <div class="form-group">
            <label for="post-title" class="text-muted mb-1"><span>Title</span></label>
            <input required name="title" id="post-title" class="form-control form-control-lg form-control-title" type="text" placeholder="" autocomplete="off"  value="{{ old("title", $post->title) }}"/>
            @error("title")
                <p class="m-0 small alert alert-danger shadow-sm">{{ $message }}</p>
            @enderror
        </div>
  
          <div class="form-group">
            <label for="post-body" class="text-muted mb-1">
              <span>Body Content</span>
              <small>
                <a href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet" target="_blank">(this form uses markdown)</a>
              </small>
            </label>
            <textarea required name="body" id="post-body" class="body-content tall-textarea form-control" type="text">{{ old("body", $post->body)}}</textarea>
            @error("body")
                <p class="m-0 small alert alert-danger shadow-sm">{{ $message }}</p>
            @enderror
          </div>
  
          <button class="btn btn-primary">Save Changes</button>
        </form>
      </div>
</x-layout>