<?php
$pageTitle = "Sales Reports & Analysis";
include_once 'views/header.php';

// --- MOCK GLOBAL DATA ---
$salesPeople = ['Siti Aminah', 'Ah Meng', 'Ali Bin Abu', 'Devi A/P Mohan'];
$categories = ['Pharmaceuticals', 'OTC Medicine', 'Health Supplements', 'Personal Care'];
$reportPeriods = ['Today', 'Yesterday', 'Last 7 Days', 'Last 30 Days', 'Custom Range'];

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
                                    <h4 class="card-title mb-4"><i class="uil uil-chart-bar me-2 text-primary"></i> Daily/Periodic Sales Summary</h4>
                                    <p class="card-title-desc">Total Sales, Cost, and Gross Profit for a given period.</p>
                                    
                                    <form id="report-sales-summary" onsubmit="generateReport(event, 'Sales Summary', ['Period', 'Salesperson'])">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="summary-period" class="form-label">Time Period</label>
                                                <select class="form-select" id="summary-period">
                                                    <?php foreach ($reportPeriods as $period): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $period); ?>" <?php echo ($period == 'Last 7 Days' ? 'selected' : ''); ?>><?php echo $period; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="summary-salesperson" class="form-label">Salesperson Filter</label>
                                                <select class="form-select" id="summary-salesperson">
                                                    <option value="All">All Salespersons</option>
                                                    <?php foreach ($salesPeople as $sp): ?>
                                                        <option value="<?php echo $sp; ?>"><?php echo $sp; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
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
                                    <h4 class="card-title mb-4"><i class="uil uil-star me-2 text-success"></i> Top Selling Items & Profitability</h4>
                                    <p class="card-title-desc">Ranks products by sales value, quantity, or gross profit.</p>
                                    
                                    <form id="report-best-seller" onsubmit="generateReport(event, 'Best Seller & Profitability', ['Period', 'Category', 'Metric'])">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="seller-period" class="form-label">Time Period</label>
                                                <select class="form-select" id="seller-period">
                                                    <?php foreach ($reportPeriods as $period): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $period); ?>"><?php echo $period; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="seller-category" class="form-label">Category Filter</label>
                                                <select class="form-select" id="seller-category">
                                                    <option value="All">All Categories</option>
                                                    <?php foreach ($categories as $cat): ?>
                                                        <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="seller-metric" class="form-label">Rank By</label>
                                                <select class="form-select" id="seller-metric">
                                                    <option value="Quantity">Quantity Sold</option>
                                                    <option value="Revenue" selected>Total Revenue</option>
                                                    <option value="Profit">Gross Profit</option>
                                                </select>
                                            </div>
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
                                    <h4 class="card-title mb-4"><i class="uil uil-users-alt me-2 text-info"></i> Customer Performance & Loyalty Analysis</h4>
                                    <p class="card-title-desc">Identifies top spending customers and tracks new/active members.</p>
                                    
                                    <form id="report-customer-perf" onsubmit="generateReport(event, 'Customer Performance', ['Period', 'Metric', 'Group'])">
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label for="customer-period" class="form-label">Time Period</label>
                                                <select class="form-select" id="customer-period">
                                                    <?php foreach ($reportPeriods as $period): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $period); ?>" <?php echo ($period == 'Last 90 Days' ? 'selected' : ''); ?>><?php echo $period; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="customer-metric" class="form-label">Report Type</label>
                                                <select class="form-select" id="customer-metric">
                                                    <option value="Top Spenders" selected>Top Spenders (Revenue)</option>
                                                    <option value="New Members">New Members Signed Up</option>
                                                    <option value="Points Balance">Customer Points Balance</option>
                                                    <option value="Customer Lifetime Value">Customer Lifetime Value</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="customer-group" class="form-label">Filter by Member Group</label>
                                                <select class="form-select" id="customer-group">
                                                    <option value="All">All Groups</option>
                                                    <option value="Gold">Gold Tier</option>
                                                    <option value="Silver">Silver Tier</option>
                                                    <option value="General">General Member</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                                <button type="submit" class="btn btn-info w-100">Generate Report</button>
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
                                    <h4 class="card-title mb-4"><i class="uil uil-money-bill me-2 text-warning"></i> Tax & Revenue Report</h4>
                                    <p class="card-title-desc">Summary of collected taxes (GST/SST) for accounting reconciliation.</p>
                                    
                                    <form id="report-tax" onsubmit="generateReport(event, 'Tax & Revenue', ['Period', 'Tax Rate'])">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="tax-period" class="form-label">Time Period</label>
                                                <select class="form-select" id="tax-period">
                                                    <?php foreach ($reportPeriods as $period): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $period); ?>" <?php echo ($period == 'Last Month' ? 'selected' : ''); ?>><?php echo $period; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="tax-rate" class="form-label">Tax Rate / Code</label>
                                                <select class="form-select" id="tax-rate">
                                                    <option value="All">All Tax Codes</option>
                                                    <option value="SST-6%" selected>SST 6%</option>
                                                    <option value="Exempt">Tax Exempt</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-warning w-lg">Generate Report</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><i class="uil uil-trophy me-2 text-danger"></i> Sales Commission Report</h4>
                                    <p class="card-title-desc">Calculates commission earned by each salesperson based on sales or profit.</p>
                                    
                                    <form id="report-commission" onsubmit="generateReport(event, 'Sales Commission', ['Period', 'Salesperson', 'Basis'])">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="commission-period" class="form-label">Time Period</label>
                                                <select class="form-select" id="commission-period">
                                                    <?php foreach ($reportPeriods as $period): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $period); ?>" <?php echo ($period == 'Current Month' ? 'selected' : ''); ?>><?php echo $period; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="commission-salesperson" class="form-label">Salesperson</label>
                                                <select class="form-select" id="commission-salesperson">
                                                    <option value="All">All Salespersons</option>
                                                    <?php foreach ($salesPeople as $sp): ?>
                                                        <option value="<?php echo $sp; ?>"><?php echo $sp; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="commission-basis" class="form-label">Calculation Basis</label>
                                                <select class="form-select" id="commission-basis">
                                                    <option value="Revenue">Total Revenue</option>
                                                    <option value="Profit" selected>Gross Profit</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-danger w-lg">Generate Report</button>
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
            console.log('Sales Reports & Analysis Page Loaded.');
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
                
                filterDetails += `- ${param}: ${value}\n`;
            });

            alert(`--- Generating Report ---\n\n${filterDetails}\n(Simulated: The actual report data table or chart would be loaded here.)`);
        }
    </script>
</body>

</html>