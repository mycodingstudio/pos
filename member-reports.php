<?php
$pageTitle = "Loyalty & Member Reports";
include_once 'views/header.php';

// --- MOCK GLOBAL DATA ---
$memberGroups = ['All Groups', 'General Member', 'Silver Tier', 'Gold Tier', 'Platinum VIP'];
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Loyalty</a></li>
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
                                    <h4 class="card-title mb-4"><i class="uil uil-analytics me-2 text-primary"></i> Loyalty Program Summary</h4>
                                    <p class="card-title-desc">Key metrics for the loyalty program over a period: Points Earned vs. Redeemed.</p>
                                    
                                    <form id="report-program-summary" onsubmit="generateReport(event, 'Loyalty Program Summary', ['Period', 'Group'])">
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
                                                <label for="summary-group" class="form-label">Filter by Member Group</label>
                                                <select class="form-select" id="summary-group">
                                                    <?php foreach ($memberGroups as $group): ?>
                                                        <option value="<?php echo $group; ?>"><?php echo $group; ?></option>
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
                                    <h4 class="card-title mb-4"><i class="uil uil-user-plus me-2 text-success"></i> New Member Acquisition Report</h4>
                                    <p class="card-title-desc">Tracks the number of new members signed up and their initial spending.</p>
                                    
                                    <form id="report-acquisition" onsubmit="generateReport(event, 'New Member Acquisition', ['Period', 'Location'])">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="acquisition-period" class="form-label">Sign-up Period</label>
                                                <select class="form-select" id="acquisition-period">
                                                    <?php foreach ($reportPeriods as $period): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $period); ?>"><?php echo $period; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="acquisition-location" class="form-label">Sign-up Location</label>
                                                <select class="form-select" id="acquisition-location">
                                                    <option value="All">All Locations/POS</option>
                                                    <option value="Online">Online Registration</option>
                                                    <option value="StoreA">Main Store A</option>
                                                    <option value="StoreB">Main Store B</option>
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
                                    <h4 class="card-title mb-4"><i class="uil uil-trophy me-2 text-warning"></i> Tier Status & Migration Analysis</h4>
                                    <p class="card-title-desc">Overview of members by tier and tracking of upgrades/downgrades over time.</p>
                                    
                                    <form id="report-tier-migration" onsubmit="generateReport(event, 'Tier Status & Migration', ['DateRange', 'Action'])">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="tier-range" class="form-label">Review Period</label>
                                                <select class="form-select" id="tier-range">
                                                    <?php foreach ($reportPeriods as $period): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $period); ?>" <?php echo ($period == 'Last 90 Days' ? 'selected' : ''); ?>><?php echo $period; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="tier-action" class="form-label">Migration Type</label>
                                                <select class="form-select" id="tier-action">
                                                    <option value="Snapshot" selected>Current Tier Snapshot</option>
                                                    <option value="Upgrade">Members who UPGRADED</option>
                                                    <option value="Downgrade">Members who DOWNGRADED</option>
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
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><i class="uil uil-hourglass me-2 text-danger"></i> Points Expiry Projection</h4>
                                    <p class="card-title-desc">Identifies members whose points are due to expire within the next few months.</p>
                                    
                                    <form id="report-expiry-projection" onsubmit="generateReport(event, 'Points Expiry Projection', ['DaysAhead', 'Group'])">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="expiry-days" class="form-label">Expiring Within (Days)</label>
                                                <select class="form-select" id="expiry-days">
                                                    <option value="30">30 Days</option>
                                                    <option value="60" selected>60 Days</option>
                                                    <option value="90">90 Days</option>
                                                    <option value="180">180 Days</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="expiry-group" class="form-label">Filter by Member Group</label>
                                                <select class="form-select" id="expiry-group">
                                                    <?php foreach ($memberGroups as $group): ?>
                                                        <option value="<?php echo $group; ?>"><?php echo $group; ?></option>
                                                    <?php endforeach; ?>
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

                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><i class="uil uil-tag-alt me-2 text-info"></i> E-Voucher Usage & Liability</h4>
                                    <p class="card-title-desc">Tracks total value of generated, redeemed, and outstanding voucher liability.</p>
                                    
                                    <form id="report-voucher-usage" onsubmit="generateReport(event, 'E-Voucher Usage', ['Period', 'Status'])">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="voucher-period" class="form-label">Issued Period</label>
                                                <select class="form-select" id="voucher-period">
                                                    <?php foreach ($reportPeriods as $period): ?>
                                                        <option value="<?php echo str_replace(' ', '-', $period); ?>" <?php echo ($period == 'Last 90 Days' ? 'selected' : ''); ?>><?php echo $period; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="voucher-status" class="form-label">Voucher Status</label>
                                                <select class="form-select" id="voucher-status">
                                                    <option value="All">All Statuses</option>
                                                    <option value="Active" selected>Active / Outstanding</option>
                                                    <option value="Redeemed">Redeemed</option>
                                                    <option value="Expired">Expired</option>
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
            console.log('Loyalty & Member Reports Page Loaded.');
        });
        
        // Generic mock function to simulate generating a report
        function generateReport(event, reportName, parameters) {
            event.preventDefault();
            
            let filterDetails = `Report: ${reportName}\n\nFilters:\n`;
            
            // Collect filter values dynamically based on the form parameters
            parameters.forEach(param => {
                let elementId = event.target.id.replace('report-', '') + '-' + param.toLowerCase().replace(/ /g, '-').replace('daysahead', 'days').replace('daterange', 'range');
                let element = document.getElementById(elementId);
                let value = element ? element.value : 'N/A';
                
                filterDetails += `- ${param}: ${value}\n`;
            });

            alert(`--- Generating Report ---\n\n${filterDetails}\n(Simulated: The actual report data table, list, or chart would be loaded here.)`);
        }
    </script>
</body>

</html>