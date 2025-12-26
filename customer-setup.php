<?php
$pageTitle = "Customer & Member Setup";
include_once 'views/header.php';

// --- MOCK DATA ---
// Updated with 'birthday' field
$customerList = [
    [
        "id" => 1001,
        "name" => "Ahmad Zulkifli",
        "email" => "ahmad@mail.com",
        "phone" => "012-3456789",
        "birthday" => "1985-04-12",
        "group" => "Gold Tier",
        "status" => "Active",
        "points" => 560
    ],
    [
        "id" => 1002,
        "name" => "Chong Wei",
        "email" => "chong@mail.com",
        "phone" => "016-1234567",
        "birthday" => "1992-08-23",
        "group" => "Silver Tier",
        "status" => "Active",
        "points" => 125
    ],
    [
        "id" => 1003,
        "name" => "Devi Sunder",
        "email" => "devi@mail.com",
        "phone" => "019-9876543",
        "birthday" => "1995-11-30",
        "group" => "General Member",
        "status" => "Active",
        "points" => 15
    ],
    [
        "id" => 1004,
        "name" => "Non-Member Guest",
        "email" => "-",
        "phone" => "011-22334455",
        "birthday" => null, // Guest has no birthday
        "group" => "None",
        "status" => "Inactive",
        "points" => 0
    ],
];

$memberGroups = [
    ["name" => "General Member", "min_points" => 0, "color" => "secondary"],
    ["name" => "Silver Tier", "min_points" => 100, "color" => "info"],
    ["name" => "Gold Tier", "min_points" => 500, "color" => "warning"],
    ["name" => "Platinum VIP", "min_points" => 2000, "color" => "primary"],
];

