<?php
$pageTitle = "Inventory Stock Counting & Take";
include_once 'views/header.php';

// --- MOCK DATA ---
$locationList = ["Main Warehouse", "In-Store Stockroom", "Receiving Counter"];
$staffList = ["Ali Bin Abu", "Siti Aminah", "Ah Meng"];

$stockTakeList = [
    [
        "id" => 5001,
        "date" => "2025-11-29",
        "location" => "In-Store Stockroom",
        "progress" => 75, // Percentage of items counted
        "status" => "Counting in Progress",
        "counted_by" => "Siti Aminah",
        "variance_value" => 0.00, // Variance usually calculated upon finalization
        "variance_count" => 0
    ],
    [
        "id" => 5002,
        "date" => "2025-11-25",
        "location" => "Main Warehouse",
        "progress" => 100,
        "status" => "Awaiting Finalization",
        "counted_by" => "Ali Bin Abu",
        "variance_value" => -450.50, // Negative means shortage
        "variance_count" => -15
    ],
    [
        "id" => 5003,
        "date" => "2025-11-01",
        "location" => "Receiving Counter",
        "progress" => 100,
        "status" => "Completed & Adjusted",
        "counted_by" => "Ah Meng",
        "variance_value" => 12.00, // Positive means overage
        "variance_count" => 2
    ],
];

