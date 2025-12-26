<?php
$pageTitle = "Inventory Reports";
include_once 'views/header.php';

// --- MOCK GLOBAL DATA ---
$locations = ['Main Store A', 'Main Store B', 'Warehouse 1', 'Retail Floor', 'Counter 1', 'Counter 2'];
$categories = ['Pharmaceuticals', 'OTC Medicine', 'Health Supplements', 'Personal Care', 'Medical Devices'];
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Inventory</a></li>
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
                                    <h4 class="card-title mb-4"><i class="uil uil-exclamation-octagon me-2 text-warning"></i> Low Stock & Reorder Report</h4>
                                    <p class="card-title-desc">Identifies items that are currently at or below their minimum reorder level.</p>
                                    
                                    <form id="report-low-stock" onsubmit="generateReport(event, 'Low Stock & Reorder', ['Location', 'Category'])">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="low-stock-location" class="form-label">Location Filter</label>
                                                <select class="form-select" id="low-stock-location">
                                                    <option value="All">All Locations</option>
                                                    <?php foreach ($locations as $loc): ?>
                                                        <option value="<?php echo $loc; ?>"><?php echo $loc; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="low-stock-category" class="form-label">Category Filter</label>
                                                <select class="form-select" id="low-stock-category">
                                                    <option value="All">All Categories</option>
                                                    <?php foreach ($categories as $cat): ?>
                                                        <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
                                                    <?php endforeach; ?>
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
                                    <h4 class="card-title mb-4"><i class="uil uil-history me-2 text-danger"></i> Expiry Tracking Report</h4>
                                    <p class="card-title-desc">Lists batches expiring within a specified timeframe for proactive action.</p>
                                    
                                    <form id="report-expiry" onsubmit="generateReport(event, 'Expiry Tracking', ['Location', 'Days Ahead'])">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="expiry-location" class="form-label">Location Filter</label>
                                                <select class="form-select" id="expiry-location">
                                                    <option value="All">All Locations</option>
                                                    <?php foreach ($locations as $loc): ?>
                                                        <option value="<?php echo $loc; ?>"><?php echo $loc; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="expiry-days" class="form-label">Expiring Within (Days)</label>
                                                <select class="form-select" id="expiry-days">
                                                    <option value="30" selected>30 Days</option>
                                                    <option value="60">60 Days</option>
                                                    <option value="90">90 Days</option>
                                                    <option value="180">180 Days</option>
                                                    <option value="Expired">Already Expired</option>
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
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><i class="uil uil-chart-line me-2 text-primary"></i> Stock Movement Summary Report</h4>
                                    <p class="card-title-desc">Summarizes total stock-in, stock-out, and net changes over a period.</p>
                                    
                                    <form id="report-movement" onsubmit="generateReport(event, 'Stock Movement Summary', ['Period', 'Category', 'Location'])">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="movement-period" class="form-label">Time Period</label>
                                                <select class="form-select" id="movement-period">
                                                    <?php foreach ($reportPeriods as $period): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $period); ?>" <?php echo ($period == 'Last 30 Days' ? 'selected' : ''); ?>><?php echo $period; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="movement-category" class="form-label">Category Filter</label>
                                                <select class="form-select" id="movement-category">
                                                    <option value="All">All Categories</option>
                                                    <?php foreach ($categories as $cat): ?>
                                                        <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="movement-location" class="form-label">Location Filter</label>
                                                <select class="form-select" id="movement-location">
                                                    <option value="All">All Locations</option>
                                                    <?php foreach ($locations as $loc): ?>
                                                        <option value="<?php echo $loc; ?>"><?php echo $loc; ?></option>
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
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><i class="uil uil-usd-square me-2 text-info"></i> Inventory Valuation Report</h4>
                                    <p class="card-title-desc">Calculates the total monetary value of current stock holding by cost or selling price.</p>
                                    
                                    <form id="report-valuation" onsubmit="generateReport(event, 'Inventory Valuation', ['Method', 'Category'])">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="valuation-method" class="form-label">Valuation Method</label>
                                                <select class="form-select" id="valuation-method">
                                                    <option value="Cost" selected>Total Cost Value (LIFO/FIFO)</option>
                                                    <option value="Selling">Estimated Selling Value</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="valuation-category" class="form-label">Category Grouping</label>
                                                <select class="form-select" id="valuation-category">
                                                    <option value="All">Detail by Item</option>
                                                    <option value="Category">Group by Category</option>
                                                    <option value="Location">Group by Location</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-info w-lg">Generate Report</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><i class="uil uil-trash-alt me-2 text-dark"></i> Write-Off & Loss Report</h4>
                                    <p class="card-title-desc">Summarizes all stock written off (damaged, expired, lost) over a period.</p>
                                    
                                    <form id="report-writeoff" onsubmit="generateReport(event, 'Write-Off & Loss', ['Period', 'Reason'])">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="writeoff-period" class="form-label">Time Period</label>
                                                <select class="form-select" id="writeoff-period">
                                                    <?php foreach ($reportPeriods as $period): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $period); ?>" <?php echo ($period == 'Current Month' ? 'selected' : ''); ?>><?php echo $period; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="writeoff-reason" class="form-label">Reason Filter</label>
                                                <select class="form-select" id="writeoff-reason">
                                                    <option value="All">All Reasons</option>
                                                    <option value="Expired">Expired</option>
                                                    <option value="Damaged">Damaged/Spoiled</option>
                                                    <option value="Lost">Lost/Missing</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-dark w-lg">Generate Report</button>
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
            console.log('Inventory Reports Page Loaded.');
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

            alert(`--- Generating Report ---\n\n${filterDetails}\n(Simulated: The actual report data would be loaded here.)`);
        }
    </script>
</body>

</html>