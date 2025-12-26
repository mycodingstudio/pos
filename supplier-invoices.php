This is the code for `supplier-invoices.php`, which allows users to view, record, and manage all invoices received from suppliers (often referred to as Accounts Payable or AP bills) before they are fully paid.

This page is distinct from the Goods Received Note (GRN) page, which only tracks the physical receipt of goods. This page is for the financial document (invoice) that authorizes payment.

```php
<?php
$pageTitle = "Supplier Invoices & Bills (Accounts Payable)";
include_once 'views/header.php';

// --- MOCK DATA ---
$supplierList = ["Pharma Distributors Sdn Bhd", "Office Supplies Inc.", "Import & Export Trading"];
$statusList = ["Pending Payment", "Partially Paid", "Paid in Full", "Cancelled/Disputed"];

$invoiceList = [
    [
        "id" => 7001,
        "invoice_no" => "INV-PDSB-0050",
        "supplier" => "Pharma Distributors Sdn Bhd",
        "date_received" => "2025-11-20",
        "due_date" => "2025-12-20",
        "total_amount" => 15500.75,
        "amount_due" => 15500.75,
        "status" => "Pending Payment",
        "grn_ref" => "GRN-2025-090"
    ],
    [
        "id" => 7002,
        "invoice_no" => "OS-INV-456",
        "supplier" => "Office Supplies Inc.",
        "date_received" => "2025-11-15",
        "due_date" => "2025-11-30",
        "total_amount" => 450.00,
        "amount_due" => 150.00,
        "status" => "Partially Paid",
        "grn_ref" => "N/A (Expense)"
    ],
    [
        "id" => 7003,
        "invoice_no" => "TRD-NOV-10",
        "supplier" => "Import & Export Trading",
        "date_received" => "2025-11-01",
        "due_date" => "2025-12-01",
        "total_amount" => 8900.00,
        "amount_due" => 0.00,
        "status" => "Paid in Full",
        "grn_ref" => "GRN-2025-080"
    ],
    [
        "id" => 7004,
        "invoice_no" => "INV-PDSB-0049",
        "supplier" => "Pharma Distributors Sdn Bhd",
        "date_received" => "2025-10-25",
        "due_date" => "2025-11-25",
        "total_amount" => 1200.00,
        "amount_due" => 0.00,
        "status" => "Paid in Full",
        "grn_ref" => "GRN-2025-075"
    ],
];

// Helper to determine status badge color
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'Pending Payment':
            return 'bg-danger';
        case 'Partially Paid':
            return 'bg-warning text-dark';
        case 'Paid in Full':
            return 'bg-success';
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
                                        <h4 class="card-title">Manage Supplier Invoices</h4>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-secondary waves-effect waves-light" onclick="exportInvoiceList()">
                                                <i class="uil uil-export me-1"></i> Export List
                                            </button>
                                            <button class="btn btn-sm btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#newInvoiceModal">
                                                <i class="uil uil-plus me-1"></i> Record New Invoice
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row mb-3 g-2">
                                        <div class="col-md-3">
                                            <select class="form-select form-select-sm" id="status-filter">
                                                <option value="">Filter by Status</option>
                                                <?php foreach ($statusList as $status): ?>
                                                    <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
                                                <?php endforeach; ?>
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
                                            <input type="text" class="form-control form-control-sm" placeholder="Search Invoice No. or GRN Ref...">
                                            <button class="btn btn-sm btn-secondary ms-2"><i class="uil uil-search"></i></button>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table align-middle table-nowrap table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" style="width: 150px;">Invoice No.</th>
                                                    <th scope="col">Supplier</th>
                                                    <th scope="col" style="width: 120px;">Date Received</th>
                                                    <th scope="col" style="width: 120px;" class="text-danger">Due Date</th>
                                                    <th scope="col" class="text-end">Total Amount (RM)</th>
                                                    <th scope="col" class="text-end text-danger">Amount Due (RM)</th>
                                                    <th scope="col" class="text-center">Status</th>
                                                    <th scope="col" style="width: 130px;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($invoiceList as $invoice): 
                                                    $amountDueClass = $invoice['amount_due'] > 0 ? 'text-danger fw-bold' : 'text-success';
                                                ?>
                                                <tr>
                                                    <th scope="row"><?php echo htmlspecialchars($invoice['invoice_no']); ?></th>
                                                    <td class="fw-bold"><?php echo htmlspecialchars($invoice['supplier']); ?></td>
                                                    <td><?php echo date('d M Y', strtotime($invoice['date_received'])); ?></td>
                                                    <td class="fw-bold text-danger"><?php echo date('d M Y', strtotime($invoice['due_date'])); ?></td>
                                                    <td class="text-end"><?php echo number_format($invoice['total_amount'], 2); ?></td>
                                                    <td class="text-end <?php echo $amountDueClass; ?>">
                                                        <?php echo number_format($invoice['amount_due'], 2); ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge <?php echo getStatusBadgeClass($invoice['status']); ?> font-size-12">
                                                            <?php echo htmlspecialchars($invoice['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <button class="btn btn-sm btn-outline-primary" title="View Details"
                                                                onclick="viewInvoice(<?php echo $invoice['id']; ?>)">
                                                                <i class="uil uil-file-search-alt"></i> View
                                                            </button>
                                                            <?php if ($invoice['amount_due'] > 0): ?>
                                                            <button class="btn btn-sm btn-success" title="Record Payment"
                                                                onclick="recordPayment(<?php echo $invoice['id']; ?>)">
                                                                <i class="uil uil-money-bill-stack"></i> Pay
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
                                        <button class="btn btn-link text-primary" onclick="loadMoreInvoices()">Load More Invoices...</button>
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
    
    <div class="modal fade" id="newInvoiceModal" tabindex="-1" aria-labelledby="newInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newInvoiceModalLabel"><i class="uil uil-plus me-1"></i> Record New Supplier Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form onsubmit="recordNewInvoice(event)">
                    <div class="modal-body">
                        <p class="text-muted">Enter the details from the physical invoice received from the supplier.</p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="inv-supplier" class="form-label">Supplier</label>
                                <select class="form-select" id="inv-supplier" required>
                                    <option value="" disabled selected>Select a Supplier</option>
                                    <?php foreach ($supplierList as $supplier): ?>
                                        <option value="<?php echo $supplier; ?>"><?php echo $supplier; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="inv-no" class="form-label">Supplier Invoice No.</label>
                                <input type="text" class="form-control" id="inv-no" placeholder="e.g., INV-00123" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="inv-date" class="form-label">Date Received</label>
                                <input type="date" class="form-control" id="inv-date" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="inv-due-date" class="form-label text-danger">Payment Due Date</label>
                                <input type="date" class="form-control" id="inv-due-date" value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="inv-amount" class="form-label">Total Amount (RM)</label>
                                <input type="number" class="form-control" id="inv-amount" step="0.01" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="inv-grn-ref" class="form-label">GRN Reference (Optional)</label>
                            <input type="text" class="form-control" id="inv-grn-ref" placeholder="e.g., GRN-2025-090 or leave blank for non-GRN expense">
                            <small class="form-text text-muted">Link this invoice to a Goods Received Note (GRN) to verify items received.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="inv-upload" class="form-label">Upload Invoice Document (PDF/Image)</label>
                            <input type="file" class="form-control" id="inv-upload" accept=".pdf,image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Record Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php include_once 'views/footer_libraries.php'; ?>
    <script src="assets/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Supplier Invoices Page Loaded.');
        });
        
        function recordNewInvoice(event) {
            event.preventDefault();
            const supplier = document.getElementById('inv-supplier').value;
            const invNo = document.getElementById('inv-no').value;
            const totalAmount = parseFloat(document.getElementById('inv-amount').value).toFixed(2);
            
            // Close the modal
            const modalElement = document.getElementById('newInvoiceModal');
            const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
            modal.hide();

            Swal.fire({
                title: 'Invoice Recorded!',
                html: `Invoice <b>${invNo}</b> from <b>${supplier}</b> (RM ${totalAmount}) has been recorded.<br>Status: <b>Pending Payment</b>`,
                icon: 'success',
                confirmButtonText: 'OK'
            });
            document.getElementById('newInvoiceModal').querySelector('form').reset();
        }

        function viewInvoice(id) {
            alert(`Simulated: Opening detailed view for Invoice #${id}. Shows line items, payment history, and linked documents.`);
        }
        
        function recordPayment(id) {
            Swal.fire({
                title: `Record Payment for Invoice #${id}`,
                input: 'number',
                inputLabel: 'Amount Paid (RM)',
                inputPlaceholder: 'e.g., 15500.75',
                inputAttributes: {
                    min: 0.01,
                    step: 0.01
                },
                showCancelButton: true,
                confirmButtonText: 'Record Payment',
                showLoaderOnConfirm: true,
                preConfirm: (amount) => {
                    if (parseFloat(amount) <= 0 || isNaN(parseFloat(amount))) {
                        Swal.showValidationMessage('Please enter a valid amount greater than zero.');
                        return false;
                    }
                    return amount;
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Payment Recorded!',
                        `RM ${result.value} recorded against Invoice #${id}. Status updated to Partially Paid or Paid in Full.`,
                        'success'
                    );
                }
            });
        }

        function exportInvoiceList() {
            alert('Simulated: Exporting current filtered list of Supplier Invoices to CSV/Excel.');
        }
        
        function loadMoreInvoices() {
            alert('Simulated: Loading next page of 50 Supplier Invoices.');
        }
    </script>
</body>

</html>
```