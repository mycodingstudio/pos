<?php
$pageTitle = "Point Redemption & E-Voucher Management";
include_once 'views/header.php';

// --- MOCK DATA ---
$selectedMember = [
    "id" => 1001,
    "name" => "Ahmad Zulkifli",
    "phone" => "012-3456789",
    "group" => "Gold Tier",
    "points" => 560,
    "redemption_rate" => 100, // 100 points = RM 1.00
    "redemption_value" => 1.00
];

// Mock list of active e-vouchers (generated from points)
$voucherList = [
    [
        "voucher_code" => "VCH500-Ahmad",
        "value" => 5.00,
        "points_deducted" => 500,
        "member_id" => 1001,
        "member_name" => "Ahmad Zulkifli",
        "date_issued" => "2025-11-20",
        "expiry_date" => "2026-02-20",
        "status" => "Active"
    ],
    [
        "voucher_code" => "VCH100-Chong",
        "value" => 1.00,
        "points_deducted" => 100,
        "member_id" => 1002,
        "member_name" => "Chong Wei",
        "date_issued" => "2025-10-15",
        "expiry_date" => "2026-01-15",
        "status" => "Redeemed"
    ],
    [
        "voucher_code" => "VCH200-Devi",
        "value" => 2.00,
        "points_deducted" => 200,
        "member_id" => 1003,
        "member_name" => "Devi Sunder",
        "date_issued" => "2025-09-01",
        "expiry_date" => "2025-12-01",
        "status" => "Expired"
    ],
];

// Helper functions
function getVoucherStatusClass($status) {
    switch ($status) {
        case 'Active': return 'bg-success';
        case 'Redeemed': return 'bg-primary';
        case 'Expired': return 'bg-danger';
        default: return 'bg-secondary';
    }
}

