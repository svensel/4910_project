
<!DOCTYPE html>
<html lang="en">

@include('layouts.generalCss')

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
@include('layouts.sidebar')

<div class="content-wrapper">
    <div class="container-fluid">
        @yield('content')
    </div>

    <footer class="sticky-footer">
        <div class="container">
            <div class="text-center">
                <small>Copyright © 4910_project 2017</small>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fa fa-angle-up"></i>
    </a>
    @include('layouts.logout')
    @include('layouts.generalScripts')
</div>
</body>

</html>
