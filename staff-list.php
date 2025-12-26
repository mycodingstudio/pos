<?php
$pageTitle = "Staff and POS Assignment";
include_once 'views/header.php';

// --- MOCK DATA ---
// Updated to remove 'point' and 'last_deduct' and add 'pos_counter' assignment
$staffList = [
    [
        "id" => 101, 
        "name" => "Ali Bin Abu", 
        "company" => "All Day", 
        "group" => "Pharmacist",
        "status" => "active",
        "pos_counter" => "Counter 1 (Prescription)", 
        "avatar" => "assets/images/users/avatar-2.jpg" 
    ],
    [
        "id" => 102, 
        "name" => "Siti Aminah", 
        "company" => "All Day", 
        "group" => "Admin/Supervisor",
        "status" => "active",
        "pos_counter" => "Counter 3 (Supervisor)",
        "avatar" => "assets/images/users/avatar-3.jpg"
    ],
    [
        "id" => 103, 
        "name" => "Ah Meng", 
        "company" => "Contractor Co.", 
        "group" => "Cashier",
        "status" => "active",
        "pos_counter" => "Counter 2 (OTC/Express)",
        "avatar" => "assets/images/users/avatar-4.jpg"
    ],
    [
        "id" => 104, 
        "name" => "John Doe", 
        "company" => "All Day", 
        "group" => "Cashier",
        "status" => "blocked",
        "pos_counter" => "Counter 2 (OTC/Express)",
        "avatar" => "assets/images/users/avatar-5.jpg"
    ],
    [
        "id" => 105, 
        "name" => "Maria Gonzales", 
        "company" => "All Day", 
        "group" => "Pharmacist Asst.",
        "status" => "active",
        "pos_counter" => "Counter 1 (Prescription)",
        "avatar" => "assets/images/users/avatar-6.jpg"
    ],
    [
        "id" => 106, 
        "name" => "Devi A/P Mohan", 
        "company" => "All Day", 
        "group" => "Cashier",
        "status" => "active",
        "pos_counter" => "Counter 3 (Supervisor)",
        "avatar" => "assets/images/users/avatar-7.jpg"
    ],
];

