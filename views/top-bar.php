<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="index" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="assets/images/logo-sm.png" alt="" height="45">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo-dark.png" alt="" height="45">
                    </span>
                </a>

                <a href="index" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="assets/images/logo-sm.png" alt="" height="45">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo-light.png" alt="" height="45">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>


        </div>

        <div class="d-flex">


            <!-- LANGUAGE DROPDOWN -->
            <?php //include_once 'views/topbar/language.php'; ?>
            <!-- LANGUAGE DROPDOWN -->

            <!-- NOTIFICATION LIST -->
            <?php include_once 'views/topbar/notification.php'; ?>
            <!-- NOTIFICATION LIST -->

            <!-- PROFILE LIST -->
            <?php include_once 'views/topbar/profile.php'; ?>
            <!-- PROFILE LIST -->


        </div>
    </div>
</header>