$currentValue = $selectedMember['points'] / $selectedMember['redemption_rate'] * $selectedMember['redemption_value'];
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
                        <div class="col-xl-4">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0 text-white">Member Search & Balance</h5>
                                </div>
                                <div class="card-body">
                                    <form class="row g-3 mb-4" onsubmit="searchMember(event)">
                                        <div class="col-12">
                                            <input type="text" class="form-control" id="search-member-input" placeholder="Enter Member ID, Phone, or Name" value="<?php echo $selectedMember['name'] . ' (' . $selectedMember['id'] . ')'; ?>" required>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="uil uil-search me-1"></i> Check Member
                                            </button>
                                        </div>
                                    </form>
                                    
                                    <h5 class="mt-4">Current Status:</h5>
                                    <div class="p-3 bg-light rounded">
                                        <p class="mb-1"><strong>Name:</strong> <?php echo $selectedMember['name']; ?></p>
                                        <p class="mb-1"><strong>Group:</strong> <span class="badge bg-warning text-dark"><?php echo $selectedMember['group']; ?></span></p>
                                        <hr class="my-2">
                                        <p class="mb-1"><strong>Current Points:</strong> <span class="font-size-18 text-success fw-bold"><?php echo number_format($selectedMember['points']); ?></span> pts</p>
                                        <p class="mb-0"><strong>Redemption Value:</strong> <span class="font-size-16 text-info fw-bold">RM <?php echo number_format($currentValue, 2); ?></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-8">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0 text-white">Generate Redemption / E-Voucher</h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-title-desc">Generate a discount voucher or immediately redeem points as a transaction discount.</p>
                                    
                                    <form id="redemption-form" onsubmit="processRedemption(event)">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="points-to-redeem" class="form-label">Points to Redeem (Min 100)</label>
                                                <input type="number" class="form-control" id="points-to-redeem" value="100" min="100" step="100" required onchange="calculateValue()">
                                                <div class="form-text">Must be in multiples of <?php echo $selectedMember['redemption_rate']; ?> points.</div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="redemption-amount" class="form-label">Equivalent Discount Value</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">RM</span>
                                                    <input type="text" class="form-control fw-bold" id="redemption-amount" value="<?php echo number_format($selectedMember['redemption_value'], 2); ?>" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label for="redemption-type" class="form-label">Redemption Type</label>
                                            <select class="form-select" id="redemption-type" required>
                                                <option value="IMMEDIATE">Immediate Discount (For current transaction)</option>
                                                <option value="EVOUCHER" selected>Generate E-Voucher (For future use)</option>
                                            </select>
                                        </div>

                                        <div class="row" id="voucher-options">
                                            <div class="col-md-6 mb-3">
                                                <label for="expiry-days" class="form-label">Voucher Expiry (Days)</label>
                                                <input type="number" class="form-control" id="expiry-days" value="90" min="30" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="min-purchase" class="form-label">Min Purchase (Optional)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">RM</span>
                                                    <input type="number" class="form-control" id="min-purchase" value="0.00" step="0.01">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success w-lg" id="process-redemption-btn">
                                                <i class="uil uil-money-withdrawal me-1"></i> Process Redemption
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Active & Recent E-Vouchers (Generated from Points)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped align-middle table-nowrap mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 150px;">Voucher Code</th>
                                                    <th>Member</th>
                                                    <th class="text-end">Value (RM)</th>
                                                    <th class="text-end">Points Used</th>
                                                    <th style="width: 120px;">Issued Date</th>
                                                    <th style="width: 120px;">Expiry Date</th>
                                                    <th class="text-center">Status</th>
                                                    <th style="width: 80px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($voucherList as $voucher): ?>
                                                <tr>
                                                    <td class="fw-bold text-primary"><?php echo $voucher['voucher_code']; ?></td>
                                                    <td><?php echo $voucher['member_name']; ?> (#<?php echo $voucher['member_id']; ?>)</td>
                                                    <td class="text-end fw-bold text-success">RM <?php echo number_format($voucher['value'], 2); ?></td>
                                                    <td class="text-end text-danger"><?php echo number_format($voucher['points_deducted']); ?></td>
                                                    <td><?php echo date('d M Y', strtotime($voucher['date_issued'])); ?></td>
                                                    <td><?php echo date('d M Y', strtotime($voucher['expiry_date'])); ?></td>
                                                    <td class="text-center">
                                                        <span class="badge <?php echo getVoucherStatusClass($voucher['status']); ?>">
                                                            <?php echo $voucher['status']; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if ($voucher['status'] === 'Active'): ?>
                                                            <button class="btn btn-sm btn-outline-danger" title="Cancel Voucher" onclick="cancelVoucher('<?php echo $voucher['voucher_code']; ?>')">
                                                                <i class="uil uil-times"></i>
                                                            </button>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btn-outline-secondary" disabled>
                                                                <i class="uil uil-eye"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
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
        const REDEMPTION_RATE = <?php echo $selectedMember['redemption_rate']; ?>;
        const REDEMPTION_VALUE = <?php echo $selectedMember['redemption_value']; ?>;
        const CURRENT_POINTS = <?php echo $selectedMember['points']; ?>;
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Point Redemption & E-Voucher Management Page Loaded.');
            
            // Initial calculation
            calculateValue();
            
            // Event listener for redemption type change
            document.getElementById('redemption-type').addEventListener('change', function() {
                const type = this.value;
                const voucherOptions = document.getElementById('voucher-options');
                const processBtn = document.getElementById('process-redemption-btn');
                
                if (type === 'EVOUCHER') {
                    voucherOptions.style.display = 'flex';
                    processBtn.innerHTML = '<i class="uil uil-money-withdrawal me-1"></i> Generate E-Voucher';
                } else {
                    voucherOptions.style.display = 'none';
                    processBtn.innerHTML = '<i class="uil uil-money-withdrawal me-1"></i> Apply Immediate Discount';
                }
            });
        });

        function searchMember(event) {
            event.preventDefault();
            const query = document.getElementById('search-member-input').value;
            alert(`Simulated: Searching for member with query: "${query}". (Displaying mock data for Ahmad Zulkifli)`);
            // In a real application, this would fetch the member data and refresh the page or update the balance card.
        }

        function calculateValue() {
            const pointsInput = document.getElementById('points-to-redeem');
            const amountInput = document.getElementById('redemption-amount');
            
            let points = parseInt(pointsInput.value);

            // Basic validation
            if (isNaN(points) || points < REDEMPTION_RATE || points % REDEMPTION_RATE !== 0) {
                points = REDEMPTION_RATE;
                pointsInput.value = points;
            }
            
            const value = (points / REDEMPTION_RATE) * REDEMPTION_VALUE;
            amountInput.value = value.toFixed(2);
        }

        function processRedemption(event) {
            event.preventDefault();
            
            const points = parseInt(document.getElementById('points-to-redeem').value);
            const amount = parseFloat(document.getElementById('redemption-amount').value);
            const type = document.getElementById('redemption-type').value;

            if (points > CURRENT_POINTS) {
                alert('Error: Not enough points for this redemption.');
                return;
            }

            if (type === 'EVOUCHER') {
                const expiryDays = document.getElementById('expiry-days').value;
                const minPurchase = document.getElementById('min-purchase').value;
                
                if (confirm(`Confirm generation of RM ${amount.toFixed(2)} E-Voucher for ${points} points?`)) {
                    alert(`Simulated: E-Voucher generated!\nPoints Deducted: ${points}\nVoucher Value: RM ${amount.toFixed(2)}\nExpires in: ${expiryDays} days\nMin Purchase: RM ${minPurchase}`);
                    // In a real app, refresh the voucher list and balance
                }
            } else {
                if (confirm(`Confirm immediate redemption of RM ${amount.toFixed(2)} discount for ${points} points?`)) {
                    alert(`Simulated: Immediate discount applied.\nPoints Deducted: ${points}.\nThis transaction value (RM ${amount.toFixed(2)}) is now available for the current POS transaction.`);
                    // In a real app, update the POS interface and point balance
                }
            }
        }

        function cancelVoucher(code) {
            if (confirm(`Are you sure you want to CANCEL voucher ${code}? Points will be refunded.`)) {
                alert(`Simulated: Voucher ${code} cancelled. ${500} points refunded to member's account.`);
            }
        }
    </script>
</body>

</html>