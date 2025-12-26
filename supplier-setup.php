<?php
$pageTitle = "Supplier Setup & Management";
include_once 'views/header.php';

// --- MOCK DATA ---
$supplierList = [
    [
        "id" => 5001,
        "name" => "Pharma Distributors Sdn Bhd",
        "contact_person" => "Ms. Lee",
        "phone" => "03-11223344",
        "email" => "sales@pharmadist.com",
        "term" => "Net 30 Days",
        "status" => "Active"
    ],
    [
        "id" => 5002,
        "name" => "Health Devices (M) Corp",
        "contact_person" => "Mr. Ahmad",
        "phone" => "04-55667788",
        "email" => "ahmad@healthdev.my",
        "term" => "Cash On Delivery (COD)",
        "status" => "Active"
    ],
    [
        "id" => 5003,
        "name" => "Discount OTC Wholesaler",
        "contact_person" => "David Tan",
        "phone" => "07-98765432",
        "email" => "david@otcwholesale.net",
        "term" => "Net 45 Days",
        "status" => "Active"
    ],
    [
        "id" => 5004,
        "name" => "Old School Supplier Ltd.",
        "contact_person" => "Vendor 4",
        "phone" => "018-7778889",
        "email" => "vendor4@mail.com",
        "term" => "Net 60 Days",
        "status" => "Inactive"
    ],
];

$paymentTerms = ['Net 7 Days', 'Net 15 Days', 'Net 30 Days', 'Net 45 Days', 'Net 60 Days', 'Cash On Delivery (COD)'];

// Helper to determine status badge color
function getStatusBadgeClass($status) {
    return $status === 'Active' ? 'bg-success' : 'bg-danger';
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Purchase</a></li>
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
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h4 class="card-title">Registered Suppliers (<?php echo count($supplierList); ?>)</h4>
                                        <button class="btn btn-sm btn-primary" onclick="openAddSupplierModal()">
                                            <i class="uil uil-plus me-1"></i> Add New Supplier
                                        </button>
                                    </div>

                                    <div class="row mb-3 g-2">
                                        <div class="col-md-3">
                                            <select class="form-select form-select-sm" id="term-filter">
                                                <option value="">Filter by Payment Term</option>
                                                <?php foreach ($paymentTerms as $term): ?>
                                                    <option value="<?php echo $term; ?>"><?php echo $term; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select form-select-sm" id="status-filter">
                                                <option value="">Filter by Status</option>
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 d-flex">
                                            <input type="text" class="form-control form-control-sm" placeholder="Search Supplier Name or Contact...">
                                            <button class="btn btn-sm btn-secondary ms-2"><i class="uil uil-search"></i></button>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover align-middle table-nowrap mb-0" id="supplierTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" style="width: 50px;">ID</th>
                                                    <th scope="col">Supplier Name</th>
                                                    <th scope="col">Contact Person</th>
                                                    <th scope="col">Phone / Email</th>
                                                    <th scope="col" style="width: 150px;">Payment Term</th>
                                                    <th scope="col" class="text-center">Status</th>
                                                    <th scope="col" style="width: 120px;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($supplierList as $supplier): ?>
                                                <tr>
                                                    <td><?php echo $supplier['id']; ?></td>
                                                    <td class="fw-bold"><?php echo htmlspecialchars($supplier['name']); ?></td>
                                                    <td><?php echo htmlspecialchars($supplier['contact_person']); ?></td>
                                                    <td>
                                                        <i class="uil uil-phone me-1"></i><?php echo htmlspecialchars($supplier['phone']); ?><br>
                                                        <i class="uil uil-envelope me-1"></i><?php echo htmlspecialchars($supplier['email']); ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($supplier['term']); ?></td>
                                                    <td class="text-center">
                                                        <span class="badge <?php echo getStatusBadgeClass($supplier['status']); ?>">
                                                            <?php echo $supplier['status']; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-warning" title="Edit Supplier" onclick="editSupplier(<?php echo $supplier['id']; ?>)">
                                                            <i class="uil uil-pen"></i>
                                                        </button>
                                                        <a href="supplier-ledger.php?id=<?php echo $supplier['id']; ?>" class="btn btn-sm btn-outline-info ms-1" title="View Ledger/History">
                                                            <i class="uil uil-history"></i>
                                                        </a>
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
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Supplier Setup Page Loaded.');
        });
        
        // --- Supplier Profile Functions ---
        function openAddSupplierModal() {
            alert('Simulated: Opening modal for "Add New Supplier" profile and contact details.');
            // In a real application, you would launch a modal/dialog here
        }

        function editSupplier(id) {
            alert(`Simulated: Opening modal to edit Supplier ID ${id}'s profile.`);
        }
    </script>
</body>

</html>