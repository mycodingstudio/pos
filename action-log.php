<?php
$pageTitle = "Action Logs";
include_once 'views/header.php';

// --- MOCK DATA: Filtered to ONLY include Point Deduction Transactions ---
$logs = [
    [
        "id" => 1059,
        "date" => "2025-11-22 10:30 AM",
        "type" => "deduction", 
        "target" => "Ali Bin Abu (Technician)",
        "name_only" => "Ali Bin Abu",
        "company" => "All Day",
        "points" => 5, // Demerit Point
        "transaction" => "Deducted 5 Points",
        "reason" => "Late Arrival (Rule #5 - Level 1)", // Rule/Reason
        "remark" => "Was 15 minutes late to the site morning briefing.", // Remark
        "by" => "Admin"
    ],
    [
        "id" => 1058,
        "date" => "2025-11-22 09:15 AM",
        "type" => "deduction",
        "target" => "Ah Meng (Foreman)",
        "name_only" => "Ah Meng",
        "company" => "All Day",
        "points" => 12, // Demerit Point
        "transaction" => "Deducted 12 Points",
        "reason" => "No complete PSM Check list (Rule #24 - Level 3)",
        "remark" => "Supervisor noted checklist was not done for Zone C works. Repeat offence.",
        "by" => "Manager John"
    ],
    [
        "id" => 1055,
        "date" => "2025-11-20 11:00 AM",
        "type" => "deduction",
        "target" => "Ali Bin Abu (Technician)",
        "name_only" => "Ali Bin Abu",
        "company" => "All Day",
        "points" => 3, // Demerit Point
        "transaction" => "Deducted 3 Points",
        "reason" => "Dirty Hand (Rule #72 - Level 1)",
        "remark" => "Minor violation. First warning given.",
        "by" => "Admin"
    ],
    [
        "id" => 1054,
        "date" => "2025-11-19 05:30 PM",
        "type" => "deduction",
        "target" => "Siti Aminah (Admin)",
        "name_only" => "Siti Aminah",
        "company" => "All Day",
        "points" => 7, // Demerit Point
        "transaction" => "Deducted 7 Points",
        "reason" => "Monthly MC Limit Exceeded (Rule #1 - Level 2)",
        "remark" => "Second MC this month. Requested medical cert copy.",
        "by" => "Manager John"
    ],
];

// Helper for Badge Color
function getTypeBadge($type) {
    if($type === 'deduction') return 'badge bg-danger';
    return 'badge bg-secondary';
}
?>

