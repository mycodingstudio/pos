<?php
$pageTitle = "Inventory Audit Log";
include_once 'views/header.php';

// --- MOCK GLOBAL DATA ---
$locations = ['Main Store A', 'Main Store B', 'Warehouse 1', 'Retail Floor', 'Counter 1', 'Counter 2'];
$transactionTypes = ['RECEIVE', 'ISSUE', 'ADJUSTMENT', 'WRITE-OFF', 'TRANSFER-OUT', 'TRANSFER-IN'];
$users = ['Admin/Supervisor Siti', 'Cashier Ah Meng', 'Pharmacist Ali', 'System Bot (Expiry)'];

// --- MOCK AUDIT DATA GENERATION (50 Records) ---
$auditLog = [];
$totalRecords = 50;
$currentSku = 1;

for ($i = 1; $i <= $totalRecords; $i++) {
    $type = $transactionTypes[array_rand($transactionTypes)];
    $location = $locations[array_rand($locations)];
    $user = $users[array_rand($users)];
    
    // Generate Item Data
    if ($i % 5 == 0) {
        $currentSku++;
    }
    $sku = 'SKU-' . str_pad($currentSku, 4, '0', STR_PAD_LEFT);
    $itemName = ($currentSku % 2 == 0 ? 'Paracetamol 500mg' : 'Vitamin C 1000mg') . ' (' . ($currentSku % 3 == 0 ? 'Large' : 'Small') . ')';
    $batchNo = 'BN' . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT);

    // Generate Quantity Change
    $qtyChange = 0;
    $prevQty = rand(0, 1000);
    switch ($type) {
        case 'RECEIVE':
        case 'TRANSFER-IN':
            $qtyChange = rand(10, 200);
            $qtyChangeDisplay = "+".$qtyChange;
            break;
        case 'ISSUE':
        case 'WRITE-OFF':
        case 'TRANSFER-OUT':
            $qtyChange = -rand(1, 50);
            $qtyChangeDisplay = $qtyChange;
            break;
        case 'ADJUSTMENT':
            $qtyChange = rand(-10, 10);
            $qtyChangeDisplay = ($qtyChange >= 0 ? "+" : "") . $qtyChange;
            break;
    }
    
    $newQty = max(0, $prevQty + $qtyChange);

    // Generate Date and Reference
    $daysAgo = rand(1, 90);
    $date = date('Y-m-d H:i:s', strtotime("-$daysAgo days"));
    $reference = strtoupper(substr($type, 0, 3)) . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
    
    $auditLog[] = [
        "id" => $i,
        "date" => $date,
        "type" => $type,
        "location" => $location,
        "sku" => $sku,
        "item_name" => $itemName,
        "batch_no" => $batchNo,
        "qty_change" => $qtyChangeDisplay,
        "prev_qty" => $prevQty,
        "new_qty" => $newQty,
        "user" => $user,
        "reference" => $reference
    ];
}