// Helper to determine status badge color
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'active':
            return 'bg-success';
        case 'blocked':
            return 'bg-warning text-dark';
        case 'on-leave':
            return 'bg-info';
        default:
            return 'bg-secondary';
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

                    <?php include_once 'views/container_page_title.php'; ?>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h4 class="card-title">Current Staff Roster</h4>
                                        <button class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                                            <i class="mdi mdi-plus me-1"></i> Add New Staff
                                        </button>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table align-middle table-nowrap table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Staff ID</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Group / Role</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Assigned POS Counter</th>
                                                    <th scope="col" style="width: 120px;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($staffList as $staff): ?>
                                                <tr>
                                                    <th scope="row"><?php echo $staff['id']; ?></th>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="<?php echo $staff['avatar']; ?>"
                                                                class="rounded-circle avatar-xs me-2"
                                                                onerror="this.onerror=null;this.src='assets/images/users/avatar-1.jpg';"
                                                                alt="">
                                                            <span
                                                                class="text-dark font-weight-bold"><?php echo htmlspecialchars($staff['name']); ?></span>
                                                        </div>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($staff['group']); ?></td>
                                                    <td>
                                                        <span
                                                            class="badge <?php echo getStatusBadgeClass($staff['status']); ?> font-size-12">
                                                            <?php echo ucfirst($staff['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="text-primary font-weight-bold">
                                                            <?php echo htmlspecialchars($staff['pos_counter']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <button class="btn btn-sm btn-info text-white"
                                                                title="Edit Assignment"
                                                                onclick="editStaff(<?php echo $staff['id']; ?>, '<?php echo htmlspecialchars($staff['name']); ?>')">
                                                                <i class="mdi mdi-pencil-outline"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-warning" title="Block Access"
                                                                onclick="blockStaff(<?php echo $staff['id']; ?>, '<?php echo htmlspecialchars($staff['name']); ?>')">
                                                                <i class="mdi mdi-block-helper"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-danger"
                                                                title="Delete Staff Record"
                                                                onclick="deleteStaff(<?php echo $staff['id']; ?>, '<?php echo htmlspecialchars($staff['name']); ?>')">
                                                                <i class="mdi mdi-trash-can-outline"></i>
                                                            </button>
                                                        </div>
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
    
    <div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStaffModalLabel">Add New Staff Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addStaffForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="staff-name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="staff-name" required placeholder="Ali Bin Abu">
                            </div>
                            <div class="col-md-6">
                                <label for="staff-position" class="form-label">Specific Position</label>
                                <input type="text" class="form-control" id="staff-position" required placeholder="Senior Cashier">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="staff-username" class="form-label">Username (Login ID)</label>
                                <input type="text" class="form-control" id="staff-username" required placeholder="ali.abu">
                            </div>
                            <div class="col-md-6">
                                <label for="staff-password" class="form-label">Temporary Password</label>
                                <input type="password" class="form-control" id="staff-password" required placeholder="Must be 8 characters">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="staff-role" class="form-label">Group / Role (Role-Based Access Control)</label>
                                <select class="form-select" id="staff-role" required>
                                    <option value="">--- Select Group/Role ---</option>
                                    <option value="Cashier">Cashier</option>
                                    <option value="Pharmacist">Pharmacist</option>
                                    <option value="Pharmacist Asst.">Pharmacist Asst.</option>
                                    <option value="Admin/Supervisor">Admin/Supervisor</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="staff-status" class="form-label">Initial Status</label>
                                <select class="form-select" id="staff-status" required>
                                    <option value="active">Active</option>
                                    <option value="blocked">Blocked</option>
                                    <option value="on-leave">On Leave</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="staff-permissions" class="form-label">Group Permissions / Access Control (Hold Ctrl/Cmd to select multiple)</label>
                                <select class="form-select" id="staff-permissions" multiple size="4">
                                    <option value="Sales_View">View Sales Reports</option>
                                    <option value="Sales_Modify">Apply Discounts/Refunds</option>
                                    <option value="Inventory_View">View Inventory (all locations)</option>
                                    <option value="Inventory_Modify">Edit Stock Levels/Prices</option>
                                    <option value="Staff_View">View Staff Roster</option>
                                    <option value="Staff_Modify">Add/Delete Staff</option>
                                    <option value="POS_OpenClose">Open/Close POS Register</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <label for="staff-pos" class="form-label">Assigned POS Counters (Hold Ctrl/Cmd to select multiple)</label>
                                <select class="form-select" id="staff-pos" multiple size="4">
                                    <option value="Counter 1 (Prescription)">Counter 1 (Prescription)</option>
                                    <option value="Counter 2 (OTC/Express)">Counter 2 (OTC/Express)</option>
                                    <option value="Counter 3 (Supervisor)">Counter 3 (Supervisor)</option>
                                    <option value="None">None (No default POS)</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitNewStaff()">Save Staff</button>
                </div>
            </div>
        </div>
    </div>
    <?php include_once 'views/footer_libraries.php'; ?>
    <script src="assets/js/app.js"></script>
    <script>
    // New function to handle form submission for Bootstrap Modal
    function submitNewStaff() {
        // 1. Collect data from the Bootstrap modal form fields
        const username = document.getElementById('staff-username').value;
        const password = document.getElementById('staff-password').value;
        const name = document.getElementById('staff-name').value;
        const position = document.getElementById('staff-position').value;
        const role = document.getElementById('staff-role').value;
        const status = document.getElementById('staff-status').value;
        
        // Get selected Permissions from the new multi-select
        const permSelect = document.getElementById('staff-permissions');
        const selectedPermissions = Array.from(permSelect.options)
            .filter(option => option.selected)
            .map(option => option.value);
            
        // Get selected POS counters from the multi-select
        const posSelect = document.getElementById('staff-pos');
        const selectedPos = Array.from(posSelect.options)
            .filter(option => option.selected)
            .map(option => option.value);

        // 2. Basic validation
        if (!username || !password || !name || !position || !role) {
            Swal.fire('Validation Error', 'Please fill in all required fields (Name, Position, Username, Password, and Group/Role).', 'error');
            return;
        }

        // 3. Hide the Bootstrap modal
        const modalElement = document.getElementById('addStaffModal');
        const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        modal.hide();

        // 4. Show success message using SweetAlert for better UI feedback
        const posString = selectedPos.length > 0 ? selectedPos.join(', ') : 'None';
        const permString = selectedPermissions.length > 0 ? selectedPermissions.join(', ') : 'Default Role Permissions';
        
        Swal.fire(
            'Staff Provisioned!',
            `**Name:** ${name}<br>` +
            `**Group/Role:** ${role}<br>` +
            `**Position:** ${position}<br>` +
            `**Username:** ${username}<br>` +
            `**Password:** ${password} (TEMP)<br>` + // Highlighting temporary password for security
            `**Initial Status:** ${status}<br>` +
            `**Group Permissions:** ${permString}<br>` + // Displaying selected permissions
            `**Assigned POS:** ${posString}<br>` +
            `(Demo Success: Data sent to server for creation)`,
            'success'
        );
        
        // In a real app, send the data object (including permissions) to the server here.
    }

    // The function used by the button (remains simple, just opening the modal)
    function addStaff() {
        // This function is generally not needed as the button uses data-bs-target, 
        // but it can be used for custom logic before opening the modal.
    }

    function editStaff(id, name) {
        Swal.fire({
            title: `Edit Assignment for ${name}`,
            html: '<select id="edit-pos" class="swal2-select" style="width: 80%;">' +
                '<option value="Counter 1 (Prescription)">Counter 1 (Prescription)</option>' +
                '<option value="Counter 2 (OTC/Express)">Counter 2 (OTC/Express)</option>' +
                '<option value="Counter 3 (Supervisor)">Counter 3 (Supervisor)</option>' +
                '<option value="None">None</option>' +
                '</select>',
            focusConfirm: false,
            preConfirm: () => {
                return document.getElementById('edit-pos').value;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Updated!',
                    `${name} (ID: ${id}) is now assigned to ${result.value}. (Demo Success)`,
                    'success'
                );
            }
        });
    }

    function blockStaff(id, name) {
        Swal.fire({
            title: 'Block User Access?',
            text: `Are you sure you want to block ${name}? They will not be able to log in to the POS system.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f1b44c', // Warning color
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Block!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Access Suspended!',
                    `${name}'s login access has been suspended. (Demo Success)`,
                    'success'
                );
            }
        });
    }

    function deleteStaff(id, name) {
        Swal.fire({
            title: 'Delete Staff Record?',
            text: `You won't be able to revert the deletion of ${name}'s record!`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#74788d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Deleted!',
                    `Staff record for ${name} (ID: ${id}) has been deleted. (Demo Success)`,
                    'success'
                );
            }
        });
    }
    </script>
</body>

</html>