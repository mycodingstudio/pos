<?php
$pageTitle = "Product Master"; 
include_once 'views/header.php'; // Assumes this includes Bootstrap/CSS/JS dependencies
?>

<!-- Bootstrap Icons CSS link & Lucide Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<?php
// --- MOCK DATA SETUP ---
$categories = ["Analgesics", "Antibiotics", "Cardiology", "Dermatology", "Vitamins", "Ophthalmology", "Vaccines", "Antiseptics"];
$forms = ["Tablet", "Capsule", "Suspension", "Injectable", "Cream", "Syrup"];
$uoms = ["Box", "Bottle", "Vial", "Tablet", "Pill", "Syringe", "Tube"];
$base_names = ["Paracetamol", "Amoxicillin", "Metformin", "Loratadine", "Amlodipine", "Omeprazole", "Ibuprofen", "Hydrocortisone", "Ciprofloxacin", "Ranitidine"];


function generateMockProducts($count = 100, $categories, $forms, $uoms, $base_names) {
    $products = [];
    $today = new DateTime();

    for ($i = 1; $i <= $count; $i++) {
        $category = $categories[array_rand($categories)];
        $form = $forms[array_rand($forms)];
        $base_name = $base_names[array_rand($base_names)];
        $variant = rand(10, 800) . 'mg';
        $product_code = strtoupper(substr($base_name, 0, 3)) . '-' . rand(100, 999) . '-' . $i;
        $status = (rand(1, 100) > 90) ? 'Discontinued' : 'Active';

        // Inventory fields for KPI calculation
        $in_stock = rand(100, 50000);
        $low_stock_threshold = rand(1000, 5000);

        // UOMs
        $purchase_uom = $uoms[array_rand($uoms)];
        $sales_uom = $uoms[array_rand($uoms)];

        $products[] = [
            "id" => $i,
            "product_name" => $base_name . ' ' . $variant,
            "product_code" => $product_code,
            "category" => $category,
            "form" => $form,
            "retail_price" => round(rand(50, 500) / 100, 2), // $0.50 - $5.00
            "purchase_uom" => $purchase_uom,
            "sales_uom" => $sales_uom,
            "status" => $status,
            "in_stock" => $in_stock,
            "low_stock_threshold" => $low_stock_threshold,
            // Simplified fields for filtering
            "data-search" => strtolower($base_name . ' ' . $variant . ' ' . $product_code),
            "data-category" => $category,
            "data-status" => $status
        ];
    }
    return $products;
}

$products = generateMockProducts(100, $categories, $forms, $uoms, $base_names);

// --- KPI CALCULATION ---
$totalProducts = count($products);
$criticalAlerts = 5;
$expiringSoon = 0; // Mocking this without full batch logic, just counting items below a stock threshold

