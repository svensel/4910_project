<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="">Scheduling Application</a>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                <a class="nav-link" href="">
                    <i class="fa fa-fw fa-dashboard"></i>
                    <span class="nav-link-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="fa fa-fw fa-book"></i>
                    <span class="nav-link-text">Courses</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('groups')}}">
                    <i class="fa fa-fw fa-group"></i>
                    <span class="nav-link-text">Groups</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="fa fa-fw fa-calendar"></i>
                    <span class="nav-link-text">Calendar</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="fa fa-fw fa-inbox"></i>
                    <span class="nav-link-text">Inbox</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="fa fa-fw fa-question"></i>
                    <span class="nav-link-text">Help</span>
                </a>
            </li>
        </ul>
        @include('layouts.topbar')
    </div>
</nav>