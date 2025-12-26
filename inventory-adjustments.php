<?php
$pageTitle = "Inventory Stock Adjustments & Write-offs";
include_once 'views/header.php';

// --- MOCK DATA ---
$adjustmentTypes = [
    "Write-Off (Spoilage/Expired)", 
    "Write-In (Found Stock/Correction)", 
    "Inter-Location Transfer", 
    "Promotional Sample",
    "Damage/Breakage"
];
$locationList = ["Main Warehouse", "In-Store Stockroom", "Receiving Counter"];
$staffList = ["Ali Bin Abu", "Siti Aminah", "Ah Meng"];

$adjustmentList = [
    [
        "id" => 6005,
        "date" => "2025-11-29",
        "type" => "Write-Off (Spoilage/Expired)",
        "location" => "In-Store Stockroom",
        "total_items" => 12,
        "total_value" => -55.90, // Negative for write-off (out)
        "status" => "Approved",
        "created_by" => "Siti Aminah",
    ],
    [
        "id" => 6004,
        "date" => "2025-11-28",
        "type" => "Write-In (Found Stock/Correction)",
        "location" => "Main Warehouse",
        "total_items" => 5,
        "total_value" => 25.00, // Positive for write-in (in)
        "status" => "Pending Approval",
        "created_by" => "Ali Bin Abu",
    ],
    [
        "id" => 6003,
        "date" => "2025-11-27",
        "type" => "Inter-Location Transfer",
        "location" => "Main Warehouse -> Receiving Counter", // Example for transfer
        "total_items" => 100,
        "total_value" => 0.00, // Transfers usually have zero value impact on total stock value
        "status" => "Approved",
        "created_by" => "Ah Meng",
    ],
    [
        "id" => 6002,
        "date" => "2025-11-25",
        "type" => "Damage/Breakage",
        "location" => "In-Store Stockroom",
        "total_items" => 3,
        "total_value" => -15.00,
        "status" => "Rejected",
        "created_by" => "Siti Aminah",
    ],
];

// Helper to determine status badge color
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'Approved':
            return 'bg-success';
        case 'Pending Approval':
            return 'bg-warning text-dark';
        case 'Rejected':
            return 'bg-danger';
        default:
            return 'bg-secondary';
    }
}

