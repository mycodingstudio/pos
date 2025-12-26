<?php
$pageTitle = "System Setup & Configuration";
// Assumes 'views/header.php' includes necessary dependencies like Bootstrap/CSS, jQuery, and SweetAlert2 (Swal)
include_once 'views/header.php';

// --- MOCK DATA FOR DEMONSTRATION ---
$mock_settings = [
    "company_name" => "All Day POS Sdn Bhd",
    "address" => "Lot 18, Jalan Teknologi 3/4, Kota Damansara,\n47810 Petaling Jaya, Selangor, Malaysia",
    "website" => "https://www.alldaypos.com",
    "currency_symbol" => "RM",
    "currency_code" => "MYR",
    "time_zone" => "Asia/Kuala_Lumpur",
    "logo_url" => "assets/images/logo-dark.png" // Simulated existing logo
];

?>

<body>
    <?php include_once 'views/loading_spinner.php'; // Assume a spinner component ?>

    <div id="layout-wrapper">
        <?php include_once 'views/top-bar.php'; // Assume top navigation ?>
        <?php include_once 'views/sidebar.php'; // Assume sidebar navigation ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 font-size-18">System Setup</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Main</a></li>
                                        <li class="breadcrumb-item active">Setup</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">

                                    <ul class="nav nav-pills nav-justified bg-light mb-4" id="pills-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="company-tab" data-bs-toggle="pill"
                                                href="#company-info" role="tab" aria-controls="company-info"
                                                aria-selected="true">
                                                <i class="uil-building font-size-18 me-1"></i> Company Information
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="currency-tab" data-bs-toggle="pill"
                                                href="#currency-settings" role="tab" aria-controls="currency-settings"
                                                aria-selected="false">
                                                <i class="uil-usd-circle font-size-18 me-1"></i> Currency Settings
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="pos-tab" data-bs-toggle="pill"
                                                href="#pos-settings" role="tab" aria-controls="pos-settings"
                                                aria-selected="false">
                                                <i class="uil-desktop font-size-18 me-1"></i> General POS Settings
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="pills-tabContent">

                                        <div class="tab-pane fade show active" id="company-info" role="tabpanel"
                                            aria-labelledby="company-tab">
                                            <form id="company-form" onsubmit="saveCompanyInfo(event)">
                                                <h5 class="text-muted mb-4">Update Business Details</h5>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="company_name" class="form-label">Company
                                                            Name</label>
                                                        <input type="text" class="form-control" id="company_name"
                                                            value="<?= $mock_settings['company_name'] ?>" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="website" class="form-label">Website URL</label>
                                                        <input type="url" class="form-control" id="website"
                                                            value="<?= $mock_settings['website'] ?>"
                                                            placeholder="e.g., https://www.yourcompany.com">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="address" class="form-label">Address</label>
                                                    <textarea class="form-control" id="address" rows="4"
                                                        required><?= trim($mock_settings['address']) ?></textarea>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-4">
                                                        <label for="logo_file" class="form-label">Company Logo
                                                            (Current)</label>
                                                        <div class="input-group">
                                                            <input type="file" class="form-control" id="logo_file"
                                                                accept="image/*">
                                                            <button class="btn btn-outline-secondary" type="button"
                                                                onclick="viewCurrentLogo()">View</button>
                                                        </div>
                                                        <small class="text-muted mt-2 d-block">Upload a new logo to
                                                            replace the current one. Max size: 2MB.</small>
                                                    </div>
                                                </div>

                                                <button type="submit"
                                                    class="btn btn-primary waves-effect waves-light">Save Company
                                                    Info</button>
                                            </form>
                                        </div>

                                        <div class="tab-pane fade" id="currency-settings" role="tabpanel"
                                            aria-labelledby="currency-tab">
                                            <form id="currency-form" onsubmit="saveCurrencySettings(event)">
                                                <h5 class="text-muted mb-4">Define Currency and Timezone</h5>

                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="currency_symbol" class="form-label">Currency
                                                            Symbol</label>
                                                        <input type="text" class="form-control" id="currency_symbol"
                                                            value="<?= $mock_settings['currency_symbol'] ?>"
                                                            placeholder="e.g., RM" required>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="currency_code" class="form-label">Currency
                                                            Code</label>
                                                        <input type="text" class="form-control" id="currency_code"
                                                            value="<?= $mock_settings['currency_code'] ?>"
                                                            placeholder="e.g., MYR" maxlength="3" required>
                                                        <small class="text-muted">3-letter ISO code (e.g., USD,
                                                            MYR).</small>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="time_zone" class="form-label">Time Zone</label>
                                                        <select class="form-select" id="time_zone" required>
                                                            <option value="Asia/Kuala_Lumpur"
                                                                <?= $mock_settings['time_zone'] == 'Asia/Kuala_Lumpur' ? 'selected' : '' ?>>
                                                                (UTC+08:00) Kuala Lumpur</option>
                                                            <option value="Asia/Singapore"
                                                                <?= $mock_settings['time_zone'] == 'Asia/Singapore' ? 'selected' : '' ?>>
                                                                (UTC+08:00) Singapore</option>
                                                            <option value="America/New_York"> (UTC-05:00) New York
                                                            </option>
                                                            <option value="Europe/London"> (UTC+00:00) London</option>
                                                            <option value="Asia/Tokyo"> (UTC+09:00) Tokyo</option>
                                                            <option value="UTC"> (UTC+00:00) Coordinated Universal Time
                                                                (UTC)</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <button type="submit"
                                                    class="btn btn-primary waves-effect waves-light mt-3">Save Currency
                                                    Settings</button>
                                            </form>
                                        </div>
                                        
                                        <div class="tab-pane fade" id="pos-settings" role="tabpanel"
                                            aria-labelledby="pos-tab">
                                            <form id="pos-config-form" onsubmit="savePosSettings(event)">
                                                <h5 class="text-muted mb-4">Point of Sale (POS) General Configuration</h5>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="default_discount_template" class="form-label">Default Discount Template</label>
                                                        <select class="form-select" id="default_discount_template" required>
                                                            <option value="None">None (No automatic discount)</option>
                                                            <option value="Standard" selected>Standard Loyalty Discount (10%)</option>
                                                            <option value="Promo">Seasonal Promotion Discount</option>
                                                            <option value="Staff">Staff Discount (15%)</option>
                                                        </select>
                                                        <small class="text-muted">Used to create discount templates for POS.</small>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="cashier_access_level" class="form-label">Cashier Default Access Level</label>
                                                        <select class="form-select" id="cashier_access_level" required>
                                                            <option value="Full">Full Access (Allows voids, returns)</option>
                                                            <option value="Standard" selected>Standard (Supervisor needed for voids/returns)</option>
                                                            <option value="Limited">Limited (Sales only)</option>
                                                        </select>
                                                        <small class="text-muted">Setup Cashier Access Right define rights for cashiers.</small>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="weighing_machine" class="form-label">Weighing Machine Linkage</label>
                                                        <select class="form-select" id="weighing_machine" required>
                                                            <option value="Disabled" selected>Disabled</option>
                                                            <option value="SerialPort">Serial Port (COM1)</option>
                                                            <option value="Network">Network (IP: 192.168.1.x)</option>
                                                        </select>
                                                        <small class="text-muted">Connect to scales for weighted items.</small>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="item_pictures" class="form-label">Item Pictures on POS Screen</label>
                                                        <select class="form-select" id="item_pictures" required>
                                                            <option value="Enabled" selected>Enabled</option>
                                                            <option value="Disabled">Disabled</option>
                                                        </select>
                                                        <small class="text-muted">Display product images in POS interface.</small>
                                                    </div>
                                                </div>

                                                <h5 class="text-muted mb-3 mt-4">Related POS Settings & Management</h5>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn btn-outline-info waves-effect" onclick="manageVouchers()">
                                                        <i class="uil-tag-alt me-1"></i> Manage Vouchers
                                                    </button>
                                                    <button type="button" class="btn btn-outline-warning waves-effect" onclick="setupCashierRights()">
                                                        <i class="uil-lock me-1"></i> Advanced Access Rights
                                                    </button>
                                                </div>


                                                <button type="submit"
                                                    class="btn btn-primary waves-effect waves-light mt-4">Save POS Settings</button>
                                            </form>
                                        </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    </div> </div>
            <?php include_once 'views/footer.php'; ?>
        </div>
        </div>
    <?php include_once 'views/footer_libraries.php'; ?>
    <script src="assets/js/app.js"></script>
    <script>
    // --- System Setup JavaScript Logic ---

    function saveCompanyInfo(event) {
        event.preventDefault();
        const companyName = document.getElementById('company_name').value;
        const address = document.getElementById('address').value;
        const website = document.getElementById('website').value;

        // Simulating Logo Upload (check if a file was selected)
        const logoFile = document.getElementById('logo_file').files[0];
        const logoMessage = logoFile ? ` and new logo <b>${logoFile.name}</b>` : '';

        Swal.fire({
            title: 'Confirm Save',
            html: `Are you sure you want to update company info to <b>${companyName}</b>${logoMessage}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Save it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // In a real application, AJAX call to update database here
                console.log('Saving Company Info:', {
                    companyName,
                    address,
                    website,
                    logoFile: logoFile ? logoFile.name : 'none'
                });
                document.getElementById('logo_file').value = null; // Clear file input after "upload"

                Swal.fire(
                    'Saved!',
                    'Company information has been updated successfully.',
                    'success'
                );
            }
        });
    }

    function saveCurrencySettings(event) {
        event.preventDefault();
        const symbol = document.getElementById('currency_symbol').value;
        const code = document.getElementById('currency_code').value;
        const timezone = document.getElementById('time_zone').value;

        Swal.fire({
            title: 'Confirm Save',
            html: `Set base currency to <b>${symbol} (${code})</b> and timezone to <b>${timezone}</b>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Save it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // In a real application, AJAX call to update database here
                console.log('Saving Currency Settings:', {
                    symbol,
                    code,
                    timezone
                });

                Swal.fire(
                    'Saved!',
                    'Currency and Timezone settings have been updated.',
                    'success'
                );
            }
        });
    }

    function savePosSettings(event) {
        event.preventDefault();
        const discountTemplate = document.getElementById('default_discount_template').value;
        const accessLevel = document.getElementById('cashier_access_level').value;
        const weighing = document.getElementById('weighing_machine').value;
        const pictures = document.getElementById('item_pictures').value;

        Swal.fire({
            title: 'Confirm Save',
            html: `Update POS settings:<br>- Discount: <b>${discountTemplate}</b><br>- Access: <b>${accessLevel}</b>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Save it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // In a real application, AJAX call to update database here
                console.log('Saving POS Settings:', {
                    discountTemplate,
                    accessLevel,
                    weighing,
                    pictures
                });

                Swal.fire(
                    'Saved!',
                    'General POS settings have been updated successfully.',
                    'success'
                );
            }
        });
    }

    function manageVouchers() {
        alert('Simulated: Redirecting to Voucher Management page (e.g., voucher-setup.php).');
    }
    
    function setupCashierRights() {
        alert('Simulated: Redirecting to detailed Cashier/User Access Rights configuration page.');
    }

    function viewCurrentLogo() {
        Swal.fire({
            title: 'Current Company Logo',
            // Assuming 'assets/images/logo-dark.png' is the correct mock path
            imageUrl: '<?= $mock_settings['logo_url'] ?>',
            imageWidth: 200,
            imageAlt: 'Current Logo',
            text: 'This is the logo currently used on receipts and reports.',
            confirmButtonText: 'Close'
        });
    }
    </script>
</body>

</html>