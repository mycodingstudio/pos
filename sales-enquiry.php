<?php
$pageTitle = "Sales & Transaction Enquiry Screen";
include_once 'views/header.php';

// --- MOCK DATA ---
$cashierList = ["Ali Bin Abu", "Siti Aminah", "Ah Meng"];
$posCounters = ["Counter 1 (Prescription)", "Counter 2 (OTC/Express)", "Counter 3 (Supervisor)"];

$salesTransactions = [
    [
        "id" => 20251129005,
        "date" => "2025-11-29 14:30:15",
        "total" => 155.50,
        "type" => "Sales",
        "pos_id" => "C-1",
        "cashier" => "Ali Bin Abu",
        "customer" => "Ahmad Zulkifli (1001)",
        "status" => "Completed"
    ],
    [
        "id" => 20251129004,
        "date" => "2025-11-29 11:05:40",
        "total" => 45.00,
        "type" => "Sales",
        "pos_id" => "C-2",
        "cashier" => "Ah Meng",
        "customer" => "Walk-in Customer",
        "status" => "Completed"
    ],
    [
        "id" => 20251128099,
        "date" => "2025-11-28 17:55:00",
        "total" => 25.90,
        "type" => "Return",
        "pos_id" => "C-3",
        "cashier" => "Siti Aminah",
        "customer" => "Chong Wei (1002)",
        "status" => "Completed"
    ],
    [
        "id" => 20251128001,
        "date" => "2025-11-28 09:10:00",
        "total" => 550.75,
        "type" => "Sales",
        "pos_id" => "C-1",
        "cashier" => "Ali Bin Abu",
        "customer" => "Walk-in Customer",
        "status" => "Completed"
    ],
];

// Helper to determine status badge color
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'Completed':
            return 'bg-success';
        case 'Return':
            return 'bg-danger';
        case 'Voided':
            return 'bg-secondary';
        default:
            return 'bg-primary';
    }
}

// Helper to determine transaction type color
function getTypeClass($type) {
    return $type === 'Return' ? 'text-danger' : 'text-success';
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
                                    <h4 class="card-title mb-4">Quick Sales Search & Lookup</h4>
                                    
                                    <form class="row g-3 mb-4" onsubmit="searchSales(event)">
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" id="search-transaction-id" placeholder="Transaction ID or Invoice No.">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" id="search-product-sku" placeholder="Product Name or SKU">
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-select" id="search-cashier">
                                                <option value="">All Cashiers</option>
                                                <?php foreach ($cashierList as $cashier): ?>
                                                    <option value="<?php echo $cashier; ?>"><?php echo $cashier; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" class="form-control" id="search-date-from" value="<?php echo date('Y-m-d'); ?>">
                                            <div class="form-text">Date From</div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="uil uil-search me-1"></i> Search
                                            </button>
                                        </div>
                                    </form>

                                    <div class="mt-5">
                                        <h5 class="mb-3">Transaction Results (Last 4 Records)</h5>
                                        <div class="table-responsive">
                                            <table class="table align-middle table-nowrap table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col" style="width: 120px;">Trans. ID</th>
                                                        <th scope="col" style="width: 150px;">Date/Time</th>
                                                        <th scope="col">Cashier</th>
                                                        <th scope="col">Customer</th>
                                                        <th scope="col" class="text-end">Total Amount</th>
                                                        <th scope="col" class="text-center">Type</th>
                                                        <th scope="col" style="width: 100px;">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($salesTransactions as $trans): ?>
                                                    <tr>
                                                        <th scope="row">#<?php echo $trans['id']; ?></th>
                                                        <td><?php echo date('d M Y H:i', strtotime($trans['date'])); ?></td>
                                                        <td><?php echo htmlspecialchars($trans['cashier']); ?> (<?php echo $trans['pos_id']; ?>)</td>
                                                        <td><?php echo htmlspecialchars($trans['customer']); ?></td>
                                                        <td class="text-end fw-bold <?php echo getTypeClass($trans['type']); ?>">
                                                            <?php echo ($trans['type'] === 'Return' ? '- ' : '+ '); ?>RM <?php echo number_format($trans['total'], 2); ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge <?php echo getStatusBadgeClass($trans['type']); ?> font-size-12">
                                                                <?php echo htmlspecialchars($trans['type']); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary" title="View Details"
                                                                onclick="viewTransaction(<?php echo $trans['id']; ?>)">
                                                                <i class="uil uil-file-search-alt"></i> View
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <div class="mt-3 text-center">
                                            <button class="btn btn-link text-primary" onclick="loadMoreTransactions()">Load More Transactions...</button>
                                        </div>
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
            console.log('Sales Enquiry Page Loaded.');
        });
        
        function searchSales(event) {
            event.preventDefault();
            const transactionId = document.getElementById('search-transaction-id').value;
            const productSku = document.getElementById('search-product-sku').value;
            const cashier = document.getElementById('search-cashier').value;
            const dateFrom = document.getElementById('search-date-from').value;

            alert(`Simulated: Searching Sales Records with:\n- ID/Invoice: "${transactionId}"\n- Product: "${productSku}"\n- Cashier: "${cashier}"\n- Date From: ${dateFrom}\n(Displaying mock list of 4 transactions)`);
            // In a real application, this would send an AJAX request and refresh the table.
        }

        function viewTransaction(id) {
            alert(`Simulated: Opening detailed sales transaction view for # ${id}. This includes item list, payment methods, and customer info.`);
        }
        
        function loadMoreTransactions() {
            alert('Simulated: Loading the next 50 sales transactions that match the current filters.');
        }
    </script>
</body>

</html>