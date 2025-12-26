<div class="dropdown d-inline-block">
    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <!-- <img class="rounded-circle header-profile-user" src="assets/images/users/avatar-1.jpg" alt="Header Avatar"> -->
        <span class="d-none d-xl-inline-block ms-1 fw-medium font-size-15" id="topBarProfileName">-</span>
        <i class="uil-angle-down d-none d-xl-inline-block font-size-15"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <!-- item-->
        <a class="dropdown-item d-block" href="profile-setting"><i class="uil uil-cog font-size-18 align-middle me-1 text-muted"></i>
            <span class="align-middle">Settings</span></a>
        <span class="dropdown-item clickEleStyle" onclick="signOutFunc()"><i class="uil uil-sign-out-alt font-size-18 align-middle me-1 text-muted"></i>
            <span class="align-middle">Sign out</span></span>
    </div>
</div>