<?php
$pageTitle = "Member Details & Maintenance";
include_once 'views/header.php';

// --- MOCK MEMBER DATA ---
$memberList = [
    [
        "id" => 1001,
        "name" => "Ahmad Zulkifli",
        "phone" => "012-3456789",
        "email" => "ahmad@mail.com",
        "group" => "Gold Tier",
        "points" => 560,
        "total_spent" => 5600.00,
        "joined_date" => "2024-03-15"
    ],
    [
        "id" => 1002,
        "name" => "Chong Wei",
        "phone" => "016-1234567",
        "email" => "chong@mail.com",
        "group" => "Silver Tier",
        "points" => 125,
        "total_spent" => 1250.00,
        "joined_date" => "2025-01-20"
    ],
    [
        "id" => 1003,
        "name" => "Devi Sunder",
        "phone" => "019-9876543",
        "email" => "devi@mail.com",
        "group" => "General Member",
        "points" => 15,
        "total_spent" => 150.00,
        "joined_date" => "2025-10-01"
    ],
];

// --- MOCK POINT HISTORY DATA (for selected member ID 1001) ---
$pointHistory = [
    [
        "date" => "2025-11-28",
        "type" => "Earned (Sale)",
        "reference" => "INV-2025110002",
        "points_change" => "+125",
        "remarks" => "Purchase of health supplements"
    ],
    [
        "date" => "2025-10-10",
        "type" => "Redeemed",
        "reference" => "POS-123456",
        "points_change" => "-500",
        "remarks" => "RM 5.00 discount redemption"
    ],
    [
        "date" => "2025-03-15",
        "type" => "Bonus",
        "reference" => "SIGNUP",
        "points_change" => "+100",
        "remarks" => "Welcome Bonus Points"
    ],
];

