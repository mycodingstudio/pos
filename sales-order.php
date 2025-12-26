<?php
$pageTitle = "Sales Order & Delivery Order Management";
include_once 'views/header.php';

// --- MOCK DATA ---
$customerList = ["Ahmad Zulkifli (1001)", "Chong Wei (1002)", "Retail POS Sale", "Hospital Procurement"];
$salesOrderList = [
    [
        "id" => 2023001,
        "date" => "2025-11-28",
        "customer" => "Hospital Procurement",
        "total" => 4550.00,
        "status" => "Pending DO",
        "sales_rep" => "Siti Aminah"
    ],
    [
        "id" => 2023002,
        "date" => "2025-11-27",
        "customer" => "Ahmad Zulkifli (1001)",
        "total" => 125.50,
        "status" => "Completed",
        "sales_rep" => "Ah Meng"
    ],
    [
        "id" => 2023003,
        "date" => "2025-11-27",
        "customer" => "Chong Wei (1002)",
        "total" => 89.90,
        "status" => "Cancelled",
        "sales_rep" => "Ali Bin Abu"
    ],
    [
        "id" => 2023004,
        "date" => "2025-11-26",
        "customer" => "Retail POS Sale",
        "total" => 22.00,
        "status" => "Completed",
        "sales_rep" => "Devi A/P Mohan"
    ],
    [
        "id" => 2023005,
        "date" => "2025-11-26",
        "customer" => "New Customer",
        "total" => 780.00,
        "status" => "Quotation",
        "sales_rep" => "Siti Aminah"
    ],
];

// Helper to determine status badge color
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'Completed':
            return 'bg-success';
        case 'Pending DO':
            return 'bg-warning text-dark';
        case 'Cancelled':
            return 'bg-danger';
        case 'Quotation':
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Sales</a></li>
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
                                        <h4 class="card-title">Recent Sales Transactions (Quotation/Order)</h4>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-info waves-effect waves-light" onclick="createNewQuotation()">
                                                <i class="uil uil-file-alt me-1"></i> New Quotation
                                            </button>
                                            <button class="btn btn-sm btn-primary waves-effect waves-light" onclick="createNewSalesOrder()">
                                                <i class="uil uil-plus me-1"></i> New Sales Order
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row mb-3 g-2">
                                        <div class="col-md-3">
                                            <select class="form-select form-select-sm" id="status-filter">
                                                <option value="">Filter by Status</option>
                                                <option value="Pending DO">Pending DO</option>
                                                <option value="Completed">Completed</option>
                                                <option value="Quotation">Quotation</option>
                                                <option value="Cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select form-select-sm" id="customer-filter">
                                                <option value="">Filter by Customer</option>
                                                <?php foreach ($customerList as $customer): ?>
                                                    <option value="<?php echo $customer; ?>"><?php echo $customer; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 d-flex">
                                            <input type="text" class="form-control form-control-sm" placeholder="Search SO/DO No. or Customer Name...">
                                            <button class="btn btn-sm btn-secondary ms-2"><i class="uil uil-search"></i></button>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table align-middle table-nowrap table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" style="width: 100px;">SO No.</th>
                                                    <th scope="col" style="width: 120px;">Date</th>
                                                    <th scope="col">Customer</th>
                                                    <th scope="col" class="text-end">Total Amount</th>
                                                    <th scope="col" class="text-center">Status</th>
                                                    <th scope="col">Sales Rep</th>
                                                    <th scope="col" style="width: 150px;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($salesOrderList as $order): ?>
                                                <tr>
                                                    <th scope="row">SO-<?php echo $order['id']; ?></th>
                                                    <td><?php echo date('d M Y', strtotime($order['date'])); ?></td>
                                                    <td class="fw-bold"><?php echo htmlspecialchars($order['customer']); ?></td>
                                                    <td class="text-end fw-bold">RM <?php echo number_format($order['total'], 2); ?></td>
                                                    <td class="text-center">
                                                        <span class="badge <?php echo getStatusBadgeClass($order['status']); ?> font-size-12">
                                                            <?php echo htmlspecialchars($order['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($order['sales_rep']); ?></td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <button class="btn btn-sm btn-outline-primary" title="View/Edit Details"
                                                                onclick="viewOrder(<?php echo $order['id']; ?>)">
                                                                <i class="uil uil-file-search-alt"></i>
                                                            </button>
                                                            <?php if ($order['status'] === 'Pending DO'): ?>
                                                            <button class="btn btn-sm btn-outline-success" title="Generate Delivery Order"
                                                                onclick="generateDO(<?php echo $order['id']; ?>)">
                                                                <i class="uil uil-truck"></i> DO
                                                            </button>
                                                            <?php elseif ($order['status'] === 'Completed'): ?>
                                                            <button class="btn btn-sm btn-outline-secondary" title="View Invoice"
                                                                onclick="viewInvoice(<?php echo $order['id']; ?>)">
                                                                <i class="uil uil-bill"></i> Invoice
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
                                        <button class="btn btn-link text-primary" onclick="loadMoreOrders()">Load More Orders...</button>
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
            console.log('Sales Order Management Page Loaded.');
        });
        
        function createNewQuotation() {
            alert('Simulated: Redirecting to Quotation entry form to create a new Sales Quotation.');
        }

        function createNewSalesOrder() {
            alert('Simulated: Redirecting to Sales Order entry form to create a new Sales Order.');
        }

        function viewOrder(id) {
            alert(`Simulated: Opening detailed view/edit screen for Sales Order SO-${id}.`);
        }
        
        function generateDO(id) {
            if (confirm(`Confirm generating Delivery Order for SO-${id}? This will reserve stock.`)) {
                alert(`Simulated: Delivery Order DO-${id} generated successfully! Stock reserved. The next step is picking and shipping.`);
            }
        }

        function viewInvoice(id) {
            alert(`Simulated: Opening the final Invoice document for SO-${id}.`);
        }
        
        function cancelOrder(id) {
            if (confirm(`Are you sure you want to CANCEL Sales Order SO-${id}?`)) {
                alert(`Simulated: Sales Order SO-${id} has been cancelled.`);
            }
        }
        
        function loadMoreOrders() {
            alert('Simulated: Loading next page of 50 Sales Orders.');
        }
    </script>
</body>

</html>