// Helper to determine group badge color
function getGroupBadgeClass($groupName) {
    global $memberGroups;
    foreach ($memberGroups as $group) {
        if ($group['name'] === $groupName) {
            return 'bg-' . $group['color'];
        }
    }
    return 'bg-dark';
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Customers</a></li>
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
                                    <h4 class="card-title mb-4">Customer & Loyalty Management</h4>

                                    <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#tab-profiles" role="tab">
                                                <i class="uil uil-users-alt me-1"></i> Customer Profiles
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#tab-groups" role="tab">
                                                <i class="uil uil-layer-group me-1"></i> Member Groups Setup
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#tab-loyalty" role="tab">
                                                <i class="uil uil-money-withdraw me-1"></i> Loyalty Point Rules
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content pt-3">
                                        
                                        <div class="tab-pane active" id="tab-profiles" role="tabpanel">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="font-size-15">Registered Customers (<?php echo count($customerList); ?>)</h5>
                                                <button class="btn btn-sm btn-primary" onclick="openAddCustomerModal()">
                                                    <i class="uil uil-plus me-1"></i> Add New Customer
                                                </button>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover align-middle table-nowrap mb-0" id="customerTable">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th scope="col" style="width: 50px;">ID</th>
                                                            <th scope="col">Customer Name</th>
                                                            <th scope="col">Phone</th>
                                                            <th scope="col">Email</th>
                                                            <th scope="col">Birthday</th> <th scope="col" class="text-center">Member Group</th>
                                                            <th scope="col" class="text-end">Current Points</th>
                                                            <th scope="col" style="width: 120px;">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($customerList as $customer): ?>
                                                        <tr>
                                                            <td><?php echo $customer['id']; ?></td>
                                                            <td class="fw-bold"><?php echo htmlspecialchars($customer['name']); ?></td>
                                                            <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                                                            <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                                            
                                                            <td>
                                                                <?php 
                                                                    if ($customer['birthday']) {
                                                                        echo date('d M Y', strtotime($customer['birthday'])); 
                                                                    } else {
                                                                        echo '<span class="text-muted">-</span>';
                                                                    }
                                                                ?>
                                                            </td>
                                                            
                                                            <td class="text-center">
                                                                <span class="badge <?php echo getGroupBadgeClass($customer['group']); ?>">
                                                                    <?php echo $customer['group']; ?>
                                                                </span>
                                                            </td>
                                                            <td class="text-end fw-bold text-info"><?php echo number_format($customer['points']); ?></td>
                                                            <td>
                                                                <a href="customer-details.php?id=<?php echo $customer['id']; ?>" class="btn btn-sm btn-outline-info" title="View Details">
                                                                    <i class="uil uil-file-alt"></i>
                                                                </a>
                                                                <button class="btn btn-sm btn-outline-warning ms-1" title="Edit Profile" onclick="editCustomer(<?php echo $customer['id']; ?>)">
                                                                    <i class="uil uil-pen"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="tab-groups" role="tabpanel">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="font-size-15">Configure Loyalty Tiers / Member Groups</h5>
                                                <button class="btn btn-sm btn-success" onclick="addGroup()">
                                                    <i class="uil uil-plus me-1"></i> Add New Group
                                                </button>
                                            </div>
                                            
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped align-middle table-nowrap mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th scope="col">Group Name</th>
                                                            <th scope="col">Minimum Points Required</th>
                                                            <th scope="col">Tier Color</th>
                                                            <th scope="col" style="width: 120px;">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($memberGroups as $group): ?>
                                                        <tr>
                                                            <td class="fw-bold"><?php echo $group['name']; ?></td>
                                                            <td><?php echo number_format($group['min_points']); ?> Points</td>
                                                            <td><span class="badge <?php echo 'bg-' . $group['color']; ?>"><?php echo ucfirst($group['color']); ?></span></td>
                                                            <td>
                                                                <button class="btn btn-sm btn-outline-warning" title="Edit Group" onclick="editGroup('<?php echo $group['name']; ?>')">
                                                                    <i class="uil uil-pen"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-outline-danger ms-1" title="Delete Group" onclick="deleteGroup('<?php echo $group['name']; ?>')">
                                                                    <i class="uil uil-trash-alt"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="tab-loyalty" role="tabpanel">
                                            <h5 class="font-size-15 mb-4">Global Loyalty Program Configuration</h5>
                                            <form id="loyalty-setup-form" onsubmit="saveLoyaltyRules(event)">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="card border border-primary">
                                                            <div class="card-header bg-primary text-white">Point Collection Rule</div>
                                                            <div class="card-body">
                                                                <p class="card-title-desc">Configure how many points customers earn per currency spent.</p>
                                                                <div class="mb-3">
                                                                    <label for="point-earn-rate" class="form-label">Points Earned per RM / $ Spent</label>
                                                                    <div class="input-group">
                                                                        <input type="number" class="form-control" id="point-earn-rate" value="1" step="0.1" required>
                                                                        <span class="input-group-text">Points</span>
                                                                    </div>
                                                                    <div class="form-text">e.g., 1 point earned for every RM 1.00 spent.</div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="minimum-spend" class="form-label">Minimum Purchase for Points</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">RM</span>
                                                                        <input type="number" class="form-control" id="minimum-spend" value="10.00" step="0.01" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card border border-info">
                                                            <div class="card-header bg-info text-white">Point Redemption Rule</div>
                                                            <div class="card-body">
                                                                <p class="card-title-desc">Configure the value of points when used for redemption.</p>
                                                                <div class="mb-3">
                                                                    <label for="point-value" class="form-label">Redemption Value (RM / $ per 100 Points)</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">RM</span>
                                                                        <input type="number" class="form-control" id="point-value" value="1.00" step="0.01" required>
                                                                        <span class="input-group-text">per 100 points</span>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="min-redemption-points" class="form-label">Minimum Points for Redemption</label>
                                                                    <input type="number" class="form-control" id="min-redemption-points" value="100" min="10" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-end mt-3">
                                                    <button type="submit" class="btn btn-success w-lg">Save Loyalty Rules</button>
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
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Customer Setup Page Loaded.');
        });
        
        // --- Customer Profile Functions ---
        function openAddCustomerModal() {
            // Updated to reflect new field
            alert('Simulated: Opening modal for "Add New Customer" profile details (Name, Email, Phone, Birthday).');
            // In a real application, you would launch a modal/dialog here
        }

        function editCustomer(id) {
            alert(`Simulated: Opening modal to edit Customer ID ${id}'s profile.`);
        }

        // --- Member Group Functions ---
        function addGroup() {
            alert('Simulated: Opening form to add a new Member Group (Tier Name, Min Points, Benefits).');
        }

        function editGroup(name) {
            alert(`Simulated: Opening form to edit the "${name}" Member Group.`);
        }
        
        function deleteGroup(name) {
            if (confirm(`Are you sure you want to delete the member group: ${name}?`)) {
                alert(`Simulated: Deleting member group "${name}".`);
            }
        }

        // --- Loyalty Rules Function ---
        function saveLoyaltyRules(event) {
            event.preventDefault();
            const earnRate = document.getElementById('point-earn-rate').value;
            const redeemValue = document.getElementById('point-value').value;
            
            alert(`Simulated: Loyalty rules saved successfully!\nEarn Rate: ${earnRate} pt / RM spent\nRedeem Value: RM ${redeemValue} / 100 points`);
        }
    </script>
</body>

</html>