<?php
$pageTitle = "Inventory Stock Transactions";
include_once 'views/header.php';

// --- MOCK GLOBAL DATA ---
$locations = ['Main Store A', 'Main Store B', 'Warehouse 1', 'Retail Floor', 'Counter 1', 'Counter 2'];
$suppliers = ['Supplier A Trading', 'Wholesale B Sdn Bhd', 'Manufacturer C Co'];
$adjustmentReasons = ['Cycle Count Discrepancy', 'Damaged Item (Salvage)', 'System Error Correction', 'New Stock Opening'];
$issueReasons = ['Internal Consumption (Office)', 'Marketing Samples', 'Transfer to Kiosk'];

// --- INITIAL DUMMY DATA FOR RECEIVE TAB ---
$initialReceiveLines = [
    [
        "sku" => "SKU-0015", 
        "item_name" => "Vitamin C 1000mg (Bottle)", 
        "qty" => 150, 
        "batch_no" => "VC202506A", 
        "expiry_date" => "2025-06-30", 
        "unit_cost" => 12.50
    ],
    [
        "sku" => "SKU-0042", 
        "item_name" => "Hand Sanitizer Gel (500ml)", 
        "qty" => 200, 
        "batch_no" => "HGS2311", 
        "expiry_date" => "-", 
        "unit_cost" => 7.80
    ],
    [
        "sku" => "SKU-0088", 
        "item_name" => "Digital Thermometer Deluxe", 
        "qty" => 50, 
        "batch_no" => "DTM2309", 
        "expiry_date" => "-", 
        "unit_cost" => 45.00
    ],
];
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
                                    <h4 class="card-title mb-4">Stock Movement Management</h4>
                                    <p class="card-title-desc">Execute stock-in, stock-out, and internal transfers/adjustments.</p>

                                    <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#tab-receive" role="tab">
                                                <i class="uil uil-arrow-to-bottom me-1"></i> Stock Receive (IN)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#tab-issue" role="tab">
                                                <i class="uil uil-arrow-from-top me-1"></i> Stock Issue (OUT)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#tab-adjustment" role="tab">
                                                <i class="uil uil-exchange-alt me-1"></i> Stock Adjustment
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#tab-writeoff" role="tab">
                                                <i class="uil uil-trash-alt me-1"></i> Stock Write-Off
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#tab-transfer" role="tab">
                                                <i class="uil uil-truck me-1"></i> Stock Transfer
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content pt-3">
                                        
                                        <div class="tab-pane active" id="tab-receive" role="tabpanel">
                                            <h5 class="font-size-15 mb-4">Record Stock In from Supplier or Production</h5>
                                            <form action="#" method="post" id="form-receive">
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="receive-ref" class="form-label">GRN/Invoice No.</label>
                                                        <input type="text" class="form-control" id="receive-ref" placeholder="GRN-202311001" value="INV-SUPA20231115" required>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="receive-supplier" class="form-label">Supplier</label>
                                                        <select class="form-select" id="receive-supplier" required>
                                                            <option value="">Select Supplier</option>
                                                            <?php foreach ($suppliers as $supplier): ?>
                                                                <option value="<?php echo $supplier; ?>" <?php echo ($supplier == 'Supplier A Trading' ? 'selected' : ''); ?>><?php echo $supplier; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="receive-location" class="form-label">Receiving Location</label>
                                                        <select class="form-select" id="receive-location" required>
                                                            <option value="">Select Location</option>
                                                            <?php foreach ($locations as $loc): ?>
                                                                <option value="<?php echo $loc; ?>" <?php echo ($loc == 'Warehouse 1' ? 'selected' : ''); ?>><?php echo $loc; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <hr>
                                                <h6 class="mb-3">Add New Item to Receive</h6>
                                                <div class="row g-3 align-items-end">
                                                    <div class="col-md-3">
                                                        <label for="receive-sku" class="form-label">Item SKU / Name</label>
                                                        <input type="text" class="form-control" id="receive-sku" placeholder="Start typing SKU or name...">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="receive-qty" class="form-label">Quantity</label>
                                                        <input type="number" class="form-control" id="receive-qty" min="1" placeholder="0" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="receive-batch" class="form-label">Batch No.</label>
                                                        <input type="text" class="form-control" id="receive-batch" placeholder="BN01234">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="receive-expiry" class="form-label">Expiry Date</label>
                                                        <input type="date" class="form-control" id="receive-expiry">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="receive-cost" class="form-label">Unit Cost</label>
                                                        <input type="number" step="0.01" class="form-control" id="receive-cost" placeholder="0.00">
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-success w-100" onclick="addItemToReceiveTable()"><i class="uil uil-plus"></i></button>
                                                    </div>
                                                </div>

                                                <h6 class="mt-4">Items to Receive:</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered mt-2">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Item Name</th>
                                                                <th>Qty</th>
                                                                <th>Batch No</th>
                                                                <th>Expiry Date</th>
                                                                <th>Unit Cost</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="receive-lines">
                                                            <?php 
                                                            $receiveItemCount = 0;
                                                            foreach ($initialReceiveLines as $item): 
                                                                $receiveItemCount++;
                                                            ?>
                                                            <tr id="receive-row-<?php echo $receiveItemCount; ?>">
                                                                <td><?php echo $item['item_name']; ?> (<?php echo $item['sku']; ?>)</td>
                                                                <td><?php echo $item['qty']; ?></td>
                                                                <td><?php echo $item['batch_no']; ?></td>
                                                                <td><?php echo $item['expiry_date']; ?></td>
                                                                <td>RM <?php echo number_format($item['unit_cost'], 2); ?></td>
                                                                <td><button type="button" class="btn btn-sm btn-danger" onclick="document.getElementById('receive-row-<?php echo $receiveItemCount; ?>').remove()">Remove</button></td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="text-end mt-4">
                                                    <button type="submit" class="btn btn-primary w-lg">Finalize Stock Receive</button>
                                                </div>
                                            </form>
                                        </div>
                                        
                                        <div class="tab-pane" id="tab-issue" role="tabpanel">
                                            <h5 class="font-size-15 mb-4">Record Stock Out for Non-Sales Purposes</h5>
                                            <form action="#" method="post" id="form-issue">
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="issue-ref" class="form-label">Issue Reference No.</label>
                                                        <input type="text" class="form-control" id="issue-ref" placeholder="ISS-202311001" value="ISS-OFFICE-SUPP" required>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="issue-reason" class="form-label">Reason for Issue</label>
                                                        <select class="form-select" id="issue-reason" required>
                                                            <option value="">Select Reason</option>
                                                            <?php foreach ($issueReasons as $reason): ?>
                                                                <option value="<?php echo $reason; ?>" <?php echo ($reason == 'Internal Consumption (Office)' ? 'selected' : ''); ?>><?php echo $reason; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="issue-location" class="form-label">Issuing Location</label>
                                                        <select class="form-select" id="issue-location" required>
                                                            <option value="">Select Location</option>
                                                            <?php foreach ($locations as $loc): ?>
                                                                <option value="<?php echo $loc; ?>" <?php echo ($loc == 'Main Store A' ? 'selected' : ''); ?>><?php echo $loc; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="text-end mt-4">
                                                    <button type="submit" class="btn btn-primary w-lg">Process Stock Issue</button>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="tab-pane" id="tab-adjustment" role="tabpanel">
                                            <h5 class="font-size-15 mb-4">Change Stock Quantity (+ or -) without Movement</h5>
                                            <form action="#" method="post" id="form-adjustment">
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="adj-location" class="form-label">Location</label>
                                                        <select class="form-select" id="adj-location" required>
                                                            <option value="">Select Location</option>
                                                            <?php foreach ($locations as $loc): ?>
                                                                <option value="<?php echo $loc; ?>"><?php echo $loc; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="adj-sku" class="form-label">Item SKU / Name</label>
                                                        <input type="text" class="form-control" id="adj-sku" placeholder="Start typing SKU or name..." value="SKU-0010 (Paracetamol 500mg)" required>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="adj-batch" class="form-label">Batch No. (Optional)</label>
                                                        <input type="text" class="form-control" id="adj-batch" placeholder="Specify batch if needed" value="PC202403">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="adj-qty" class="form-label">Adjustment Quantity (+/-)</label>
                                                        <input type="number" class="form-control" id="adj-qty" placeholder="+10 or -5" value="-3" required>
                                                        <div class="form-text">Use positive value for increase, negative for decrease.</div>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="adj-reason" class="form-label">Adjustment Reason</label>
                                                        <select class="form-select" id="adj-reason" required>
                                                            <option value="">Select Reason</option>
                                                            <?php foreach ($adjustmentReasons as $reason): ?>
                                                                <option value="<?php echo $reason; ?>" <?php echo ($reason == 'Cycle Count Discrepancy' ? 'selected' : ''); ?>><?php echo $reason; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="adj-notes" class="form-label">Notes</label>
                                                        <input type="text" class="form-control" id="adj-notes" placeholder="Reference physical count sheet #123" value="Found 3 missing items during morning count.">
                                                    </div>
                                                </div>
                                                
                                                <div class="text-end mt-4">
                                                    <button type="submit" class="btn btn-primary w-lg">Execute Stock Adjustment</button>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="tab-pane" id="tab-writeoff" role="tabpanel">
                                            <h5 class="font-size-15 mb-4">Permanently Remove Damaged or Expired Stock (Write-Off)</h5>
                                            <form action="#" method="post" id="form-writeoff">
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="wo-location" class="form-label">Location</label>
                                                        <select class="form-select" id="wo-location" required>
                                                            <option value="">Select Location</option>
                                                            <?php foreach ($locations as $loc): ?>
                                                                <option value="<?php echo $loc; ?>" <?php echo ($loc == 'Retail Floor' ? 'selected' : ''); ?>><?php echo $loc; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="wo-sku" class="form-label">Item SKU / Name</label>
                                                        <input type="text" class="form-control" id="wo-sku" placeholder="Start typing SKU or name..." value="SKU-0077 (Protein Powder Vanilla)" required>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="wo-qty" class="form-label">Quantity to Write-Off</label>
                                                        <input type="number" class="form-control" id="wo-qty" min="1" placeholder="0" value="1" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="wo-reason" class="form-label">Write-Off Reason</label>
                                                        <select class="form-select" id="wo-reason" required>
                                                            <option value="">Select Reason</option>
                                                            <option value="EXPIRED">Expired</option>
                                                            <option value="DAMAGED" selected>Damaged/Spoiled</option>
                                                            <option value="LOST">Lost/Missing</option>
                                                            <option value="OTHERS">Other (Specify in notes)</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-8 mb-3">
                                                        <label for="wo-notes" class="form-label">Authorization/Notes</label>
                                                        <input type="text" class="form-control" id="wo-notes" placeholder="Authorized by Supervisor Siti." value="Container seal broken on shelf.">
                                                    </div>
                                                </div>
                                                
                                                <div class="text-end mt-4">
                                                    <button type="submit" class="btn btn-danger w-lg">Confirm Write-Off</button>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="tab-pane" id="tab-transfer" role="tabpanel">
                                            <h5 class="font-size-15 mb-4">Move Stock Between Locations</h5>
                                            <form action="#" method="post" id="form-transfer">
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="transfer-ref" class="form-label">Transfer Reference No.</label>
                                                        <input type="text" class="form-control" id="transfer-ref" placeholder="TRF-202311001" value="TRF-C1-MSB-2023" required>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="transfer-from" class="form-label">Transfer FROM Location</label>
                                                        <select class="form-select" id="transfer-from" required>
                                                            <option value="">Select Source Location</option>
                                                            <?php foreach ($locations as $loc): ?>
                                                                <option value="<?php echo $loc; ?>" <?php echo ($loc == 'Counter 1' ? 'selected' : ''); ?>><?php echo $loc; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="transfer-to" class="form-label">Transfer TO Location</label>
                                                        <select class="form-select" id="transfer-to" required>
                                                            <option value="">Select Destination Location</option>
                                                            <?php foreach ($locations as $loc): ?>
                                                                <option value="<?php echo $loc; ?>" <?php echo ($loc == 'Main Store B' ? 'selected' : ''); ?>><?php echo $loc; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="transfer-sku" class="form-label">Item SKU / Name</label>
                                                        <input type="text" class="form-control" id="transfer-sku" placeholder="Start typing SKU or name..." value="SKU-0099 (Cough Suppressant)" required>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="transfer-qty" class="form-label">Quantity to Transfer</label>
                                                        <input type="number" class="form-control" id="transfer-qty" min="1" placeholder="0" value="12" required>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="transfer-notes" class="form-label">Notes</label>
                                                        <input type="text" class="form-control" id="transfer-notes" placeholder="Urgent transfer for POS counter restock" value="Counter 1 has excess stock.">
                                                    </div>
                                                </div>
                                                
                                                <div class="text-end mt-4">
                                                    <button type="submit" class="btn btn-primary w-lg">Initiate Stock Transfer</button>
                                                </div>
                                            </form>
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
        let receiveItemCount = <?php echo $receiveItemCount; ?>; // Initialize counter from PHP data

        // Mock function for adding an item line (for Stock Receive Tab)
        function addItemToReceiveTable() {
            const sku = document.getElementById('receive-sku').value;
            const qty = document.getElementById('receive-qty').value;
            const batch = document.getElementById('receive-batch').value;
            const expiry = document.getElementById('receive-expiry').value;
            const cost = document.getElementById('receive-cost').value;
            
            if (!sku || !qty) {
                alert('Please enter Item SKU/Name and Quantity.');
                return;
            }

            receiveItemCount++;
            const tbody = document.getElementById('receive-lines');
            const newRow = tbody.insertRow();
            newRow.id = `receive-row-${receiveItemCount}`;
            newRow.innerHTML = `
                <td>${sku}</td>
                <td>${qty}</td>
                <td>${batch || '-'}</td>
                <td>${expiry || '-'}</td>
                <td>RM ${parseFloat(cost).toFixed(2) || '0.00'}</td>
                <td><button type="button" class="btn btn-sm btn-danger" onclick="document.getElementById('receive-row-${receiveItemCount}').remove()">Remove</button></td>
            `;

            // Clear input fields for the next item
            document.getElementById('receive-sku').value = '';
            document.getElementById('receive-qty').value = '';
            document.getElementById('receive-batch').value = '';
            document.getElementById('receive-expiry').value = '';
            document.getElementById('receive-cost').value = '';
            document.getElementById('receive-sku').focus();
        }

        // Mock form submission handlers
        document.getElementById('form-receive').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Stock Receive Transaction Simulated successfully!');
        });
        document.getElementById('form-issue').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Stock Issue Transaction Simulated successfully!');
        });
        document.getElementById('form-adjustment').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Stock Adjustment Transaction Simulated successfully!');
        });
        document.getElementById('form-writeoff').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Stock Write-Off Transaction Simulated successfully!');
        });
        document.getElementById('form-transfer').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Stock Transfer Transaction Simulated successfully!');
        });
    </script>
</body>

</html>