<body>
    <?php include_once 'views/loading_spinner.php'; ?>

    <script>
        const logData = <?php echo json_encode($logs); ?>;

        // Mock data for the Deduction Modal (Staff and Rules)
        const mockStaff = [
            { id: 101, name: "Ali Bin Abu", company: "All Day" },
            { id: 102, name: "Siti Aminah", company: "All Day" },
            { id: 103, name: "Ah Meng", company: "Contractor Co." },
            { id: 104, name: "John Doe", company: "External Agency" },
        ];
        
        const mockRules = [
            { id: 1, desc: "Monthly MC Limit Exceeded", pts: [3, 7, 12, 18, 25] },
            { id: 5, desc: "Late Arrival", pts: [3, 7, 12, 18, 25] },
            { id: 72, desc: "Dirty Hand/PPE Violation", pts: [3, 7, 12, 18, 25] },
            { id: 24, desc: "No Complete PSM Check list", pts: [3, 7, 12, 18, 25] },
            { id: 12, desc: "Damage Company Property", pts: [12, 25, 50, 75, 100] },
        ];
    </script>

    <div id="layout-wrapper">

        <?php include_once 'views/top-bar.php'; ?>

        <?php include_once 'views/sidebar.php'; ?>

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <?php include_once 'views/container_page_title.php'; ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-title p-4 border-bottom">
                                    
                                    <div class="d-lg-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center mb-2 mb-lg-0">
                                            <div class="me-2"><i class="uil uil-file-alt font-size-24 text-primary"></i></div>
                                            <div>
                                                <h5 class="font-size-16 mb-1">KPI Demerit Log</h5>
                                                <p class="text-muted text-truncate mb-0">Record of all staff point deductions by managers.</p>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="btn btn-danger btn-sm waves-effect waves-light" onclick="openDeductionModal()">
                                                <i class="mdi mdi-minus-circle-outline me-1"></i> Add Deduction Transaction
                                            </button>

                                            <button class="btn btn-success btn-sm waves-effect waves-light" onclick="exportCSV()">
                                                <i class="mdi mdi-microsoft-excel me-1"></i> Export CSV
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row mt-3 pt-3 border-top align-items-end">
                                        <div class="col-md-3 mb-2">
                                            <label class="form-label font-size-12 text-muted">Filter by Staff Name</label>
                                            <select class="form-select form-control-sm" id="filterStaffName" onchange="applyFilters()">
                                                <option value="">All Staff</option>
                                                </select>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="form-label font-size-12 text-muted">From Date</label>
                                            <input type="date" class="form-control form-control-sm" id="filterStartDate" onchange="applyFilters()">
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="form-label font-size-12 text-muted">To Date</label>
                                            <input type="date" class="form-control form-control-sm" id="filterEndDate" onchange="applyFilters()">
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <button class="btn btn-light w-100 btn-sm" onclick="resetFilters()">Reset Filters</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="logTable" class="table table-hover table-striped dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 60px;">ID</th>
                                                    <th style="width: 150px;">Date & Time</th>
                                                    <th>Staff Name</th>
                                                    <th style="width: 80px;">Points</th>
                                                    <th>Reason / Rule Violated</th>
                                                    <th>Remark</th>
                                                    <th style="width: 120px;">Action By</th>
                                                </tr>
                                            </thead>
                                            <tbody id="logTableBody">
                                                <?php foreach($logs as $log): ?>
                                                <tr>
                                                    <td class="text-muted">#<?php echo $log['id']; ?></td>
                                                    
                                                    <td>
                                                        <i class="mdi mdi-clock-outline me-1 text-muted"></i>
                                                        <?php echo $log['date']; ?>
                                                    </td>

                                                    <td>
                                                        <h6 class="font-size-14 mb-0"><?php echo $log['name_only']; ?></h6>
                                                        <span class="text-muted font-size-12"><?php echo $log['company']; ?></span>
                                                    </td>
                                                    
                                                    <td class="text-danger fw-bold font-size-16">
                                                        -<?php echo $log['points']; ?>
                                                    </td>

                                                    <td>
                                                        <p class="mb-0 text-dark"><?php echo $log['reason']; ?></p>
                                                    </td>

                                                    <td>
                                                        <p class="text-muted mb-0 font-size-12 text-truncate" style="max-width: 250px;">
                                                            <?php echo $log['remark']; ?>
                                                        </p>
                                                    </td>

                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-xs me-2">
                                                                <span class="avatar-title rounded-circle bg-light text-primary font-size-12">
                                                                    <?php echo substr($log['by'], 0, 1); ?>
                                                                </span>
                                                            </div>
                                                            <span class="font-size-13"><?php echo $log['by']; ?></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="row mt-4">
                                        <div class="col-sm-6">
                                            <div class="text-muted">Showing 1 to <?php echo count($logs); ?> of <?php echo count($logs); ?> entries (Filtered)</div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="text-sm-end">
                                                <ul class="pagination pagination-sm justify-content-end mb-0">
                                                    <li class="page-item disabled">
                                                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                                                    </li>
                                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                    <li class="page-item disabled">
                                                        <a class="page-link" href="#">Next</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    </div> </div>
            <?php include_once 'views/footer.php'; ?>
        </div>
        </div>

    <div class="modal fade" id="deductionModal" tabindex="-1" aria-labelledby="deductionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title text-white" id="deductionModalLabel"><i class="mdi mdi-minus-circle-outline me-2"></i> Add Demerit Transaction</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="deductionForm">
                        <div class="mb-3">
                            <label class="form-label">Select Staff</label>
                            <select class="form-select select2" id="staffDropdown" style="width: 100%;" required>
                                <option value="">Select Staff Member...</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Violation Rule / Reason</label>
                            <select class="form-select select2" id="ruleDropdown" style="width: 100%;" required onchange="updatePoints()">
                                <option value="">Select Violated Rule...</option>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Violation Level</label>
                                <select class="form-select" id="levelDropdown" required onchange="updatePoints()">
                                    <option value="0">Level 1</option>
                                    <option value="1">Level 2</option>
                                    <option value="2">Level 3</option>
                                    <option value="3">Level 4</option>
                                    <option value="4">Level 5</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Points to Deduct</label>
                                <input type="number" class="form-control text-center bg-light fw-bold text-danger" id="deductedPoints" readonly required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Deduction Remarks / Note</label>
                            <textarea class="form-control" id="deductionRemark" rows="3" placeholder="Enter any additional remarks for this deduction..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date & Time of Deduction</label>
                            <input type="datetime-local" class="form-control" id="deductionDateTime" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Action By (Manager/Admin)</label>
                            <input type="text" class="form-control" id="actionBy" value="Manager Admin" required>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="saveDeduction()">Confirm Deduction</button>
                </div>
            </div>
        </div>
    </div>

    <?php include_once 'views/footer_libraries.php'; ?>

    <script src="assets/libs/select2/js/select2.min.js"></script>

    <script src="assets/js/app.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize Select2 for deduction modal dropdowns
            $('.select2').select2({
                dropdownParent: $('#deductionModal')
            });

            // Initialize filter staff dropdown on page load
            loadFilterStaffDropdown();
        });

        // --- Filter Functions (UI Demonstration) ---

        function loadFilterStaffDropdown() {
            const $filterDropdown = $('#filterStaffName');
            $filterDropdown.empty().append('<option value="">All Staff</option>');
            mockStaff.forEach(staff => {
                $filterDropdown.append(`<option value="${staff.name}">${staff.name}</option>`);
            });
        }
        
        function applyFilters() {
            const selectedStaff = $('#filterStaffName').val();
            const startDate = $('#filterStartDate').val();
            const endDate = $('#filterEndDate').val();

            if (selectedStaff || startDate || endDate) {
                 Swal.fire({
                    title: "Filter Applied (Mock)",
                    html: `Filtering logs...<br>Staff: <b>${selectedStaff || 'ALL'}</b><br>Dates: <b>${startDate || 'N/A'}</b> to <b>${endDate || 'N/A'}</b>`,
                    icon: "info",
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        }

        function resetFilters() {
            $('#filterStaffName').val('');
            $('#filterStartDate').val('');
            $('#filterEndDate').val('');
            Swal.fire({
                title: "Filters Cleared",
                icon: "info",
                timer: 1000,
                showConfirmButton: false
            });
            // In a real app, this would trigger a data table refresh
        }

        // --- Deduction Modal Functions ---

        function loadStaffDropdown() {
            const $dropdown = $('#staffDropdown');
            $dropdown.empty().append('<option value="">Select Staff Member...</option>');
            mockStaff.forEach(staff => {
                $dropdown.append(`<option value="${staff.id}">${staff.name} (${staff.company})</option>`);
            });
            $dropdown.val('').trigger('change');
        }

        function loadRuleDropdown() {
            const $dropdown = $('#ruleDropdown');
            $dropdown.empty().append('<option value="">Select Violated Rule...</option>');
            mockRules.forEach(rule => {
                $dropdown.append(`<option value="${rule.id}" data-pts="${rule.pts.join(',')}">Rule #${rule.id}: ${rule.desc}</option>`);
            });
            $dropdown.val('').trigger('change');
        }

        function openDeductionModal() {
            $('#deductionForm')[0].reset();
            loadStaffDropdown();
            loadRuleDropdown();

            // Set default date/time to now
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hour = String(now.getHours()).padStart(2, '0');
            const minute = String(now.getMinutes()).padStart(2, '0');
            $('#deductionDateTime').val(`${year}-${month}-${day}T${hour}:${minute}`);

            // Default the level dropdown to 1
            $('#levelDropdown').val('0');
            updatePoints(); 
            
            $('#deductionModal').modal('show');
        }

        function updatePoints() {
            const $ruleDropdown = $('#ruleDropdown');
            const $selectedRule = $ruleDropdown.find(':selected');
            const levelIndex = parseInt($('#levelDropdown').val());
            const ptsData = $selectedRule.data('pts');
            
            let points = 0;
            if (ptsData) {
                const ptsArray = ptsData.split(',').map(Number);
                points = ptsArray[levelIndex] || 0;
            }

            $('#deductedPoints').val(points);
        }

        function saveDeduction() {
            const staffId = $('#staffDropdown').val();
            const ruleId = $('#ruleDropdown').val();
            const points = $('#deductedPoints').val();
            const dateTime = $('#deductionDateTime').val();
            const actionBy = $('#actionBy').val();
            const remark = $('#deductionRemark').val() || "No additional remarks."; // Capture remark field

            if (!staffId || !ruleId || !points || !dateTime || !actionBy) {
                Swal.fire("Validation Error", "Please fill in all required fields (Staff, Rule, Points, Date/Time, Action By).", "warning");
                return;
            }

            const staffName = $('#staffDropdown option:selected').text();
            const ruleText = $('#ruleDropdown option:selected').text();

            Swal.fire({
                title: 'Confirm Deduction',
                html: `Deduct <b>${points} points</b> from <b>${staffName}</b><br>Reason: ${ruleText}<br>Remark: <i>${remark}</i>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#74788d',
                confirmButtonText: 'Yes, Deduct Now!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Recording transaction in the system.',
                        icon: 'info',
                        showConfirmButton: false,
                        timer: 1000
                    }).then(() => {
                        Swal.fire(
                            'Deduction Successful!',
                            `Successfully deducted ${points} points from ${staffName}. The remark has been recorded.`,
                            'success'
                        );
                        $('#deductionModal').modal('hide');
                    });
                }
            });
        }
        
        // --- Export Function ---
        function exportCSV() {
            let csv = [];
            
            const headers = ["Name", "Company", "Demerit Point", "Action By", "Reason", "Remark", "Date Time"];
            csv.push(headers.join(','));

            logData.forEach(function(log) {
                if (log.type === 'deduction') {
                    const row = [
                        `"${log.name_only}"`,
                        `"${log.company}"`,
                        log.points,
                        `"${log.by}"`,
                        `"${log.reason.replace(/"/g, '""')}"`, // Reason
                        `"${(log.remark || 'N/A').replace(/"/g, '""')}"`, // Remark
                        `"${log.date}"`
                    ];
                    csv.push(row.join(','));
                }
            });

            const csvFile = csv.join('\n');
            const blob = new Blob([csvFile], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "kpi_demerit_log_" + new Date().toISOString().slice(0, 10) + ".csv";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            Swal.fire("Export Successful", "The log data has been downloaded as a CSV file.", "success");
        }
    </script>
</body>
</html>