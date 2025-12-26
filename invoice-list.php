<?php
$pageTitle = "Invoice & Credit Note List";
include_once 'views/header.php';

// --- MOCK DATA ---
$customerList = ["Ahmad Zulkifli (1001)", "Chong Wei (1002)", "Hospital Procurement", "Acme Retail Store"];
$invoiceList = [
    [
        "doc_type" => "INVOICE",
        "doc_no" => 2025110001,
        "date" => "2025-11-29",
        "customer" => "Hospital Procurement",
        "total" => 4550.00,
        "due_date" => "2025-12-29",
        "status" => "Outstanding",
        "sales_order" => 2023001
    ],
    [
        "doc_type" => "INVOICE",
        "doc_no" => 2025110002,
        "date" => "2025-11-28",
        "customer" => "Ahmad Zulkifli (1001)",
        "total" => 125.50,
        "due_date" => "2025-11-28",
        "status" => "Paid",
        "sales_order" => 2023002
    ],
    [
        "doc_type" => "CREDIT NOTE",
        "doc_no" => 2025110003,
        "date" => "2025-11-27",
        "customer" => "Acme Retail Store",
        "total" => -50.00,
        "due_date" => "N/A",
        "status" => "Processed",
        "sales_order" => 9005 // Associated with a return
    ],
    [
        "doc_type" => "INVOICE",
        "doc_no" => 2025110004,
        "date" => "2025-11-26",
        "customer" => "Chong Wei (1002)",
        "total" => 89.90,
        "due_date" => "2025-12-26",
        "status" => "Outstanding",
        "sales_order" => 2023003
    ],
    [
        "doc_type" => "INVOICE",
        "doc_no" => 2025110005,
        "date" => "2025-11-25",
        "customer" => "Retail POS Sale",
        "total" => 22.00,
        "due_date" => "2025-11-25",
        "status" => "Paid",
        "sales_order" => 2023004
    ],
];

// Helper to determine status badge color
function getStatusBadgeClass($status, $docType) {
    if ($docType === 'CREDIT NOTE') {
        return 'bg-info';
    }
    switch ($status) {
        case 'Paid':
            return 'bg-success';
        case 'Outstanding':
            return 'bg-danger';
        case 'Overdue':
            return 'bg-warning text-dark';
        case 'Processed':
            return 'bg-secondary';
        default:
            return 'bg-secondary';
    }
}

// Helper to format currency color
function getCurrencyColor($amount) {
    return $amount < 0 ? 'text-danger fw-bold' : 'text-success fw-bold';
}

// Helper to determine row highlight
function getRowClass($status) {
    if ($status === 'Outstanding') {
        return 'table-warning';
    }
    return '';
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
                                        <h4 class="card-title">Sales Invoices & Credit Notes</h4>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-info waves-effect waves-light" onclick="createCreditNote()">
                                                <i class="uil uil-minus me-1"></i> New Credit Note
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary waves-effect waves-light" onclick="exportInvoiceList()">
                                                <i class="uil uil-export me-1"></i> Export List
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row mb-3 g-2">
                                        <div class="col-md-3">
                                            <select class="form-select form-select-sm" id="doc-type-filter">
                                                <option value="">All Documents</option>
                                                <option value="INVOICE">Invoices Only</option>
                                                <option value="CREDIT NOTE">Credit Notes Only</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select form-select-sm" id="status-filter">
                                                <option value="">Filter by Payment Status</option>
                                                <option value="Paid">Paid</option>
                                                <option value="Outstanding">Outstanding</option>
                                                <option value="Overdue">Overdue</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 d-flex">
                                            <input type="text" class="form-control form-control-sm" placeholder="Search Inv/CN No. or Customer Name...">
                                            <button class="btn btn-sm btn-secondary ms-2"><i class="uil uil-search"></i></button>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table align-middle table-nowrap table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" style="width: 100px;">Type</th>
                                                    <th scope="col" style="width: 120px;">Document No.</th>
                                                    <th scope="col" style="width: 120px;">Invoice Date</th>
                                                    <th scope="col">Customer</th>
                                                    <th scope="col" class="text-end">Amount (RM)</th>
                                                    <th scope="col" style="width: 120px;">Due Date</th>
                                                    <th scope="col" class="text-center">Status</th>
                                                    <th scope="col" style="width: 150px;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($invoiceList as $doc): ?>
                                                <tr class="<?php echo getRowClass($doc['status']); ?>">
                                                    <td>
                                                        <span class="badge <?php echo ($doc['doc_type'] === 'CREDIT NOTE' ? 'bg-info' : 'bg-primary'); ?>">
                                                            <?php echo $doc['doc_type']; ?>
                                                        </span>
                                                    </td>
                                                    <th scope="row"><?php echo $doc['doc_no']; ?></th>
                                                    <td><?php echo date('d M Y', strtotime($doc['date'])); ?></td>
                                                    <td class="fw-bold"><?php echo htmlspecialchars($doc['customer']); ?></td>
                                                    <td class="text-end <?php echo getCurrencyColor($doc['total']); ?>">
                                                        <?php echo number_format(abs($doc['total']), 2); ?>
                                                    </td>
                                                    <td><?php echo $doc['due_date'] === 'N/A' ? 'N/A' : date('d M Y', strtotime($doc['due_date'])); ?></td>
                                                    <td class="text-center">
                                                        <span class="badge <?php echo getStatusBadgeClass($doc['status'], $doc['doc_type']); ?> font-size-12">
                                                            <?php echo htmlspecialchars($doc['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <button class="btn btn-sm btn-outline-primary" title="View Document"
                                                                onclick="viewDocument('<?php echo $doc['doc_type']; ?>', <?php echo $doc['doc_no']; ?>)">
                                                                <i class="uil uil-file-search-alt"></i>
                                                            </button>
                                                            <?php if ($doc['status'] === 'Outstanding' && $doc['doc_type'] === 'INVOICE'): ?>
                                                            <button class="btn btn-sm btn-outline-success" title="Record Payment"
                                                                onclick="recordPayment(<?php echo $doc['doc_no']; ?>, <?php echo $doc['total']; ?>)">
                                                                <i class="uil uil-receipt"></i> Pay
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
                                        <button class="btn btn-link text-primary" onclick="loadMoreDocuments()">Load More Documents...</button>
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
            console.log('Invoice List Page Loaded.');
        });
        
        function viewDocument(type, no) {
            alert(`Simulated: Opening detailed PDF/view for ${type} #${no}.`);
        }

        function createCreditNote() {
            alert('Simulated: Opening form to create a new Credit Note for a customer return or billing adjustment.');
        }

        function recordPayment(no, amount) {
            alert(`Simulated: Opening payment entry screen to record RM ${amount.toFixed(2)} payment against Invoice #${no}.`);
        }
        
        function exportInvoiceList() {
            alert('Simulated: Exporting current filtered list of Invoices and Credit Notes to CSV/Excel.');
        }
        
        function loadMoreDocuments() {
            alert('Simulated: Loading next page of 50 Invoices/Credit Notes.');
        }
    </script>
</body>

</html>