// Helper to determine status badge color
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'Completed & Adjusted':
            return 'bg-success';
        case 'Awaiting Finalization':
            return 'bg-warning text-dark';
        case 'Counting in Progress':
            return 'bg-info';
        default:
            return 'bg-secondary';
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

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 font-size-18"><?php echo $pageTitle; ?></h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Inventory</a></li>
                                        <li class="breadcrumb-item active"><?php echo $pageTitle; ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h4 class="card-title">Stock Take Sessions</h4>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-secondary waves-effect waves-light" onclick="exportStockTakeList()">
                                                <i class="uil uil-export me-1"></i> Export List
                                            </button>
                                            <button class="btn btn-sm btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#newStockTakeModal">
                                                <i class="uil uil-plus me-1"></i> Start New Stock Take
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row mb-3 g-2">
                                        <div class="col-md-3">
                                            <select class="form-select form-select-sm" id="status-filter">
                                                <option value="">Filter by Status</option>
                                                <option value="Counting in Progress">In Progress</option>
                                                <option value="Awaiting Finalization">Awaiting Finalization</option>
                                                <option value="Completed & Adjusted">Completed</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select form-select-sm" id="location-filter">
                                                <option value="">Filter by Location</option>
                                                <?php foreach ($locationList as $location): ?>
                                                    <option value="<?php echo $location; ?>"><?php echo $location; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 d-flex">
                                            <input type="text" class="form-control form-control-sm" placeholder="Search Session No. or Staff Name...">
                                            <button class="btn btn-sm btn-secondary ms-2"><i class="uil uil-search"></i></button>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table align-middle table-nowrap table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" style="width: 100px;">Session ID</th>
                                                    <th scope="col" style="width: 120px;">Start Date</th>
                                                    <th scope="col">Location</th>
                                                    <th scope="col" class="text-center">Status</th>
                                                    <th scope="col" class="text-center">Progress</th>
                                                    <th scope="col">Counted By</th>
                                                    <th scope="col" class="text-end">Variance Value</th>
                                                    <th scope="col" style="width: 160px;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($stockTakeList as $session): 
                                                    $progressClass = $session['progress'] < 100 ? 'bg-info' : 'bg-success';
                                                    $varianceClass = $session['variance_value'] < 0 ? 'text-danger' : ($session['variance_value'] > 0 ? 'text-success' : 'text-muted');
                                                ?>
                                                <tr>
                                                    <th scope="row">ST-<?php echo $session['id']; ?></th>
                                                    <td><?php echo date('d M Y', strtotime($session['date'])); ?></td>
                                                    <td class="fw-bold"><?php echo htmlspecialchars($session['location']); ?></td>
                                                    <td class="text-center">
                                                        <span class="badge <?php echo getStatusBadgeClass($session['status']); ?> font-size-12">
                                                            <?php echo htmlspecialchars($session['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="progress" style="height: 5px;">
                                                            <div class="progress-bar <?php echo $progressClass; ?>" role="progressbar" style="width: <?php echo $session['progress']; ?>%" aria-valuenow="<?php echo $session['progress']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <small><?php echo $session['progress']; ?>%</small>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($session['counted_by']); ?></td>
                                                    <td class="text-end fw-bold <?php echo $varianceClass; ?>">
                                                        <?php 
                                                            if ($session['status'] === 'Completed & Adjusted' || $session['status'] === 'Awaiting Finalization') {
                                                                echo 'RM ' . number_format(abs($session['variance_value']), 2);
                                                            } else {
                                                                echo 'N/A';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <?php if ($session['status'] === 'Counting in Progress'): ?>
                                                            <button class="btn btn-sm btn-info" title="Continue Counting"
                                                                onclick="continueCount(<?php echo $session['id']; ?>)">
                                                                <i class="uil uil-edit"></i> Count
                                                            </button>
                                                            <?php elseif ($session['status'] === 'Awaiting Finalization'): ?>
                                                            <button class="btn btn-sm btn-warning text-dark" title="Review and Finalize"
                                                                onclick="finalizeCount(<?php echo $session['id']; ?>)">
                                                                <i class="uil uil-check-circle"></i> Finalize
                                                            </button>
                                                            <?php else: ?>
                                                            <button class="btn btn-sm btn-outline-primary" title="View Report"
                                                                onclick="viewReport(<?php echo $session['id']; ?>)">
                                                                <i class="uil uil-file-search-alt"></i> Report
                                                            </button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-3 text-center">
                                        <button class="btn btn-link text-primary" onclick="loadMoreSessions()">Load More Stock Take Sessions...</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
            </div>

            <?php include_once 'views/footer.php'; ?>
        </div>
    </div>
    
    <div class="modal fade" id="newStockTakeModal" tabindex="-1" aria-labelledby="newStockTakeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newStockTakeModalLabel"><i class="uil uil-plus me-1"></i> Start New Inventory Count</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form onsubmit="startNewStockTake(event)">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="stock-location" class="form-label">Inventory Location to Count</label>
                            <select class="form-select" id="stock-location" required>
                                <option value="" disabled selected>Select a location</option>
                                <?php foreach ($locationList as $location): ?>
                                    <option value="<?php echo $location; ?>"><?php echo $location; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">A count session can only target one physical location.</small>
                        </div>
                        <div class="mb-3">
                            <label for="assigned-staff" class="form-label">Assigned Staff/Auditor</label>
                            <select class="form-select" id="assigned-staff" required>
                                <option value="" disabled selected>Select staff member</option>
                                <?php foreach ($staffList as $staff): ?>
                                    <option value="<?php echo $staff; ?>"><?php echo $staff; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="filter-by-supplier">
                            <label class="form-check-label" for="filter-by-supplier">
                                Filter count list by a specific Supplier/Brand
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Start Counting Session</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php include_once 'views/footer_libraries.php'; ?>
    <script src="assets/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Inventory Stock Counting Page Loaded.');
        });
        
        function startNewStockTake(event) {
            event.preventDefault();
            const location = document.getElementById('stock-location').value;
            const staff = document.getElementById('assigned-staff').value;
            
            // Close the modal
            const modalElement = document.getElementById('newStockTakeModal');
            const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
            modal.hide();

            Swal.fire({
                title: 'Session Started!',
                html: `New Stock Take Session <b>ST-5004</b> initiated.<br>Location: <b>${location}</b><br>Staff: <b>${staff}</b>`,
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: 'Go to Counting Screen',
                cancelButtonText: 'Stay on List Page'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to a specific counting screen (e.g., inventory-counting-detail.php?id=5004)
                    alert('Simulated: Redirecting to the item-by-item counting interface.');
                }
            });
            document.getElementById('stock-location').value = ''; // Reset form after alert
            document.getElementById('assigned-staff').value = ''; 
        }

        function continueCount(id) {
            alert(`Simulated: Opening Count Interface for session ST-${id} to continue counting.`);
        }
        
        function finalizeCount(id) {
            if (confirm(`Confirm you want to FINALIZE Stock Take Session ST-${id}? This will generate stock adjustments based on variances.`)) {
                alert(`Simulated: Finalizing session ST-${id}. Stock adjustment (write-off/write-in) documents created.`);
            }
        }
        
        function viewReport(id) {
            alert(`Simulated: Viewing Variance Report for completed session ST-${id}.`);
        }

        function exportStockTakeList() {
            alert('Simulated: Exporting current filtered list of Stock Take sessions to CSV/Excel.');
        }
        
        function loadMoreSessions() {
            alert('Simulated: Loading next page of 50 Stock Take Sessions.');
        }
    </script>
</body>

</html>