// Helper to determine value color
function getValueClass($value) {
    return $value < 0 ? 'text-danger' : ($value > 0 ? 'text-success' : 'text-muted');
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
                                        <h4 class="card-title">Recorded Stock Adjustments</h4>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-secondary waves-effect waves-light" onclick="exportAdjustmentList()">
                                                <i class="uil uil-export me-1"></i> Export List
                                            </button>
                                            <button class="btn btn-sm btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#newAdjustmentModal">
                                                <i class="uil uil-plus me-1"></i> Create New Adjustment
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row mb-3 g-2">
                                        <div class="col-md-3">
                                            <select class="form-select form-select-sm" id="type-filter">
                                                <option value="">Filter by Type</option>
                                                <?php foreach ($adjustmentTypes as $type): ?>
                                                    <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select form-select-sm" id="status-filter">
                                                <option value="">Filter by Status</option>
                                                <option value="Approved">Approved</option>
                                                <option value="Pending Approval">Pending Approval</option>
                                                <option value="Rejected">Rejected</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 d-flex">
                                            <input type="text" class="form-control form-control-sm" placeholder="Search Adj. No., Item, or Creator...">
                                            <button class="btn btn-sm btn-secondary ms-2"><i class="uil uil-search"></i></button>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table align-middle table-nowrap table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" style="width: 100px;">Adj. No.</th>
                                                    <th scope="col" style="width: 120px;">Date</th>
                                                    <th scope="col">Type / Reason</th>
                                                    <th scope="col">Location</th>
                                                    <th scope="col" class="text-end">Total Items</th>
                                                    <th scope="col" class="text-end">Total Value (RM)</th>
                                                    <th scope="col" class="text-center">Status</th>
                                                    <th scope="col" style="width: 150px;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($adjustmentList as $adj): ?>
                                                <tr>
                                                    <th scope="row">ADJ-<?php echo $adj['id']; ?></th>
                                                    <td><?php echo date('d M Y', strtotime($adj['date'])); ?></td>
                                                    <td class="fw-bold"><?php echo htmlspecialchars($adj['type']); ?></td>
                                                    <td><?php echo htmlspecialchars($adj['location']); ?></td>
                                                    <td class="text-end"><?php echo number_format($adj['total_items']); ?></td>
                                                    <td class="text-end fw-bold <?php echo getValueClass($adj['total_value']); ?>">
                                                        <?php echo number_format(abs($adj['total_value']), 2); ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge <?php echo getStatusBadgeClass($adj['status']); ?> font-size-12">
                                                            <?php echo htmlspecialchars($adj['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <button class="btn btn-sm btn-outline-primary" title="View Details"
                                                                onclick="viewAdjustment(<?php echo $adj['id']; ?>)">
                                                                <i class="uil uil-file-search-alt"></i> View
                                                            </button>
                                                            <?php if ($adj['status'] === 'Pending Approval'): ?>
                                                            <button class="btn btn-sm btn-warning text-dark" title="Approve/Reject"
                                                                onclick="manageApproval(<?php echo $adj['id']; ?>)">
                                                                <i class="uil uil-check-circle"></i> Review
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
                                        <button class="btn btn-link text-primary" onclick="loadMoreAdjustments()">Load More Adjustments...</button>
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
    
    <div class="modal fade" id="newAdjustmentModal" tabindex="-1" aria-labelledby="newAdjustmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newAdjustmentModalLabel"><i class="uil uil-plus me-1"></i> Create Manual Stock Adjustment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form onsubmit="createNewAdjustment(event)">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="adj-type" class="form-label">Adjustment Type</label>
                                <select class="form-select" id="adj-type" required>
                                    <option value="" disabled selected>Select a reason for adjustment</option>
                                    <?php foreach ($adjustmentTypes as $type): ?>
                                        <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="adj-location" class="form-label">Source/Destination Location</label>
                                <select class="form-select" id="adj-location" required>
                                    <option value="" disabled selected>Select location(s)</option>
                                    <?php foreach ($locationList as $location): ?>
                                        <option value="<?php echo $location; ?>"><?php echo $location; ?></option>
                                    <?php endforeach; ?>
                                    <option value="Transfer">Inter-Location Transfer (Special)</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="adj-item-list" class="form-label">Items to Adjust (Simulation)</label>
                            <textarea class="form-control" id="adj-item-list" rows="3" placeholder="Enter item SKU and Quantity (e.g., SKU1001, -5 for write-off; SKU2002, +2 for write-in)" required></textarea>
                            <small class="form-text text-muted">In a live system, this would be an interface to search and add items individually.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="adj-remarks" class="form-label">Remarks / Justification</label>
                            <textarea class="form-control" id="adj-remarks" rows="2" placeholder="Provide a brief reason for the stock change." required></textarea>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="require-approval" checked>
                            <label class="form-check-label" for="require-approval">
                                Require Supervisor/Manager Approval
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit for Adjustment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php include_once 'views/footer_libraries.php'; ?>
    <script src="assets/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Inventory Adjustments Page Loaded.');
        });
        
        function createNewAdjustment(event) {
            event.preventDefault();
            const type = document.getElementById('adj-type').value;
            const location = document.getElementById('adj-location').value;
            const remarks = document.getElementById('adj-remarks').value;
            
            // Close the modal
            const modalElement = document.getElementById('newAdjustmentModal');
            const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
            modal.hide();

            Swal.fire({
                title: 'Adjustment Submitted',
                html: `Adjustment ADJ-6006 submitted successfully.<br>Type: <b>${type}</b><br>Status: <b>Pending Approval</b>`,
                icon: 'info',
                confirmButtonText: 'OK'
            });
            document.getElementById('newAdjustmentModal').querySelector('form').reset();
        }

        function viewAdjustment(id) {
            alert(`Simulated: Opening detailed view for Adjustment ADJ-${id}. Shows list of items, quantities, and values.`);
        }
        
        function manageApproval(id) {
            Swal.fire({
                title: `Review Adjustment ADJ-${id}`,
                text: "The adjustment is pending approval. Do you wish to Approve or Reject this change?",
                icon: 'warning',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: 'Approve',
                denyButtonText: 'Reject',
                cancelButtonText: 'Cancel Review'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Approved!', `Adjustment ADJ-${id} has been approved and stock updated.`, 'success');
                } else if (result.isDenied) {
                    Swal.fire('Rejected!', `Adjustment ADJ-${id} has been rejected and will not affect stock.`, 'error');
                }
            });
        }

        function exportAdjustmentList() {
            alert('Simulated: Exporting current filtered list of Inventory Adjustments to CSV/Excel.');
        }
        
        function loadMoreAdjustments() {
            alert('Simulated: Loading next page of 50 Adjustments.');
        }
    </script>
</body>

</html>