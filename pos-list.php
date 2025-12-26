<?php
$pageTitle = "POS Counter Monitoring Dashboard";
include_once 'views/header.php'; // Assumes this includes Bootstrap/CSS dependencies

// --- MOCK DATA FOR POS COUNTERS ---
// This data simulates real-time status fetching from the POS terminals.
$posCounters = [
    [
        "id" => 1,
        "name" => "Counter 1 (Prescription)",
        "status" => "online",
        "transactions_today" => 45,
        "staff_on_duty" => "Ali Bin Abu (101)",
        "last_transaction_time" => "11:05 AM",
        "last_sync_time" => "11:13 AM",
        "cash_in_drawer" => "548.50",
    ],
    [
        "id" => 2,
        "name" => "Counter 2 (OTC/Express)",
        "status" => "online",
        "transactions_today" => 120,
        "staff_on_duty" => "Ah Meng (103)",
        "last_transaction_time" => "11:10 AM",
        "last_sync_time" => "11:14 AM",
        "cash_in_drawer" => "1,250.00",
    ],
    [
        "id" => 3,
        "name" => "Counter 3 (Supervisor)",
        "status" => "offline",
        "transactions_today" => 5,
        "staff_on_duty" => "Siti Aminah (102)",
        "last_transaction_time" => "09:30 AM",
        "last_sync_time" => "09:35 AM",
        "cash_in_drawer" => "45.00",
    ],
];

// MOCK DATA for Dropdowns (based on available system config/staff)
$mockStaff = ["None", "Ali Bin Abu (101)", "Siti Aminah (102)", "Ah Meng (103)", "Devi A/P Mohan (106)"];
$mockTypes = ["Standard Retail POS", "Express Checkout", "Pharmacy Counter (Controlled)", "Kiosk"];

// Helper to determine status badge color and icon
function getStatusDisplay($status) {
    if ($status === 'online') {
        // Use a brighter success badge
        return '<span class="badge bg-success font-size-14 p-2 fw-bold"><i class="mdi mdi-check-circle me-1"></i> OPERATIONAL</span>';
    } else {
        // Use a high-contrast danger badge
        return '<span class="badge bg-danger font-size-14 p-2 fw-bold"><i class="mdi mdi-alert-circle me-1"></i> OFFLINE</span>';
    }
}
?>