// Helper to determine status color
function getChangeColor($qtyChange) {
    if (strpos($qtyChange, '+') !== false && $qtyChange !== '+0') {
        return 'text-success fw-bold';
    } elseif ($qtyChange < 0) {
        return 'text-danger fw-bold';
    } else {
        return 'text-muted';
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
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="card-title">Detailed Stock Audit Log (<?php echo number_format($totalRecords); ?> Records)</h4>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="uil uil-print me-1"></i> Print Report
                                        </button>
                                    </div>

                                    <div class="row mb-4 g-2">
                                        <div class="col-md-2">
                                            <label for="filter-type" class="form-label visually-hidden">Transaction Type</label>
                                            <select class="form-select form-select-sm" id="filter-type">
                                                <option value="">All Types</option>
                                                <?php foreach ($transactionTypes as $type): ?>
                                                    <option value="<?php echo $type; ?>"><?php echo ucwords(str_replace('-', ' ', strtolower($type))); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="filter-location" class="form-label visually-hidden">Location</label>
                                            <select class="form-select form-select-sm" id="filter-location">
                                                <option value="">All Locations</option>
                                                <?php foreach ($locations as $loc): ?>
                                                    <option value="<?php echo $loc; ?>"><?php echo $loc; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="filter-user" class="form-label visually-hidden">User</label>
                                            <select class="form-select form-select-sm" id="filter-user">
                                                <option value="">All Users</option>
                                                <?php foreach ($users as $user): ?>
                                                    <option value="<?php echo $user; ?>"><?php echo $user; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="filter-date-start" class="form-label visually-hidden">Date Range Start</label>
                                            <input type="date" class="form-control form-control-sm" id="filter-date-start" value="<?php echo date('Y-m-d', strtotime('-30 days')); ?>">
                                        </div>
                                        <div class="col-md-3 d-flex">
                                            <input type="text" class="form-control form-control-sm" placeholder="Search SKU/Ref No..." id="search-input">
                                            <button class="btn btn-sm btn-secondary ms-2" onclick="applyFilters()">
                                                <i class="uil uil-filter"></i> Apply
                                            </button>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover align-middle table-nowrap mb-0" id="inventoryAuditTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" style="width: 50px;">#</th>
                                                    <th scope="col" style="width: 150px;">Timestamp</th>
                                                    <th scope="col">Trans. Type</th>
                                                    <th scope="col">Reference No.</th>
                                                    <th scope="col">Item (SKU)</th>
                                                    <th scope="col">Batch No.</th>
                                                    <th scope="col" class="text-center">Qty Change</th>
                                                    <th scope="col" class="text-center">New Qty</th>
                                                    <th scope="col">Location</th>
                                                    <th scope="col">Recorded By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($auditLog as $log): ?>
                                                <tr>
                                                    <td><?php echo $log['id']; ?></td>
                                                    <td><?php echo date('d M Y H:i', strtotime($log['date'])); ?></td>
                                                    <td>
                                                        <span class="badge 
                                                            <?php 
                                                                if ($log['type'] === 'RECEIVE' || $log['type'] === 'TRANSFER-IN') echo 'bg-success';
                                                                elseif ($log['type'] === 'WRITE-OFF' || $log['type'] === 'ISSUE') echo 'bg-danger';
                                                                elseif ($log['type'] === 'ADJUSTMENT') echo 'bg-warning text-dark';
                                                                else echo 'bg-info';
                                                            ?>">
                                                            <?php echo $log['type']; ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo $log['reference']; ?></td>
                                                    <td><?php echo $log['item_name']; ?> (<?php echo $log['sku']; ?>)</td>
                                                    <td><?php echo $log['batch_no']; ?></td>
                                                    <td class="text-center <?php echo getChangeColor($log['qty_change']); ?>"><?php echo $log['qty_change']; ?></td>
                                                    <td class="text-center"><?php echo number_format($log['new_qty']); ?></td>
                                                    <td><?php echo $log['location']; ?></td>
                                                    <td><?php echo $log['user']; ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-3 d-flex justify-content-between align-items-center">
                                        <p class="text-muted mb-0">Showing 1 to 50 of <?php echo number_format($totalRecords); ?> entries (50 per page)</p>
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination pagination-sm justify-content-end mb-0">
                                                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                                            </ul>
                                        </nav>
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

    <?php include_once 'views/footer_libraries.php'; ?>
    <script src="assets/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Inventory Audit Log Page Loaded with mock data.');
        });
        
        // Mock function to simulate applying filters
        function applyFilters() {
            const type = document.getElementById('filter-type').value;
            const location = document.getElementById('filter-location').value;
            const user = document.getElementById('filter-user').value;
            const dateStart = document.getElementById('filter-date-start').value;
            const search = document.getElementById('search-input').value;

            // In a real application, this function would send an AJAX request 
            // to the server with these parameters to fetch filtered data.
            alert(`Simulated Filter Applied:\nType: ${type}\nLocation: ${location}\nUser: ${user}\nDate From: ${dateStart}\nSearch: ${search}`);
        }
    </script>
</body>

</html>