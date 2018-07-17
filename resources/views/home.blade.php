<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>Sample Blog</title>
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link href="/css/app.css" rel="stylesheet">
  </head>
  <body>
    <div class="blog-masthead">
        <div class="container">
            <nav class="nav blog-nav">
              <a class="nav-link ml-auto" href="#">Welcome {{Auth::user()->name}}</a>
            </nav>
        </div>
    </div>
    @if($flash=session('message'))
      <div id="flash-message" class="alert alert-success" role="alert">
        {{$flash}}
      </div>
    @endif
    <div class="blog-header">
      <div class="container">
        <h1 class="blog-title">Sample Blog</h1>
        <p class="lead blog-description">An example blog built with <em>Laravel</em>.</p>
      </div>
      
    </div>
    <div class="container">
      <div class="row">
            @yield('content')
      </div><!-- /.row -->
    </div><!-- /.container -->
    @include('layouts.footer')
  </body>
</html>