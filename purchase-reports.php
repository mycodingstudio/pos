<?php
$pageTitle = "Purchase & Procurement Reports";
include_once 'views/header.php';

// --- MOCK GLOBAL DATA ---
$supplierList = ['Pharma Distributors', 'Health Devices (M) Corp', 'Discount OTC Wholesaler', 'All Suppliers'];
$buyersList = ['Ali Bin Abu', 'Siti Aminah', 'Ah Meng', 'All Buyers'];
$reportPeriods = ['Last 7 Days', 'Last 30 Days', 'Last 90 Days', 'Current Month', 'Last Month', 'Custom Range'];

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
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><i class="uil uil-shopping-basket me-2 text-danger"></i> Purchase Order & Cost Summary</h4>
                                    <p class="card-title-desc">Total spending on purchase orders and received goods (GRN) for a period.</p>
                                    
                                    <form id="report-purchase-summary" onsubmit="generateReport(event, 'Purchase Cost Summary', ['Period', 'Buyer', 'DocumentType'])">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="summary-period" class="form-label">Time Period</label>
                                                <select class="form-select" id="summary-period">
                                                    <?php foreach ($reportPeriods as $period): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $period); ?>" <?php echo ($period == 'Last 30 Days' ? 'selected' : ''); ?>><?php echo $period; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="summary-buyer" class="form-label">Buyer Filter</label>
                                                <select class="form-select" id="summary-buyer">
                                                    <?php foreach ($buyersList as $buyer): ?>
                                                        <option value="<?php echo $buyer; ?>"><?php echo $buyer; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="summary-doctype" class="form-label">Document Type</label>
                                            <select class="form-select" id="summary-doctype">
                                                <option value="PO">Purchase Order Value</option>
                                                <option value="GRN" selected>Goods Received Value (GRN)</option>
                                            </select>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-danger w-lg">Generate Report</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><i class="uil uil-truck me-2 text-primary"></i> Supplier Performance & Ranking</h4>
                                    <p class="card-title-desc">Ranks suppliers by total purchase value and tracks delivery timeliness.</p>
                                    
                                    <form id="report-supplier-perf" onsubmit="generateReport(event, 'Supplier Performance', ['Period', 'Metric'])">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="supplier-period" class="form-label">Review Period</label>
                                                <select class="form-select" id="supplier-period">
                                                    <?php foreach ($reportPeriods as $period): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $period); ?>" <?php echo ($period == 'Last 90 Days' ? 'selected' : ''); ?>><?php echo $period; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="supplier-metric" class="form-label">Rank By Metric</label>
                                                <select class="form-select" id="supplier-metric">
                                                    <option value="TotalValue" selected>Total Purchase Value</option>
                                                    <option value="POVolume">Total PO Volume</option>
                                                    <option value="LeadTime">Avg. Delivery Lead Time</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="supplier-status-po" class="form-label">PO Status to Include</label>
                                            <select class="form-select" id="supplier-status-po">
                                                <option value="Completed">Completed POs Only</option>
                                                <option value="All">All POs (Including Pending/Partial)</option>
                                            </select>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary w-lg">Generate Report</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><i class="uil uil-clipboard-notes me-2 text-warning"></i> Open Purchase Orders & Pending GRNs</h4>
                                    <p class="card-title-desc">List of outstanding POs that are awaiting approval or waiting for goods to be received.</p>
                                    
                                    <form id="report-open-po" onsubmit="generateReport(event, 'Open PO & Pending GRN List', ['Supplier', 'Status'])">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="open-po-supplier" class="form-label">Filter by Supplier</label>
                                                <select class="form-select" id="open-po-supplier">
                                                    <?php foreach ($supplierList as $supplier): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $supplier); ?>"><?php echo $supplier; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="open-po-status" class="form-label">Current Status</label>
                                                <select class="form-select" id="open-po-status">
                                                    <option value="All" selected>All Open Statuses</option>
                                                    <option value="AwaitingApproval">Awaiting Approval</option>
                                                    <option value="PendingGRN">Pending GRN</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                                <button type="submit" class="btn btn-warning w-100">Generate List</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><i class="uil uil-repeat me-2 text-success"></i> Inventory Reorder Recommendation</h4>
                                    <p class="card-title-desc">Generates a list of items that have hit their reorder level based on current stock.</p>
                                    
                                    <form id="report-reorder" onsubmit="generateReport(event, 'Reorder Recommendation', ['Location', 'Supplier'])">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="reorder-location" class="form-label">Stock Location</label>
                                                <select class="form-select" id="reorder-location">
                                                    <option value="All">All Locations</option>
                                                    <option value="Warehouse">Main Warehouse</option>
                                                    <option value="StoreA">Store A</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="reorder-supplier" class="form-label">Filter by Primary Supplier</label>
                                                <select class="form-select" id="reorder-supplier">
                                                    <?php foreach ($supplierList as $supplier): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $supplier); ?>"><?php echo $supplier; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success w-lg">Generate Recommendation</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><i class="uil uil-money-stack me-2 text-info"></i> Purchase Price Variance (PPV)</h4>
                                    <p class="card-title-desc">Compares last purchase price to standard cost for variance analysis.</p>
                                    
                                    <form id="report-ppv" onsubmit="generateReport(event, 'Purchase Price Variance', ['Period', 'VarianceThreshold'])">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="ppv-period" class="form-label">Review Period</label>
                                                <select class="form-select" id="ppv-period">
                                                    <?php foreach ($reportPeriods as $period): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $period); ?>" <?php echo ($period == 'Current Month' ? 'selected' : ''); ?>><?php echo $period; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="ppv-threshold" class="form-label">Variance Threshold (%)</label>
                                                <select class="form-select" id="ppv-threshold">
                                                    <option value="5">Above 5%</option>
                                                    <option value="10" selected>Above 10%</option>
                                                    <option value="20">Above 20%</option>
                                                </select>
                                                <div class="form-text">Show items where purchase price differs from standard cost significantly.</div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-info w-lg">Generate Report</button>
                                        </div>
                                    </form>
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
            console.log('Purchase & Procurement Reports Page Loaded.');
        });
        
        // Generic mock function to simulate generating a report
        function generateReport(event, reportName, parameters) {
            event.preventDefault();
            
            let filterDetails = `Report: ${reportName}\n\nFilters:\n`;
            
            // Collect filter values dynamically based on the form parameters
            parameters.forEach(param => {
                let elementId = event.target.id.replace('report-', '') + '-' + param.toLowerCase().replace(/ /g, '-').replace('po&pending', 'open-po').replace('doctype', 'doctype').replace('metric', 'metric').replace('supplier', 'supplier').replace('buyer', 'buyer');
                let element = document.getElementById(elementId);
                let value = element ? element.value : 'N/A';
                
                // Special handling for elements that may not exist in all forms
                if (element === null && param === 'DocumentType') {
                    element = document.getElementById('summary-doctype');
                    value = element ? element.value : 'N/A';
                }
                
                filterDetails += `- ${param}: ${value}\n`;
            });

            alert(`--- Generating Report ---\n\n${filterDetails}\n(Simulated: The actual report data table, list, or chart would be loaded here.)`);
        }
    </script>
</body>

</html>