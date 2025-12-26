<?php
$pageTitle = "Goods Received Note (GRN) List";
include_once 'views/header.php';

// --- MOCK DATA ---
$supplierList = ["Pharma Distributors Sdn Bhd (5001)", "Health Devices (M) Corp (5002)"];
$locationList = ["Main Warehouse", "In-Store Stockroom", "Receiving Counter 1"];

$grnList = [
    [
        "id" => 3001,
        "date" => "2025-11-29",
        "supplier" => "Pharma Distributors Sdn Bhd (5001)",
        "po_ref" => "PO-1001",
        "total_items" => 150,
        "status" => "Completed",
        "receiver" => "Ali Bin Abu",
        "location" => "Main Warehouse"
    ],
    [
        "id" => 3002,
        "date" => "2025-11-27",
        "supplier" => "Health Devices (M) Corp (5002)",
        "po_ref" => "PO-1002",
        "total_items" => 25,
        "status" => "Completed",
        "receiver" => "Siti Aminah",
        "location" => "Receiving Counter 1"
    ],
    [
        "id" => 3003,
        "date" => "2025-11-26",
        "supplier" => "Discount OTC Wholesaler (5003)",
        "po_ref" => "PO-1005",
        "total_items" => 50,
        "status" => "Partial",
        "receiver" => "Ah Meng",
        "location" => "In-Store Stockroom"
    ],
    [
        "id" => 3004,
        "date" => "2025-11-25",
        "supplier" => "Pharma Distributors Sdn Bhd (5001)",
        "po_ref" => "PO-1004",
        "total_items" => 0,
        "status" => "Cancelled",
        "receiver" => "Ali Bin Abu",
        "location" => "Main Warehouse"
    ],
];

// Helper to determine status badge color
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'Completed':
            return 'bg-success';
        case 'Partial':
            return 'bg-warning text-dark';
        case 'Cancelled':
            return 'bg-danger';
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
                                        <h4 class="card-title">Recorded Goods Received Notes</h4>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-secondary waves-effect waves-light" onclick="exportGRNList()">
                                                <i class="uil uil-export me-1"></i> Export List
                                            </button>
                                            <button class="btn btn-sm btn-primary waves-effect waves-light" onclick="createNewGRN()">
                                                <i class="uil uil-plus me-1"></i> Manual GRN Entry
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row mb-3 g-2">
                                        <div class="col-md-3">
                                            <select class="form-select form-select-sm" id="status-filter">
                                                <option value="">Filter by Status</option>
                                                <option value="Completed">Completed</option>
                                                <option value="Partial">Partial</option>
                                                <option value="Cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select form-select-sm" id="supplier-filter">
                                                <option value="">Filter by Supplier</option>
                                                <?php foreach ($supplierList as $supplier): ?>
                                                    <option value="<?php echo $supplier; ?>"><?php echo $supplier; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 d-flex">
                                            <input type="text" class="form-control form-control-sm" placeholder="Search GRN No., PO Ref, or Receiver...">
                                            <button class="btn btn-sm btn-secondary ms-2"><i class="uil uil-search"></i></button>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table align-middle table-nowrap table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" style="width: 100px;">GRN No.</th>
                                                    <th scope="col" style="width: 120px;">Date Received</th>
                                                    <th scope="col">Supplier</th>
                                                    <th scope="col" style="width: 100px;">PO Ref</th>
                                                    <th scope="col" class="text-end">Total Items</th>
                                                    <th scope="col">Receiver</th>
                                                    <th scope="col" class="text-center">Status</th>
                                                    <th scope="col" style="width: 100px;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($grnList as $grn): ?>
                                                <tr>
                                                    <th scope="row">GRN-<?php echo $grn['id']; ?></th>
                                                    <td><?php echo date('d M Y', strtotime($grn['date'])); ?></td>
                                                    <td class="fw-bold"><?php echo htmlspecialchars($grn['supplier']); ?></td>
                                                    <td><?php echo htmlspecialchars($grn['po_ref']); ?></td>
                                                    <td class="text-end fw-bold"><?php echo number_format($grn['total_items']); ?></td>
                                                    <td><?php echo htmlspecialchars($grn['receiver']); ?></td>
                                                    <td class="text-center">
                                                        <span class="badge <?php echo getStatusBadgeClass($grn['status']); ?> font-size-12">
                                                            <?php echo htmlspecialchars($grn['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <button class="btn btn-sm btn-outline-primary" title="View Details"
                                                                onclick="viewGRN(<?php echo $grn['id']; ?>)">
                                                                <i class="uil uil-file-search-alt"></i>
                                                            </button>
                                                            <?php if ($grn['status'] === 'Completed' || $grn['status'] === 'Partial'): ?>
                                                            <button class="btn btn-sm btn-outline-success" title="Generate Supplier Bill/Invoice"
                                                                onclick="generateBill(<?php echo $grn['id']; ?>)">
                                                                <i class="uil uil-bill"></i> Bill
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
                                        <button class="btn btn-link text-primary" onclick="loadMoreGRNs()">Load More GRNs...</button>
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
            console.log('Goods Received Note (GRN) Page Loaded.');
        });
        
        function createNewGRN() {
            alert('Simulated: Redirecting to Manual GRN entry form (No linked PO).');
        }

        function viewGRN(id) {
            alert(`Simulated: Opening detailed view/edit screen for GRN-${id}. Includes item-level quantity received and location.`);
        }
        
        function generateBill(id) {
            if (confirm(`Confirm generating Supplier Bill/Invoice for GRN-${id}?`)) {
                alert(`Simulated: Redirecting to Payables to record Supplier Bill INV-SUP-${id} from GRN-${id}.`);
            }
        }

        function exportGRNList() {
            alert('Simulated: Exporting current filtered list of GRNs to CSV/Excel.');
        }
        
        function loadMoreGRNs() {
            alert('Simulated: Loading next page of 50 GRNs.');
        }
    </script>
</body>

</html>