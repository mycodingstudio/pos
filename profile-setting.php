<?php
$pageTitle = "Profile Setting";
include_once 'views/header.php'; 
?>

<body>
    <?php include_once 'views/loading_spinner.php'; ?>
    <!-- <body data-layout="horizontal" data-topbar="colored"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- ========== TOP BAR ========== -->
        <?php include_once 'views/top-bar.php'; ?>
        <!-- ========== TOP BAR ========== -->

        <!-- ========== LEFT BAR ========== -->
        <?php include_once 'views/sidebar.php'; ?>
        <!-- ========== LEFT BAR ========== -->

        <!-- ============================================================= -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <?php include_once 'views/container_page_title.php'; ?>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-title p-4 border-bottom">
                                    <div class="d-lg-flex align-items-center">
                                        <div class="d-flex align-items-center mb-2 mb-lg-0">
                                            <div class="me-2"><i class="uil bi bi-gear font-size-24 text-primary"></i>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar-xs">
                                                        <div
                                                            class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                            <i class="uil-user  font-size-24"></i>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h5 class="font-size-16 mb-1">
                                                        Account Info & Setting
                                                    </h5>
                                                    <p class="text-muted text-truncate mb-0">Fill all information below
                                                    </p>
                                                </div>


                                            </div>

                                        </div>
                                        <!-- <div class="ms-auto"><a href="https://t.me/213123213" target="_blank"
                                                class="btn btn-info mb-2 mb-lg-0"><i class="uil uil-telegram-alt"></i>
                                                Support</a>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="p-3 pt-0">
                                                <div class="row">
                                                    <div class="col-md-4 col-12">
                                                        <h5 class="font-size-14 mb-1 text-dark">Login Username</h5>
                                                        <p class="mb-3 text-primary" id="profileUsername">-</p>
                                                    </div>

                                                    <div class="col-md-4 col-12">
                                                        <h5 class="font-size-14 mb-1 text-dark">Sales Code</h5>
                                                        <p class="mb-3 text-primary" id="salesCode">-</p>
                                                    </div>
                                                </div>
                                                <form class="needs-validation" novalidate>

                                                    <div class="row">

                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label" for="name">
                                                                    Name (For Rating Rank)</label>
                                                                <input id="name" name="name" type="text"
                                                                    class="form-control text-capitalize"
                                                                    placeholder="Fill in name" required
                                                                    autocomplete="off">
                                                                <div class="invalid-feedback">
                                                                    Please provide a valid name.
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label" for="phone">
                                                                    Phone (For All Day Support)</label>
                                                                <input id="phone" name="phone" type="tel"
                                                                    class="form-control" placeholder="Fill in phone"
                                                                    required autocomplete="off">
                                                                <div class="invalid-feedback">
                                                                    Please provide a valid phone.
                                                                </div>
                                                            </div>
                                                        </div>

                                                           <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label" for="email">
                                                                    Email (For Order Notification)</label>
                                                                <input id="email" name="email" type="email"
                                                                    class="form-control" placeholder="Fill in email"
                                                                    required autocomplete="off">
                                                                <div class="invalid-feedback">
                                                                    Please provide a valid email.
                                                                </div>
                                                            </div>
                                                        </div>
                                                        

                                                        
                                                        <div class="col-12">
                                                            <hr>
                                                            <p>Only fill in below when you want to change password</p>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="mb-3 position-relative"><label>Current
                                                                    Password</label>
                                                                <div><input name="profileCurrentPassword"
                                                                        id="profileCurrentPassword"
                                                                        placeholder="Current Password" type="password"
                                                                        class="form-control" 
                                                                        autocomplete="off">

                                                                    <div class="invalid-feedback">
                                                                        Please provide current password.
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="mb-3 position-relative"><label>New
                                                                    Password</label>
                                                                <div><input name="profileNewPassword"
                                                                        id="profileNewPassword"
                                                                        placeholder="New Password" type="password"
                                                                        class="form-control" 
                                                                        autocomplete="off">

                                                                    <div class="invalid-feedback">
                                                                        Please provide a new password.
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3 position-relative"><label>Confirm
                                                                    Password</label><input name="profileConfirmPassword"
                                                                    id="profileConfirmPassword"
                                                                    placeholder="Confirm Password" type="password"
                                                                    class="form-control"  autocomplete="off">

                                                                <div class="invalid-feedback"
                                                                    id="profileConfirmPasswordError">
                                                                    Confirm password must be same with new password.
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <p class="text-muted font-size-13"><i
                                                                class="uil uil-exclamation-triangle font-size-16 text-warning me-2"></i>
                                                            Password is Case Sensitive. </p>
                                                    </div>
                                                    <div class="mb-3 d-flex">

                                                        <button type="button"
                                                            class="btn btn-success waves-effect waves-light mb-3 float-end"
                                                            onclick="editProfileFunc(event)"><i
                                                                class="mdi mdi-shield-edit-outline me-1"></i>
                                                            Update
                                                            Now</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                </div> <!-- container-fluid -->
            </div>


            <!-- End Page-content -->

            <!-- FOOTER -->
            <?php include_once 'views/footer.php'; ?>
            <!-- FOOTER -->

        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->
    <?php include_once 'views/footer_libraries.php'; ?>

    <!-- plugins -->
    <script src="assets/libs/select2/js/select2.min.js"></script>
    <script src="assets/libs/spectrum-colorpicker2/spectrum.min.js"></script>
    <script src="assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
    <script src="assets/libs/@chenfengyuan/datepicker/datepicker.min.js"></script>

    <!-- datepicker js -->
    <script src="assets/libs/flatpickr/flatpickr.min.js"></script>

    <!-- init js -->
    <script src="assets/js/pages/form-advanced.init.js"></script>
    <!-- parsleyjs -->
    <script src="assets/libs/parsleyjs/parsley.min.js"></script>

    <script src="assets/js/pages/form-validation.init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

    <?php include_once 'js/admin/profileUpdateJs_encoded.php'; ?>

</body>

</html>