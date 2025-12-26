<?php
// index.php
$pageTitle = "KPI Multi-Step Login Demo";
// Keeping includes as they likely contain your CSS/JS dependencies
// NOTE: I am commenting out the includes for demonstration, but you should keep them in your environment.
// include_once 'views/header.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <!-- Assuming your environment loads necessary CSS like Bootstrap/Tailwind for the card/form structure -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f4f6f9; }
        .authentication-bg { min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .text-primary { color: #00897B !important; }
        /* ENSURE ALL PRIMARY BUTTONS ARE #00897B */
        .btn-primary { background-color: #00897B; border-color: #00897B; }
        .btn-primary:hover { background-color: #00695C; border-color: #00695C; }
        /* CSS Fix for layout width and centering */
        .account-pages .col-xl-5 {
             max-width: 450px; /* Constrain max width for better centering */
             width: 90%; /* Responsive width */
        }
        .border-radius-20 { border-radius: 20px !important; }
    </style>
</head>

<body class="authentication-bg">

    <!-- <?php // include_once 'views/loading_spinner.php'; ?> -->

    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card border-radius-20">

                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Welcome to HealthFirst Portal</h5>
                                <p class="text-muted">Sign in to manage your system.</p>
                            </div>

                            <!-- Step 1: Username & Password Login -->
                            <div class="p-2 mt-4" id="login-step-1">
                                <form id="login-form">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" placeholder="Enter username" required value="admin">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" placeholder="Enter password" required value="12345">
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="customCheck1" checked>
                                                <label class="form-check-label" for="customCheck1">Remember me</label>
                                            </div>
                                        </div>
                                        <div class="col-6 text-end">
                                            <a href="javascript:void(0);" onclick="forgotPassFunc()" class="text-muted"><i class="mdi mdi-lock me-1"></i>Forgot your password?</a>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <button class="btn btn-primary w-100" type="submit" id="login-submit-btn">
                                            Log In
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- NOTE: Step 2 HTML is now used as content for the SweetAlert Modal -->
                            <div id="step-2-content" style="display: none;">
                                <div class="mb-3">
                                    <label for="company-select-modal" class="form-label">Select Company</label>
                                    <select class="form-select" id="company-select-modal" required>
                                        <option value="">-- Choose Company --</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="branch-select-modal" class="form-label">Select Branch</label>
                                    <select class="form-select" id="branch-select-modal" required disabled>
                                        <option value="">-- Choose Branch --</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end account-pages -->

    <script>
        // Placeholder for local storage helpers - assuming they exist or using standard localStorage
        function saveToLocalEncrypted(key, value) {
            localStorage.setItem(key, value);
        }

        // UPDATED: Added confirmButtonColor to ensure consistency with #00897B
        function showDialogMessage(title, text, icon, showConfirm, redirect = null) {
             Swal.fire({
                 title: title,
                 text: text,
                 icon: icon,
                 showConfirmButton: showConfirm,
                 confirmButtonColor: '#00897B' // Set confirm button to brand color
             })
                .then(() => { if (redirect) window.location.href = redirect + '.html'; });
        }

        function showDialogMessageWithTimer(title, text, icon, showConfirm, redirect = null) {
             Swal.fire({ title: title, text: text, icon: icon, timer: 1500, showConfirmButton: false })
                .then(() => { if (redirect) window.location.href = redirect + ''; });
        }

        // --- Simulated Data and State ---
        const MOCK_CREDENTIALS = {
            'admin': '12345',
            'manager': 'password'
        };

        // Mock data for Company selection
        const MOCK_COMPANIES = [
            { id: 1, name: 'HealthFirst HQ', branches: [101, 102] },
            { id: 2, name: 'Wellness Group', branches: [201, 202, 203] },
            { id: 3, name: 'Metro Labs', branches: [301] } // Added a third company
        ];

        // Mock data for Branch selection (keyed by branch ID)
        const MOCK_BRANCHES = {
            101: { id: 101, name: 'Main City Branch (HQ)' },
            102: { id: 102, name: 'Suburban Outlet' },
            201: { id: 201, name: 'North Mall Pharmacy' },
            202: { id: 202, name: 'South End Clinic' },
            203: { id: 203, name: 'Downtown Express' },
            301: { id: 301, name: 'R&D Center' } // Added a branch for Metro Labs
        };

        let activeUser = null;

        // --- Event Listeners ---
        $(document).ready(function () {
            $('#login-form').on('submit', function (e) {
                e.preventDefault();
                loginFunc();
            });

            // Global listener for the modal's company change event
            $(document).on('change', '#company-select-modal', function() {
                selectCompanyFunc($(this).val(), true); // Pass true to indicate modal context
            });
        });


        // --- Step 1 Logic: Credential Check ---
        function loginFunc() {
            const username = $('#username').val();
            const password = $('#password').val();
            const submitBtn = $('#login-submit-btn');
            const originalText = submitBtn.text();

            submitBtn.prop('disabled', true).text('Verifying...');

            // --- Simulated AJAX/Server Call for Credential Check ---
            setTimeout(function () {
                let success = false;
                let message = "Invalid username or password. Please try again.";

                if (MOCK_CREDENTIALS[username] === password) {
                    success = true;
                    message = "Credentials verified. Please select your Company and Branch.";
                    activeUser = username; // Store user details temporarily
                }

                if (success) {
                    // Successful Step 1: Transition to Step 2 (Modal)
                    submitBtn.prop('disabled', false).text(originalText); // Reset button before modal shows
                    selectContextModal();

                } else {
                    // Uses the updated helper function with brand color for the confirm button
                    showDialogMessage("Login Alert", message, "error", true);
                    submitBtn.prop('disabled', false).text(originalText);
                }

            }, 1000); // 1 second simulated delay
        }

        function forgotPassFunc() {
            Swal.fire({
                title: "Demo Mode",
                text: "Password reset is disabled in demo mode.",
                icon: "info",
                confirmButtonColor: '#00897B' // Set confirm button to brand color
            });
        }

        // --- Step 2 Logic: Company/Branch Selection (Modal based) ---

        function selectContextModal() {
             Swal.fire({
                title: 'Select Access Context',
                html: $('#step-2-content').html(), // Inject hidden HTML content
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Access Dashboard',
                cancelButtonText: 'Cancel',
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonColor: '#00897B', // Set confirm button to brand color
                didOpen: () => {
                    // 1. Initialize Company Select inside the modal
                    // By passing true (isModal), we ensure the selector is scoped correctly.
                    populateCompanySelect(true);

                    // 2. Initial state: disable Confirm button
                    const confirmButton = Swal.getConfirmButton();
                    $(confirmButton).prop('disabled', true);

                    // 3. Listener for branch change to enable/disable button
                    const context = Swal.getHtmlContainer();
                    $(context).on('change', '#branch-select-modal', function() {
                        const isBranchSelected = $(this).val() !== '';
                        $(confirmButton).prop('disabled', !isBranchSelected);
                    });
                },
                preConfirm: () => {
                    // CRITICAL FIX: Ensure we read the values directly from the elements
                    // within the current SweetAlert container.
                    const context = Swal.getHtmlContainer();
                    const companyId = $('#company-select-modal', context).val();
                    const branchId = $('#branch-select-modal', context).val();

                    if (!companyId || !branchId) {
                        Swal.showValidationMessage('Please select both a Company and a Branch.');
                        return false;
                    }
                    return { companyId, branchId };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    finalizeLoginFunc(result.value.companyId, result.value.branchId);
                }
            });
        }

        function populateCompanySelect(isModal) {
            const selectId = isModal ? '#company-select-modal' : '#company-select';
            // Scope the lookup to the active SweetAlert content for modal context
            const context = isModal ? Swal.getHtmlContainer() : document;
            const companySelect = $(selectId, context);

            companySelect.empty().append('<option value="">-- Choose Company --</option>');

            // Populate options using MOCK_COMPANIES data
            MOCK_COMPANIES.forEach(company => {
                companySelect.append(`<option value="${company.id}">${company.name}</option>`);
            });
        }

        function selectCompanyFunc(companyId, isModal) {
            const branchSelectId = isModal ? '#branch-select-modal' : '#branch-select';
            // Scope the lookup to the active SweetAlert content for modal context
            const context = isModal ? Swal.getHtmlContainer() : document;
            const branchSelect = $(branchSelectId, context);
            // In the modal, the confirm button is obtained via Swal.getConfirmButton()
            const confirmButton = isModal ? Swal.getConfirmButton() : $('#finalize-login-btn');

            branchSelect.prop('disabled', true).empty().append('<option value="">-- Loading Branches --</option>');
            $(confirmButton).prop('disabled', true);

            if (!companyId) {
                branchSelect.prop('disabled', true).empty().append('<option value="">-- Choose Branch --</option>');
                return;
            }

            // Simulate fetching branches based on companyId
            setTimeout(() => {
                branchSelect.empty().append('<option value="">-- Choose Branch --</option>');
                // Find the selected company's branch IDs from the mock data
                const selectedCompany = MOCK_COMPANIES.find(c => c.id == companyId);

                if (selectedCompany && selectedCompany.branches.length > 0) {
                    // Populate branch options using MOCK_BRANCHES data
                    selectedCompany.branches.forEach(branchId => {
                        const branch = MOCK_BRANCHES[branchId];
                        if (branch) {
                            branchSelect.append(`<option value="${branch.id}">${branch.name}</option>`);
                        }
                    });
                    branchSelect.prop('disabled', false);
                } else {
                    branchSelect.append('<option value="">No branches found for this company.</option>');
                }

                // Trigger change event to re-evaluate branch selection
                branchSelect.trigger('change');

            }, 500); // 0.5 second simulated delay
        }

        function finalizeLoginFunc(companyId, branchId) {
            const username = $('#username').val(); // Use the username from Step 1

            Swal.showLoading(); // Show loading state on the modal/screen

            // --- Simulated AJAX/Server Call for Final Login/Session Setup ---
            setTimeout(() => {
                const isRememberCheckStr = $('#customCheck1').is(':checked') ? "true" : "false";

                // Save user, company, and branch context to local storage
                saveToLocalEncrypted('username', username);
                saveToLocalEncrypted('access_token', 'mock_token_' + username);
                saveToLocalEncrypted('has_login', "true");
                saveToLocalEncrypted('is_remember', isRememberCheckStr);
                saveToLocalEncrypted('company_id', companyId);
                saveToLocalEncrypted('branch_id', branchId);

                const selectedCompany = MOCK_COMPANIES.find(c => c.id == companyId).name;
                const selectedBranch = MOCK_BRANCHES[branchId].name;

                const message = `Welcome ${username}! Logged into ${selectedBranch} (${selectedCompany}).`;

                // Close the current selection modal and show success message
                Swal.close();
                showDialogMessageWithTimer("Login Successful", message, "success", false, 'dashboard');

            }, 1000); // 1 second simulated delay
        }

    </script>
</body>

</html>