<body>
    <?php include_once 'views/loading_spinner.php'; ?>

    <div id="layout-wrapper">
        <?php include_once 'views/top-bar.php'; ?>
        <?php include_once 'views/sidebar.php'; ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <?php include_once 'views/container_page_title.php'; ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title text-dark">Real-Time POS Status</h4>
                                <div>
                                    <button class="btn btn-sm btn-primary waves-effect waves-light shadow-sm me-2"
                                            data-bs-toggle="modal" data-bs-target="#addPosModal">
                                        <i class="mdi mdi-plus me-1"></i> Add New POS Terminal
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary waves-effect waves-light shadow-sm"
                                        onclick="window.location.reload();">
                                        <i class="mdi mdi-refresh me-1"></i> Refresh Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php foreach ($posCounters as $counter): 
                            $statusColor = $counter['status'] === 'online' ? 'border-success' : 'border-danger';
                            $titleColor = $counter['status'] === 'online' ? 'text-success' : 'text-danger';
                            $cardBg = $counter['status'] === 'offline' ? 'bg-warning-light' : 'bg-white';
                        ?>
                        <div class="col-md-6 col-xl-4">
                            <div
                                class="card <?php echo $cardBg; ?> border-top-0 border-end-0 border-bottom-0 border-5 <?php echo $statusColor; ?> rounded-lg shadow-lg hover-shadow-xl transition-all duration-300">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="<?php echo $titleColor; ?> mb-0 fw-bold">
                                            <?php echo htmlspecialchars($counter['name']); ?></h5>
                                        <?php echo getStatusDisplay($counter['status']); ?>
                                    </div>
                                    <hr class="my-3 border-gray-200">

                                    <div class="row text-center">
                                        <div class="col-6 border-end">
                                            <h6 class="text-muted font-size-14 mb-1">STAFF ON DUTY</h6>
                                            <p class="text-dark fw-bold mb-0 text-truncate font-size-16"
                                                title="<?php echo htmlspecialchars($counter['staff_on_duty']); ?>">
                                                <?php echo htmlspecialchars($counter['staff_on_duty']); ?>
                                            </p>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="text-muted font-size-14 mb-1">TODAY'S TRANSACTIONS</h6>
                                            <p class="text-primary fw-bolder mb-0 display-6">
                                                <?php echo number_format($counter['transactions_today']); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <hr class="my-3 border-gray-200">

                                    <div class="mt-4">
                                        <div
                                            class="d-flex justify-content-between align-items-center mb-2 p-1 bg-light rounded">
                                            <p class="text-muted mb-0 font-size-14">Last Transaction:</p>
                                            <span
                                                class="text-dark fw-bold"><?php echo htmlspecialchars($counter['last_transaction_time']); ?></span>
                                        </div>
                                        <div
                                            class="d-flex justify-content-between align-items-center mb-2 p-1 bg-light rounded">
                                            <p class="text-muted mb-0 font-size-14">Last Data Sync:</p>
                                            <span
                                                class="text-info fw-bold"><?php echo htmlspecialchars($counter['last_sync_time']); ?></span>
                                        </div>
                                        <div
                                            class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top border-gray-300">
                                            <p class="text-muted mb-0 font-size-16 fw-semibold">EST. CASH IN DRAWER:</p>
                                            <span class="text-success font-size-20 fw-bolder">RM
                                                <?php echo htmlspecialchars($counter['cash_in_drawer']); ?></span>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-2 border-top">
                                        <?php if ($counter['status'] === 'offline'): ?>
                                        <button class="btn btn-sm btn-danger w-100 shadow-sm"
                                            onclick="alertAction(<?php echo $counter['id']; ?>, '<?php echo htmlspecialchars($counter['name']); ?>', 'troubleshoot')">
                                            <i class="mdi mdi-alert-octagon me-1"></i> URGENT: Troubleshoot Connection
                                        </button>
                                        <?php else: ?>
                                        <button class="btn btn-sm btn-outline-primary w-100 shadow-sm"
                                            onclick="alertAction(<?php echo $counter['id']; ?>, '<?php echo htmlspecialchars($counter['name']); ?>', 'check')">
                                            <i class="mdi mdi-file-document-box-outline me-1"></i> Access Detailed Log
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>

                        <div class="col-md-6 col-xl-4">
                            <div
                                class="card bg-light border-dashed h-100 d-flex align-items-center justify-content-center border-2 border-gray-300">
                                <div class="card-body text-center">
                                    <i class="mdi mdi-settings display-4 text-muted mb-3"></i>
                                    <h5 class="text-dark">Operational Suggestions:</h5>
                                    <ul class="list-unstyled text-start text-dark fw-normal mt-3">
                                        <li><i class="mdi mdi-cart-outline me-2 text-primary"></i> Pending Online Orders
                                        </li>
                                        <li><i class="mdi mdi-printer-alert-outline me-2 text-warning"></i> Hardware
                                            Health Check</li>
                                        <li><i class="mdi mdi-currency-usd me-2 text-success"></i> Average Transaction
                                            Value</li>
                                        <li><i class="mdi mdi-update me-2 text-info"></i> Last Stock Update Time</li>
                                    </ul>
                                    <button class="btn btn-sm btn-soft-secondary mt-3 shadow-sm" 
                                        data-bs-toggle="modal" data-bs-target="#addPosModal">Configure New Counter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
            </div>

            <?php include_once 'views/footer.php'; ?>
        </div>
    </div>
    
    <div class="modal fade" id="addPosModal" tabindex="-1" aria-labelledby="addPosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPosModalLabel"><i class="mdi mdi-desktop-tower me-2"></i> Configure New POS Terminal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="add-pos-form" onsubmit="saveNewPos(event)">
                    <div class="modal-body">
                        <p class="text-muted">Set up the terminal identification and its core operational parameters.</p>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pos-name" class="form-label">Terminal Name / Location</label>
                                <input type="text" class="form-control" id="pos-name" placeholder="e.g., Counter 4 (New)" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pos-id" class="form-label">System ID (Short Code)</label>
                                <input type="text" class="form-control" id="pos-id" placeholder="e.g., C4" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pos-type" class="form-label">Terminal Type</label>
                                <select class="form-select" id="pos-type" required>
                                    <?php foreach ($mockTypes as $type): ?>
                                        <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="default-cashier" class="form-label">Default/Supervisor Cashier</label>
                                <select class="form-select" id="default-cashier">
                                    <?php foreach ($mockStaff as $staff): ?>
                                        <option value="<?php echo $staff; ?>"><?php echo $staff; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="weighing-linkage" class="form-label">Weighing Machine Linkage</label>
                                <select class="form-select" id="weighing-linkage">
                                    <option value="Disabled" selected>Disabled</option>
                                    <option value="SerialPort">Serial Port (COM1)</option>
                                    <option value="Network">Network IP Link</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="offline-sync" class="form-label">Offline POS Capability</label>
                                <select class="form-select" id="offline-sync">
                                    <option value="Enabled" selected>Enabled (Operate offline with sync)</option>
                                    <option value="Disabled">Disabled (Online only)</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" id="barcode-printing" checked>
                            <label class="form-check-label" for="barcode-printing">Enable Barcode Label Printing from this POS</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save and Initialize POS</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include_once 'views/footer_libraries.php'; ?>
    <script src="assets/js/app.js"></script>
    <script>
    function alertAction(id, name, type) {
        let message = '';
        let title = '';
        let icon = '';

        if (type === 'troubleshoot') {
            title = `Troubleshoot ${name}`;
            message =
                `Attempting to automatically ping and check the connection for ${name}. If issue persists, check network cables and application status.`;
            icon = 'info';
        } else if (type === 'check') {
            title = `View Detailed Log for ${name}`;
            message =
                `This would redirect you to a detailed activity log and cash reconciliation report for ${name}. (Demo action)`;
            icon = 'success';
        }

        Swal.fire({
            title: title,
            text: message,
            icon: icon,
            confirmButtonColor: '#556ee6'
        });
    }
    
    function saveNewPos(event) {
        event.preventDefault();
        
        // Collect data from the modal form
        const posName = document.getElementById('pos-name').value;
        const posId = document.getElementById('pos-id').value;
        const posType = document.getElementById('pos-type').value;
        const defaultCashier = document.getElementById('default-cashier').value;
        const weighing = document.getElementById('weighing-linkage').value;
        const offline = document.getElementById('offline-sync').value;
        const barcodePrint = document.getElementById('barcode-printing').checked ? 'Enabled' : 'Disabled';
        
        // Log for demonstration
        console.log('New POS Config:', {posName, posId, posType, defaultCashier, weighing, offline, barcodePrint});

        // Close the modal
        const modalElement = document.getElementById('addPosModal');
        const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        modal.hide();

        // Show success alert
        Swal.fire({
            title: 'POS Initialized!',
            html: `New POS Terminal <b>${posName} (${posId})</b> has been configured and is ready for staff assignment.`,
            icon: 'success',
            confirmButtonColor: '#3085d6'
        }).then(() => {
             // Optional: reload page to see new counter (simulated)
             // window.location.reload(); 
        });
        
        // Clear the form
        document.getElementById('add-pos-form').reset();
    }
    </script>
</body>

</html>