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
            @yield('content')
        </div>
        <footer class="bg-dark text-center text-white">
            @include('includes.footer')
        </footer>
        @stack('page-scripts')
    </body>
</html>