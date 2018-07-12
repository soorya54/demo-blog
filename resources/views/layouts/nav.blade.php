<div class="blog-masthead">
  <div class="container">
    <nav class="nav blog-nav">
      <a class="nav-link active" href="/">Home</a>
      @if(Auth::check())
        @if(Auth::user()->type == 'admin')
          <a class="nav-link ml-auto" href="/admin">Admin</a>
          <a class="nav-link" href="/approve">Approve</a>
        @else
          <a class="nav-link ml-auto" href="#">{{Auth::user()->name}}</a>
        @endif
        <a class="nav-link" href="/posts/create">Create a Post</a>
        <a class="nav-link" href="/logout">Logout</a>   
      @else
        <a class="nav-link ml-auto" href="/login">Login</a>
        <a class="nav-link" href="/register">Register</a>         
      @endif
    </nav>
  </div>
</div>