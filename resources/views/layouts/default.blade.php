<!DOCTYPE html>
<html>
    <head>
        @include('includes.head')
        @stack('css')
    </head>
    <body>
        <header class="row">
            @include('includes.header')
        </header>
        <div class="container my-5">
            @include('includes.backToTopButton')
            @yield('content')
        </div>
        <footer class="row bg-dark text-center text-white">
            @include('includes.footer')
        </footer>
        @stack('page-scripts')
    </body>
</html>