<?php
$pageTitle = "POS & Cashier Reports";
include_once 'views/header.php';

// --- MOCK GLOBAL DATA ---
$cashierList = ['Ali Bin Abu', 'Siti Aminah', 'Ah Meng', 'All Cashiers'];
$posCounters = ['Counter 1 (Prescription)', 'Counter 2 (OTC/Express)', 'Counter 3 (Supervisor)', 'All Counters'];
$reportPeriods = ['Today', 'Yesterday', 'Last 7 Days', 'Last 30 Days', 'Current Month', 'Last Month', 'Custom Range'];

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
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><i class="uil uil-user-check me-2 text-primary"></i> Cashier Performance & Sales Summary</h4>
                                    <p class="card-title-desc">Tracks sales volume and total collections per cashier for a specified period.</p>
                                    
                                    <form id="report-cashier-sales" onsubmit="generateReport(event, 'Cashier Sales Performance', ['Period', 'Cashier', 'POS'])">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="cashier-period" class="form-label">Time Period</label>
                                                <select class="form-select" id="cashier-period">
                                                    <?php foreach ($reportPeriods as $period): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $period); ?>" <?php echo ($period == 'Last 7 Days' ? 'selected' : ''); ?>><?php echo $period; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="cashier-select" class="form-label">Filter by Cashier</label>
                                                <select class="form-select" id="cashier-select">
                                                    <?php foreach ($cashierList as $cashier): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $cashier); ?>"><?php echo $cashier; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="cashier-pos" class="form-label">Filter by POS Counter</label>
                                            <select class="form-select" id="cashier-pos">
                                                <?php foreach ($posCounters as $counter): ?>
                                                    <option value="<?php echo str_replace(' ', '-', $counter); ?>"><?php echo $counter; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary w-lg">Generate Report</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><i class="uil uil-money-bill-stack me-2 text-success"></i> Cash Collection & Variance Report</h4>
                                    <p class="card-title-desc">Reports on expected vs. actual cash collected and any detected shortages or overages.</p>
                                    
                                    <form id="report-cash-collection" onsubmit="generateReport(event, 'Cash Collection Variance', ['Date', 'POS', 'Status'])">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="collection-date" class="form-label">Transaction Date</label>
                                                <input type="date" class="form-control" id="collection-date" value="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="collection-pos" class="form-label">POS Counter</label>
                                                <select class="form-select" id="collection-pos">
                                                    <?php foreach ($posCounters as $counter): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $counter); ?>"><?php echo $counter; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="collection-status" class="form-label">Filter by Variance Status</label>
                                            <select class="form-select" id="collection-status">
                                                <option value="All">All Transactions</option>
                                                <option value="Shortage">Only Shortage/Under</option>
                                                <option value="Overage">Only Overage/Over</option>
                                                <option value="Balanced" selected>Only Balanced</option>
                                            </select>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success w-lg">Generate Report</button>
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
                                    <h4 class="card-title mb-4"><i class="uil uil-credit-card-search me-2 text-warning"></i> Sales & Collection by Payment Method</h4>
                                    <p class="card-title-desc">Detailed breakdown of sales value collected via Cash, Credit Card, E-Wallet, etc.</p>
                                    
                                    <form id="report-payment-method" onsubmit="generateReport(event, 'Sales by Payment Method', ['Period', 'POS'])">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="payment-period" class="form-label">Time Period</label>
                                                <select class="form-select" id="payment-period">
                                                    <?php foreach ($reportPeriods as $period): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $period); ?>" <?php echo ($period == 'Last 30 Days' ? 'selected' : ''); ?>><?php echo $period; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="payment-pos" class="form-label">Filter by POS Counter</label>
                                                <select class="form-select" id="payment-pos">
                                                    <?php foreach ($posCounters as $counter): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $counter); ?>"><?php echo $counter; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                                <button type="submit" class="btn btn-warning w-100">Generate Report</button>
                                            </div>
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
                                    <h4 class="card-title mb-4"><i class="uil uil-minus-circle me-2 text-danger"></i> Voided & Returned Transactions</h4>
                                    <p class="card-title-desc">List and summarize high-value transactions that were voided or returned by staff.</p>
                                    
                                    <form id="report-voided-returns" onsubmit="generateReport(event, 'Void/Return Transactions', ['Period', 'Type', 'Threshold'])">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="void-period" class="form-label">Transaction Period</label>
                                                <select class="form-select" id="void-period">
                                                    <?php foreach ($reportPeriods as $period): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $period); ?>" <?php echo ($period == 'Last 7 Days' ? 'selected' : ''); ?>><?php echo $period; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="void-type" class="form-label">Transaction Type</label>
                                                <select class="form-select" id="void-type">
                                                    <option value="All">Voided & Returned</option>
                                                    <option value="Voided">Voided Transactions Only</option>
                                                    <option value="Returned">Returned Goods Only</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                                <button type="submit" class="btn btn-danger w-100">Generate Report</button>
                                            </div>
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
            console.log('POS & Cashier Reports Page Loaded.');
        });
        
        // Generic mock function to simulate generating a report
        function generateReport(event, reportName, parameters) {
            event.preventDefault();
            
            let filterDetails = `Report: ${reportName}\n\nFilters:\n`;
            
            // Collect filter values dynamically based on the form parameters
            parameters.forEach(param => {
                let elementId = event.target.id.replace('report-', '') + '-' + param.toLowerCase().replace(/ /g, '-');
                let element = document.getElementById(elementId);
                let value = element ? element.value : 'N/A';
                
                // Specific ID mappings for consistency
                if (param === 'Cashier' && elementId === 'report-cashier-sales-cashier') {
                    elementId = 'cashier-select';
                    element = document.getElementById(elementId);
                    value = element ? element.value : 'N/A';
                }
                
                filterDetails += `- ${param}: ${value}\n`;
            });

            alert(`--- Generating Report ---\n\n${filterDetails}\n(Simulated: The actual report data table, list, or chart would be loaded here.)`);
        }
    </script>
</body>

</html>