// Helper to determine group badge color
function getGroupBadgeClass($groupName) {
    switch ($groupName) {
        case 'Gold Tier': return 'bg-warning text-dark';
        case 'Silver Tier': return 'bg-info';
        case 'Platinum VIP': return 'bg-primary';
        default: return 'bg-secondary';
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Loyalty</a></li>
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
                                    <h4 class="card-title mb-4">Search & Manage Member Profiles</h4>
                                    
                                    <form class="row g-3 mb-4" onsubmit="searchMember(event)">
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" id="search-member-input" placeholder="Enter Member ID, Phone, or Name" value="Ahmad Zulkifli (1001)" required>
                                        </div>
                                        <div class="col-md-auto">
                                            <button type="submit" class="btn btn-primary w-md">
                                                <i class="uil uil-search me-1"></i> Search Member
                                            </button>
                                        </div>
                                        <div class="col-md-auto">
                                            <a href="customer-setup.php" class="btn btn-outline-secondary w-md">
                                                <i class="uil uil-setting me-1"></i> Setup Loyalty Rules
                                            </a>
                                        </div>
                                    </form>

                                    <?php 
                                        // Use the first member for the mock display after search
                                        $selectedMember = $memberList[0]; 
                                    ?>
                                    <div class="row mt-5">
                                        <div class="col-xl-4 col-md-6">
                                            <div class="card border border-primary">
                                                <div class="card-header bg-primary text-white">Member Profile: <?php echo $selectedMember['name']; ?></div>
                                                <div class="card-body">
                                                    <p class="mb-1"><strong>Member ID:</strong> #<?php echo $selectedMember['id']; ?></p>
                                                    <p class="mb-1"><strong>Joined Since:</strong> <?php echo date('d M Y', strtotime($selectedMember['joined_date'])); ?></p>
                                                    <p class="mb-1"><strong>Phone:</strong> <?php echo $selectedMember['phone']; ?></p>
                                                    <p class="mb-1"><strong>Email:</strong> <?php echo $selectedMember['email']; ?></p>
                                                    <p class="mb-0"><strong>Total Lifetime Spent:</strong> RM <?php echo number_format($selectedMember['total_spent'], 2); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6">
                                            <div class="card border border-info h-100">
                                                <div class="card-header bg-info text-white">Loyalty Status & Points</div>
                                                <div class="card-body d-flex flex-column justify-content-between">
                                                    <div>
                                                        <p class="mb-2"><strong>Current Tier:</strong> 
                                                            <span class="badge <?php echo getGroupBadgeClass($selectedMember['group']); ?> font-size-14 p-2">
                                                                <?php echo $selectedMember['group']; ?>
                                                            </span>
                                                        </p>
                                                        <h2 class="mb-0 text-success"><?php echo number_format($selectedMember['points']); ?> Points</h2>
                                                        <p class="text-muted mt-1 mb-0">Equivalent to **RM <?php echo number_format($selectedMember['points'] / 100, 2); ?>** in redemption value.</p>
                                                    </div>
                                                    <div class="mt-3">
                                                        <button class="btn btn-sm btn-outline-warning" onclick="manualAdjustPoints(<?php echo $selectedMember['id']; ?>)">
                                                            <i class="uil uil-edit me-1"></i> Manually Adjust Points
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="card">
                                                <div class="card-header">Transfer/Change Group</div>
                                                <div class="card-body">
                                                    <form onsubmit="changeMemberGroup(event, <?php echo $selectedMember['id']; ?>)">
                                                        <div class="mb-3">
                                                            <label for="new-group" class="form-label">Change Member Group</label>
                                                            <select class="form-select" id="new-group" required>
                                                                <option value="General Member">General Member</option>
                                                                <option value="Silver Tier" <?php echo ($selectedMember['group'] === 'Silver Tier' ? 'selected' : ''); ?>>Silver Tier</option>
                                                                <option value="Gold Tier" <?php echo ($selectedMember['group'] === 'Gold Tier' ? 'selected' : ''); ?>>Gold Tier</option>
                                                                <option value="Platinum VIP">Platinum VIP</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="change-notes" class="form-label">Reason/Notes</label>
                                                            <input type="text" class="form-control" id="change-notes" placeholder="e.g., Manual upgrade for promotion" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-secondary w-100">Apply Group Change</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card mt-4">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Point Transaction History for <?php echo $selectedMember['name']; ?></h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped table-bordered align-middle table-nowrap mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th style="width: 150px;">Date/Time</th>
                                                            <th>Transaction Type</th>
                                                            <th>Reference</th>
                                                            <th class="text-end">Points Change</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($pointHistory as $history): ?>
                                                        <tr>
                                                            <td><?php echo date('d M Y H:i', strtotime($history['date'])); ?></td>
                                                            <td>
                                                                <span class="badge 
                                                                    <?php 
                                                                        if (strpos($history['type'], 'Earned') !== false || strpos($history['type'], 'Bonus') !== false) echo 'bg-success';
                                                                        elseif ($history['type'] === 'Redeemed' || $history['type'] === 'Adjusted (-)') echo 'bg-danger';
                                                                        else echo 'bg-primary';
                                                                    ?>">
                                                                    <?php echo $history['type']; ?>
                                                                </span>
                                                            </td>
                                                            <td><?php echo $history['reference']; ?></td>
                                                            <td class="text-end fw-bold 
                                                                <?php echo (strpos($history['points_change'], '+') !== false ? 'text-success' : 'text-danger'); ?>">
                                                                <?php echo $history['points_change']; ?>
                                                            </td>
                                                            <td><?php echo $history['remarks']; ?></td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="text-center mt-3">
                                                <button class="btn btn-link btn-sm" onclick="loadMoreHistory(<?php echo $selectedMember['id']; ?>)">Load Full History...</button>
                                            </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Member Maintenance Page Loaded.');
        });
        
        function searchMember(event) {
            event.preventDefault();
            const query = document.getElementById('search-member-input').value;
            alert(`Simulated: Searching for member with query: "${query}". (Displaying mock data for Ahmad Zulkifli)`);
            // In a real application, this would fetch data and update the member details section.
        }

        function manualAdjustPoints(id) {
            const currentPoints = 560; // Mock value
            const adjustment = prompt(`Enter point adjustment for Member #${id} (Current: ${currentPoints}). Use '+' for add, '-' for deduct. Example: +100 or -50`);
            
            if (adjustment) {
                alert(`Simulated: Attempting to manually adjust points by ${adjustment}. Record saved to history.`);
                // Success message and reload of history
            }
        }
        
        function changeMemberGroup(event, id) {
            event.preventDefault();
            const newGroup = document.getElementById('new-group').value;
            const notes = document.getElementById('change-notes').value;
            
            if (confirm(`Confirm change Member #${id}'s group to ${newGroup}?`)) {
                alert(`Simulated: Member #${id} group changed to **${newGroup}**. Notes: "${notes}"`);
                // Success message and update status display
            }
        }

        function loadMoreHistory(id) {
            alert(`Simulated: Loading more transaction history records for Member #${id}.`);
        }
    </script>
</body>

</html>