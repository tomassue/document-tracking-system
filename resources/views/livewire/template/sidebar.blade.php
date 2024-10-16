<!-- partial:./partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#profile-options" aria-expanded="false" aria-controls="profile-options">
                <img src="{{ asset('images/faces/user.png') }}" class="img-fluid mx-auto d-block" alt="profile-options" />
                <span class="menu-title">&nbsp &nbsp</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="profile-options">
                <ul class="nav flex-column sub-menu">
                    <p class="pt-3 text-white">{{ Auth::user()->name . ' (' . Auth::user()->ref_office->office_name . ')' }}</p>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('change-password') }}">
                            Change password
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item sidebar-category">
            <p>Navigation</p>
            <span></span>
        </li>

        @if (!Hash::check('password', Auth::user()->password))

        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="mdi mdi-view-quilt menu-icon"></i>
                <span class="menu-title">Dashboard</span>
                <!-- <div class="badge badge-info badge-pill">2</div> -->
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <i class="mdi mdi-folder-download menu-icon"></i>
                <span class="menu-title">Incoming</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('requests') }}">Requests</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('documents') }}">Documents</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('outgoing') }}">
                <i class="mdi mdi-folder-upload menu-icon"></i>
                <span class="menu-title">Outgoing</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('calendar') }}">
                <i class="mdi mdi mdi-calendar menu-icon"></i>
                <span class="menu-title">Calendar</span>
            </a>
        </li>

        <!-- /* -------------------------------------------------------------------------- */ -->
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#settings-submenus" aria-expanded="false" aria-controls="settings-submenus">
                <i class="mdi mdi-settings menu-icon"></i>
                <span class=" menu-title">Settings</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="settings-submenus">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ route('category') }}">Category</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('venues') }}">Venue</a></li>
                    @if(Auth::user()->role == '0')
                    <li class="nav-item"><a class="nav-link" href="{{ route('offices') }}">Offices</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('user-management') }}">User Management</a></li>
                    @endif
                </ul>
            </div>
        </li>
        <!-- /* -------------------------------------------------------------------------- */ -->

        @endif

    </ul>
</nav>
<!-- partial -->