foreach ($products as $product) {
    // Hit Alert logic (Critical Stock)
    if ($product['in_stock'] <= $product['low_stock_threshold']) {
        $criticalAlerts++;
    }
    
    // Mock Expiring Soon (For demonstration purposes, let 5% of products have an 'expiring' status)
    if (rand(1, 100) <= 5) {
        $expiringSoon++;
    }
}
$inventoryValue = 0;
foreach($products as $p) {
    $inventoryValue += $p['retail_price'] * $p['in_stock'];
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

                    <!-- START Card Row for Product Info -->
                    <div class="row">
                        <!-- Total Product Card -->
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="float-end mt-2">
                                        <i data-lucide="package" class="h3 text-success"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 mt-1"><span>3</span></h4>
                                        <p class="text-muted mb-0">Total Products/SKUs</p>
                                    </div>

                                </div>
                            </div>
                        </div> <!-- end col-->

                        <!-- Hit Alert (Critical Stock) Card -->
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="float-end mt-2">
                                        <i data-lucide="alert-triangle" class="h3 text-warning"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 mt-1"><span>2</span></h4>
                                        <p class="text-muted mb-0">Critical Stock Alerts</p>
                                    </div>

                                </div>
                            </div>
                        </div> <!-- end col-->
                        

                    </div>
                    <!-- END Card Row for Product Info -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    
                                    <!-- FILTER/ACTION ROW -->
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-md-4 col-sm-12 mb-2">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                                <input type="text" class="form-control" id="search-product" placeholder="Search Product Name or Code..." onkeyup="applyFilters(1)">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-6 mb-2">
                                            <select class="form-select" id="filter-category" onchange="applyFilters(1)">
                                                <option value="">All Categories</option>
                                                <?php foreach($categories as $cat): ?>
                                                    <option value="<?= $cat ?>"><?= $cat ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-6 mb-2">
                                            <select class="form-select" id="filter-status" onchange="applyFilters(1)">
                                                <option value="">All Statuses</option>
                                                <option value="Active">Active</option>
                                                <option value="Discontinued">Discontinued</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-6 mb-2">
                                            <button class="btn btn-secondary w-100" onclick="exportToCsv()">
                                                <i class="bi bi-file-earmark-arrow-down"></i> Export
                                            </button>
                                        </div>
                                        <div class="col-md-2 col-sm-6 mb-2">
                                            <button class="btn btn-primary w-100" onclick="addProduct()">
                                                <i class="bi bi-plus-circle"></i> Add Product
                                            </button>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="productTable" class="table table-striped table-hover table-bordered mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center" style="width: 50px;">#</th>
                                                    <th>Product/SKU</th>
                                                    <th>Category</th>
                                                    <th>Form</th>
                                                    <th class="text-end">Retail Price</th>
                                                    <th>Purchase UOM</th>
                                                    <th>Sales UOM</th>
                                                    <th>Status</th>
                                                    <th class="text-center" style="width: 80px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($products as $i => $product): ?>
                                                    <tr 
                                                        data-id="<?= $product['id'] ?>" 
                                                        data-search="<?= $product['data-search'] ?>"
                                                        data-category="<?= $product['data-category'] ?>"
                                                        data-status="<?= $product['data-status'] ?>"
                                                    >
                                                        <td class="text-center"><?= $i + 1 ?></td>
                                                        <td>
                                                            <h6 class="text-truncate font-size-14 mb-0"><?= htmlspecialchars($product['product_name']) ?></h6>
                                                            <p class="text-muted mb-0"><small>SKU: <?= htmlspecialchars($product['product_code']) ?></small></p>
                                                        </td>
                                                        <td><span class="badge bg-soft-info text-info p-2"><?= htmlspecialchars($product['category']) ?></span></td>
                                                        <td><?= htmlspecialchars($product['form']) ?></td>
                                                        <td class="text-end fw-bold">$<?= number_format($product['retail_price'], 2) ?></td>
                                                        <td><?= htmlspecialchars($product['purchase_uom']) ?></td>
                                                        <td><?= htmlspecialchars($product['sales_uom']) ?></td>
                                                        <td>
                                                            <span class="badge bg-<?= $product['status'] === 'Active' ? 'success' : 'danger' ?> p-2"><?= htmlspecialchars($product['status']) ?></span>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="bi bi-three-dots"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    <li><a class="dropdown-item" href="#" onclick="viewDetails(<?= $product['id'] ?>)"><i class="bi bi-eye me-2"></i>View</a></li>
                                                                    <li><a class="dropdown-item" href="#" onclick="editProduct(<?= $product['id'] ?>)"><i class="bi bi-pencil-square me-2"></i>Edit</a></li>
                                                                    <li><hr class="dropdown-divider"></li>
                                                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteProduct(<?= $product['id'] ?>, '<?= htmlspecialchars(addslashes($product['product_name'])) ?>')"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div> <!-- end .table-responsive -->

                                    <!-- PAGINATION SECTION -->
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-3">
                                        <div id="pagination-info" class="text-muted text-center text-md-start w-100 w-md-auto mb-2 mb-md-0"></div>
                                        <div id="pagination-controls-wrapper" class="w-100 d-flex justify-content-center">
                                            <div id="pagination-controls">
                                                <!-- Pagination links will be inserted here by JavaScript -->
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

    <!-- START ADD PRODUCT MODAL -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">
                        <i class="bi bi-box-seam me-2"></i> Add New Product Master
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm">
                        <div class="row">
                            <!-- Product Name -->
                            <div class="col-md-6 mb-3">
                                <label for="productName" class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="productName" required>
                            </div>
                            <!-- SKU / Product Code -->
                            <div class="col-md-6 mb-3">
                                <label for="productCode" class="form-label">SKU / Product Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="productCode" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Category -->
                            <div class="col-md-6 mb-3">
                                <label for="productCategory" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" id="productCategory" required>
                                    <option value="">Select Category</option>
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?= $cat ?>"><?= $cat ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Form -->
                            <div class="col-md-6 mb-3">
                                <label for="productForm" class="form-label">Form/Type</label>
                                <select class="form-select" id="productForm">
                                    <option value="">Select Form</option>
                                    <?php foreach($forms as $form): ?>
                                        <option value="<?= $form ?>"><?= $form ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Retail Price -->
                            <div class="col-md-4 mb-3">
                                <label for="retailPrice" class="form-label">Retail Price ($) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="retailPrice" min="0" step="0.01" value="0.00" required>
                            </div>
                            <!-- Purchase UOM -->
                            <div class="col-md-4 mb-3">
                                <label for="purchaseUOM" class="form-label">Purchase UOM</label>
                                <select class="form-select" id="purchaseUOM">
                                    <option value="">Select UOM</option>
                                    <?php foreach($uoms as $uom): ?>
                                        <option value="<?= $uom ?>"><?= $uom ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Sales UOM -->
                            <div class="col-md-4 mb-3">
                                <label for="salesUOM" class="form-label">Sales UOM</label>
                                <select class="form-select" id="salesUOM">
                                    <option value="">Select UOM</option>
                                    <?php foreach($uoms as $uom): ?>
                                        <option value="<?= $uom ?>"><?= $uom ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Status Checkbox -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="productStatusActive" checked>
                            <label class="form-check-label" for="productStatusActive">Product is Active</label>
                        </div>

                        <div class="text-end pt-3 border-top">
                            <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save me-1"></i> Save Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END ADD PRODUCT MODAL -->

    <?php include_once 'views/footer_libraries.php'; ?>
 <script src="assets/js/app.js"></script>
    <!-- Required for SweetAlert and Mock Data/Functions -->
    <script src="assets/js/app.js"></script> 
    <script>
    // Store all product rows for filtering
    const allRows = Array.from(document.getElementById('productTable').tBodies[0].rows);
    const itemsPerPage = 10;
    let currentPage = 1;

    // Initialize the Bootstrap Modal instance
    const addProductModal = new bootstrap.Modal(document.getElementById('addProductModal'));
    const addProductForm = document.getElementById('addProductForm');
    
    // --- PAGINATION FUNCTIONS ---
    function renderPage(rowsToRender, page) {
        const totalItems = rowsToRender.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;

        // Hide all rows initially
        allRows.forEach(row => row.style.display = 'none');

        // Display rows for the current page
        for (let i = start; i < end && i < totalItems; i++) {
            rowsToRender[i].style.display = '';
        }

        const infoDiv = document.getElementById('pagination-info');
        if (totalItems === 0) {
            infoDiv.textContent = 'Showing 0 to 0 of 0 entries';
        } else {
            const startIndex = start + 1;
            const endIndex = Math.min(end, totalItems);
            infoDiv.textContent = `Showing ${startIndex} to ${endIndex} of ${totalItems} entries`;
        }

        // Update the serial number column
        let currentDisplayIndex = 0;
        for (let i = 0; i < totalItems; i++) {
            if (rowsToRender[i].style.display !== 'none') {
                rowsToRender[i].cells[0].textContent = start + 1 + currentDisplayIndex;
                currentDisplayIndex++;
            }
        }
    }

    function initPagination(totalItems, activePage) {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const controlsDiv = document.getElementById('pagination-controls');
        controlsDiv.innerHTML = ''; // Clear previous controls

        if (totalPages <= 1) return;

        const ul = document.createElement('ul');
        ul.className = 'pagination pagination-sm mb-0';

        const createListItem = (text, pageNum, isActive, isDisabled) => {
            const li = document.createElement('li');
            li.className = `page-item ${isActive ? 'active' : ''} ${isDisabled ? 'disabled' : ''}`;
            
            const a = document.createElement('a');
            a.className = 'page-link';
            a.href = 'javascript:void(0)';
            a.textContent = text;
            
            if (!isDisabled && !isActive) {
                a.onclick = () => applyFilters(pageNum);
            }
            
            li.appendChild(a);
            return li;
        };

        ul.appendChild(createListItem('Previous', activePage - 1, false, activePage === 1));

        let startPage = Math.max(1, activePage - 2);
        let endPage = Math.min(totalPages, activePage + 2);

        if (activePage <= 3) {
            endPage = Math.min(totalPages, 5);
            startPage = 1;
        } else if (activePage > totalPages - 2) {
            startPage = Math.max(1, totalPages - 4);
            endPage = totalPages;
        }

        if (startPage > 1) {
            ul.appendChild(createListItem('1', 1, false, false));
            if (startPage > 2) {
                ul.appendChild(createListItem('...', 0, false, true));
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            ul.appendChild(createListItem(i.toString(), i, i === activePage, false));
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                ul.appendChild(createListItem('...', 0, false, true));
            }
            ul.appendChild(createListItem(totalPages.toString(), totalPages, false, false));
        }

        ul.appendChild(createListItem('Next', activePage + 1, false, activePage === totalPages));

        controlsDiv.appendChild(ul);
        currentPage = activePage;
    }

    // --- FILTERING FUNCTION ---
    function applyFilters(page = 1) {
        const search = document.getElementById('search-product').value.toLowerCase().trim();
        const categoryFilter = document.getElementById('filter-category').value;
        const statusFilter = document.getElementById('filter-status').value;
        
        const filteredRows = allRows.filter(row => {
            const searchTerm = row.getAttribute('data-search');
            const category = row.getAttribute('data-category');
            const status = row.getAttribute('data-status');
            
            let matchesSearch = search === '' || searchTerm.includes(search);
            let matchesCategory = categoryFilter === '' || category === categoryFilter;
            let matchesStatus = statusFilter === '' || status === statusFilter;

            return matchesSearch && matchesCategory && matchesStatus;
        });

        const totalPages = Math.max(1, Math.ceil(filteredRows.length / itemsPerPage));
        if (page > totalPages) {
            page = 1;
        }
        currentPage = page;

        initPagination(filteredRows.length, currentPage);
        renderPage(filteredRows, currentPage);
    }

    // --- ACTION FUNCTIONS ---

    function addProduct() {
        // Reset form fields before showing the modal
        addProductForm.reset();
        addProductModal.show();
    }

    addProductForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Stop default form submission

        // Collect data
        const newProduct = {
            productName: document.getElementById('productName').value,
            productCode: document.getElementById('productCode').value,
            category: document.getElementById('productCategory').value,
            retailPrice: document.getElementById('retailPrice').value,
            status: document.getElementById('productStatusActive').checked ? 'Active' : 'Discontinued'
        };

        // Close modal
        addProductModal.hide();

        // Mock action: Show success message
        Swal.fire({
            title: 'Product Created!',
            html: `Product: <b>${newProduct.productName}</b> (${newProduct.productCode})<br>
                   Category: ${newProduct.category}<br>
                   Price: $${newProduct.retailPrice}<br>
                   Status: ${newProduct.status}`,
            icon: 'success',
            confirmButtonText: 'View List'
        }).then(() => {
            console.log("New Product Data:", newProduct);
            // A real app would update the table data here
        });
    });

    function viewDetails(id) {
        Swal.fire('Product Details', `Showing details for Product ID: ${id}. (Mock Action)`, 'info');
    }

    function editProduct(id) {
        Swal.fire('Edit Product', `Opening edit form for Product ID: ${id}. (Mock Action)`, 'warning');
    }

    function deleteProduct(id, name) {
        Swal.fire({
            title: 'Confirm Deletion',
            text: `Are you sure you want to delete "${name}"? This cannot be undone.`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#74788d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Deleted!', `Product ID ${id} has been deleted. (Mock Action)`, 'success');
                // A real app would remove the row from the DOM and update KPIs here
            }
        });
    }

    function exportToCsv() {
        const filteredRows = allRows.filter(row => row.style.display !== 'none');
        
        if (filteredRows.length === 0) {
            Swal.fire('Export Failed', 'No products to export based on current filters.', 'warning');
            return;
        }
        a
        // This is a placeholder for the actual export logic which is too long for this script block
        Swal.fire('Export Successful', `Exported ${filteredRows.length} product records to CSV. (Mock Export)`, 'success');
    }

    // Initial call to apply filters
    $(document).ready(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons(); // Initialize Lucide Icons for the new cards
        }
        applyFilters(1);
    });
    </script>
</body>
</html>