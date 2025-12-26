<?php
$pageTitle = "Purchase Order Management";
include_once 'views/header.php';

// --- MOCK DATA ---
$supplierList = ["Pharma Distributors Sdn Bhd (5001)", "Health Devices (M) Corp (5002)", "Discount OTC Wholesaler (5003)"];
$purchaseOrderList = [
    [
        "id" => 1001,
        "date" => "2025-11-28",
        "supplier" => "Pharma Distributors Sdn Bhd (5001)",
        "total" => 8500.00,
        "status" => "Pending GRN",
        "buyer" => "Ali Bin Abu"
    ],
    [
        "id" => 1002,
        "date" => "2025-11-27",
        "supplier" => "Health Devices (M) Corp (5002)",
        "total" => 1250.00,
        "status" => "Completed",
        "buyer" => "Siti Aminah"
    ],
    [
        "id" => 1003,
        "date" => "2025-11-27",
        "supplier" => "Discount OTC Wholesaler (5003)",
        "total" => 550.90,
        "status" => "Cancelled",
        "buyer" => "Ah Meng"
    ],
    [
        "id" => 1004,
        "date" => "2025-11-26",
        "supplier" => "Pharma Distributors Sdn Bhd (5001)",
        "total" => 320.00,
        "status" => "Awaiting Approval",
        "buyer" => "Ali Bin Abu"
    ],
    [
        "id" => 1005,
        "date" => "2025-11-26",
        "supplier" => "New Vendor Inc.",
        "total" => 150.00,
        "status" => "Completed",
        "buyer" => "Siti Aminah"
    ],
];

// Helper to determine status badge color
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'Completed':
            return 'bg-success';
        case 'Pending GRN':
            return 'bg-info';
        case 'Awaiting Approval':
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Purchase</a></li>
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
                                        <h4 class="card-title">List of Purchase Orders</h4>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-secondary waves-effect waves-light" onclick="exportPOList()">
                                                <i class="uil uil-export me-1"></i> Export List
                                            </button>
                                            <button class="btn btn-sm btn-primary waves-effect waves-light" onclick="createNewPurchaseOrder()">
                                                <i class="uil uil-plus me-1"></i> Create New PO
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row mb-3 g-2">
                                        <div class="col-md-3">
                                            <select class="form-select form-select-sm" id="status-filter">
                                                <option value="">Filter by Status</option>
                                                <option value="Awaiting Approval">Awaiting Approval</option>
                                                <option value="Pending GRN">Pending GRN</option>
                                                <option value="Completed">Completed</option>
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
                                            <input type="text" class="form-control form-control-sm" placeholder="Search PO No. or Supplier Name...">
                                            <button class="btn btn-sm btn-secondary ms-2"><i class="uil uil-search"></i></button>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table align-middle table-nowrap table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" style="width: 100px;">PO No.</th>
                                                    <th scope="col" style="width: 120px;">Order Date</th>
                                                    <th scope="col">Supplier</th>
                                                    <th scope="col" class="text-end">Total Amount</th>
                                                    <th scope="col" class="text-center">Status</th>
                                                    <th scope="col">Prepared By</th>
                                                    <th scope="col" style="width: 160px;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($purchaseOrderList as $order): ?>
                                                <tr>
                                                    <th scope="row">PO-<?php echo $order['id']; ?></th>
                                                    <td><?php echo date('d M Y', strtotime($order['date'])); ?></td>
                                                    <td class="fw-bold"><?php echo htmlspecialchars($order['supplier']); ?></td>
                                                    <td class="text-end fw-bold text-danger">RM <?php echo number_format($order['total'], 2); ?></td>
                                                    <td class="text-center">
                                                        <span class="badge <?php echo getStatusBadgeClass($order['status']); ?> font-size-12">
                                                            <?php echo htmlspecialchars($order['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($order['buyer']); ?></td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <button class="btn btn-sm btn-outline-primary" title="View/Edit Details"
                                                                onclick="viewOrder(<?php echo $order['id']; ?>)">
                                                                <i class="uil uil-file-search-alt"></i>
                                                            </button>
                                                            <?php if ($order['status'] === 'Pending GRN'): ?>
                                                            <button class="btn btn-sm btn-outline-success" title="Create Goods Received Note (GRN)"
                                                                onclick="generateGRN(<?php echo $order['id']; ?>)">
                                                                <i class="uil uil-box"></i> GRN
                                                            </button>
                                                            <?php elseif ($order['status'] === 'Awaiting Approval'): ?>
                                                            <button class="btn btn-sm btn-outline-info" title="Approve Purchase Order"
                                                                onclick="approvePO(<?php echo $order['id']; ?>)">
                                                                <i class="uil uil-check-circle"></i> Approve
                                                            </button>
                                                            <?php endif; ?>
                                                            <?php if ($order['status'] !== 'Cancelled' && $order['status'] !== 'Completed'): ?>
                                                            <button class="btn btn-sm btn-outline-danger" title="Cancel Order"
                                                                onclick="cancelOrder(<?php echo $order['id']; ?>)">
                                                                <i class="uil uil-times"></i>
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
                                        <button class="btn btn-link text-primary" onclick="loadMoreOrders()">Load More Purchase Orders...</button>
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
            console.log('Purchase Order Management Page Loaded.');
        });
        
        function createNewPurchaseOrder() {
            alert('Simulated: Redirecting to Purchase Order entry form to create a new PO.');
        }

        function viewOrder(id) {
            alert(`Simulated: Opening detailed view/edit screen for Purchase Order PO-${id}.`);
        }
        
        function approvePO(id) {
            if (confirm(`Confirm approval for Purchase Order PO-${id}?`)) {
                alert(`Simulated: Purchase Order PO-${id} approved and status set to 'Pending GRN'.`);
            }
        }

        function generateGRN(id) {
            if (confirm(`Confirm converting Purchase Order PO-${id} to a Goods Received Note (GRN)?`)) {
                alert(`Simulated: Redirecting to GRN creation page with items from PO-${id}.`);
            }
        }
        
        function cancelOrder(id) {
            if (confirm(`Are you sure you want to CANCEL Purchase Order PO-${id}?`)) {
                alert(`Simulated: Purchase Order PO-${id} has been cancelled.`);
            }
        }

        function exportPOList() {
            alert('Simulated: Exporting current filtered list of Purchase Orders to CSV/Excel.');
        }
        
        function loadMoreOrders() {
            alert('Simulated: Loading next page of 50 Purchase Orders.');
        }
    </script>
</body>

</html>