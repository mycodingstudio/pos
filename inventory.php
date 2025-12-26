<?php
$pageTitle = "Inventory Management Hub";
// The 'views/header.php' is assumed to include necessary links like Bootstrap CSS, Tailwind utility classes (if applicable), and possibly Lucide Icons script.
include_once 'views/header.php'; 
?>

<!-- Include Bootstrap Icons CSS for a modern look -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<?php
// --- MOCK DATA SETUP ---
$categories = ["Raw Materials", "Finished Goods", "Packaging", "Spare Parts", "Chemicals", "Office Supplies"];
$uoms = ["Unit", "Box", "KG", "Litre", "Meter", "Pcs"];
$item_names = ["Steel Rod (Grade 304)", "Polypropylene Resin", "Motor Oil (Synthetic)", "A4 Paper Ream", "Safety Gloves (Box)", "Circuit Board (XYZ Model)"];

/**
 * Generates mock inventory data for demonstration purposes.
 * Includes logic for stock alerts and aging simulation.
 */
function generateMockInventoryItems($count = 100, $categories, $uoms, $item_names) {
    $items = [];
    $criticalAlerts = 0;
    $agingAlerts = 0;

    for ($i = 1; $i <= $count; $i++) {
        $category = $categories[array_rand($categories)];
        $base_name = $item_names[array_rand($item_names)];
        $sku = strtoupper(substr($base_name, 0, 3)) . '-' . rand(100, 999) . '-' . $i;

        // Inventory fields for KPI calculation
        $in_stock = rand(100, 50000);
        $low_stock_threshold = rand(1000, 5000);
        $avg_cost = round(rand(500, 50000) / 100, 2); // $5.00 - $500.00
        
        $uom = $uoms[array_rand($uoms)];
        $status = (rand(1, 100) > 95) ? 'Discontinued' : 'Active';

        // Mock aging: Set a last activity date that is old for 10% of items
        $is_old_stock = rand(1, 10) === 1; // 10% chance of old stock (Stock Aging)
        if ($is_old_stock) {
            $last_activity_days = rand(180, 720); // 6 months to 2 years
            $agingAlerts++;
        } else {
            $last_activity_days = rand(1, 90); // Last 3 months
        }
        $last_activity = (new DateTime())->modify("-{$last_activity_days} days")->format('Y-m-d');


        $item = [
            "id" => $i,
            "item_name" => $base_name . ' (' . $sku . ')',
            "sku" => $sku,
            "category" => $category,
            "uom" => $uom,
            "avg_cost" => $avg_cost,
            "in_stock" => $in_stock,
            "low_stock_threshold" => $low_stock_threshold,
            "last_activity" => $last_activity,
            "status" => $status,
            // Data attributes for JS filtering
            "data-search" => strtolower($base_name . ' ' . $sku),
            "data-category" => $category,
            "data-status" => $status,
            "is_aging" => $is_old_stock ? 'Yes' : 'No'
        ];
        
        if ($in_stock <= $low_stock_threshold) {
            $criticalAlerts++;
        }
        $items[] = $item;
    }
    return ['items' => $items, 'criticalAlerts' => $criticalAlerts, 'agingAlerts' => $agingAlerts];
}

$mockData = generateMockInventoryItems(100, $categories, $uoms, $item_names);
$inventoryItems = $mockData['items'];
$totalItems = count($inventoryItems);
$criticalAlerts = $mockData['criticalAlerts'];
$agingAlerts = $mockData['agingAlerts'];
$inventoryValue = array_sum(array_map(fn($item) => $item['avg_cost'] * $item['in_stock'], $inventoryItems));

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
                    
                    <h5 class="mb-3 text-primary"><i class="bi bi-speedometer2 me-2"></i> Inventory Overview & KPIs</h5>
                    <!-- START KPI Card Row for Inventory Info -->
                    <div class="row">
                        <!-- Total Products Card -->
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="float-end mt-2">
                                        <i data-lucide="package" class="h3 text-success"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 mt-1"><span data-plugin="counterup"><?php echo number_format($totalItems); ?></span></h4>
                                        <p class="text-muted mb-0">Total Active Items (SKUs)</p>
                                    </div>
                                    <p class="text-muted mt-3 mb-0">
                                        <span class="text-success me-1"><i class="mdi mdi-arrow-up-bold me-1"></i>0.8%</span> MoM Growth
                                    </p>
                                </div>
                            </div>
                        </div> <!-- end col-->

                        <!-- Low Stock Alerts Card (Addresses Minimum Price Control implicitly) -->
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="float-end mt-2">
                                        <i data-lucide="alert-triangle" class="h3 text-warning"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 mt-1"><span data-plugin="counterup"><?php echo number_format($criticalAlerts); ?></span></h4>
                                        <p class="text-muted mb-0">Low Stock/Reorder Alerts</p>
                                    </div>
                                    <p class="text-muted mt-3 mb-0">
                                        <span class="text-danger me-1"><i class="mdi mdi-alert-circle me-1"></i><?php echo $totalItems > 0 ? (round($criticalAlerts / $totalItems * 100)) : 0; ?>%</span> of inventory affected
                                    </p>
                                </div>
                            </div>
                        </div> <!-- end col-->
                        
                        <!-- Stock Aging Alert Card -->
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="float-end mt-2">
                                        <i data-lucide="calendar-off" class="h3 text-danger"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 mt-1"><span data-plugin="counterup"><?php echo number_format($agingAlerts); ?></span></h4>
                                        <p class="text-muted mb-0">Aged Stock Items (>6 Months)</p>
                                    </div>
                                    <p class="text-muted mt-3 mb-0">
                                        <span class="text-info me-1"><i class="mdi mdi-arrow-right-bold me-1"></i>Review for Write-Off/Sale</span>
                                    </p>
                                </div>
                            </div>
                        </div> <!-- end col-->

                        <!-- Estimated Inventory Value -->
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="float-end mt-2">
                                        <i data-lucide="wallet" class="h3 text-primary"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 mt-1">$<span data-plugin="counterup"><?php echo number_format($inventoryValue / 1000, 1); ?></span>k</h4>
                                        <p class="text-muted mb-0">Estimated Inventory Value</p>
                                    </div>
                                    <p class="text-muted mt-3 mb-0">
                                        <span class="text-info me-1"><i class="mdi mdi-trending-up me-1"></i>Based on Avg Cost</span>
                                    </p>
                                </div>
                            </div>
                        </div> <!-- end col-->
                    </div>
                    <!-- END KPI Card Row for Inventory Info -->
                    
                    <h5 class="mb-3 mt-4 text-primary"><i class="bi bi-journal-check me-2"></i> Inventory Workflow Modules</h5>
                    <p class="text-muted">Navigate to key inventory processes such as receiving, counting, and adjusting stock levels.</p>
                    
                    <!-- Diagram of Inventory Flow -->
                    <div class="mb-4">
                        [Image of Inventory Management Flowchart]
                    </div>
                    <!-- END Diagram of Inventory Flow -->

                    <!-- START Module Navigation Row -->
                    <div class="row mb-4">
                        <?php 
                        $modules = [
                            // Stock Setup & Configuration (Includes Barcode, Multi-level pricing, Minimum Price Control, Category settings)
                            ['title' => 'Stock Setup', 'icon' => 'settings-2', 'color' => 'success', 'desc' => 'Configure new items, barcodes, UOMs, and multi-level pricing rules.'],
                            // Stock Opening
                            ['title' => 'Stock Opening', 'icon' => 'plus-circle', 'color' => 'info', 'desc' => 'Set initial stock quantities and balances when introducing a new item or system.'],
                            // Stock Receive
                            ['title' => 'Stock Receive', 'icon' => 'inbox', 'color' => 'primary', 'desc' => 'Record incoming stock/shipments from suppliers (Goods Received Notes).'],
                            // Stock Issue
                            ['title' => 'Stock Issue', 'icon' => 'outbox', 'color' => 'secondary', 'desc' => 'Record stock moving out for production, internal consumption, or fulfilling sales orders.'],
                            // Stock Adjustment
                            ['title' => 'Stock Adjustment', 'icon' => 'sliders-horizontal', 'color' => 'warning', 'desc' => 'Correct stock levels for minor discrepancies, damage reports, or location transfers.'],
                            // Stock Take
                            ['title' => 'Stock Take', 'icon' => 'clipboard-check', 'color' => 'danger', 'desc' => 'Start and finalize physical inventory counting and reconciliation of variances.'],
                            // Stock Write Off
                            ['title' => 'Stock Write Off', 'icon' => 'trash-2', 'color' => 'dark', 'desc' => 'Dispose of damaged, expired, or obsolete inventory, recording the loss.'],
                            // Stock Aging Report (Detailed)
                            ['title' => 'Stock Aging Report', 'icon' => 'bar-chart', 'color' => 'purple', 'desc' => 'Generate detailed reports on the age and movement history of stock for review.'],
                        ];
                        foreach ($modules as $module): ?>
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                <div class="card border border-<?= $module['color'] ?> shadow-sm h-100 cursor-pointer module-card" 
                                     onclick="showModuleModal('<?= $module['title'] ?>', '<?= $module['desc'] ?>')">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar-sm me-3">
                                                <span class="avatar-title bg-soft-<?= $module['color'] ?> text-<?= $module['color'] ?> rounded-circle font-size-18">
                                                    <i data-lucide="<?= $module['icon'] ?>"></i>
                                                </span>
                                            </div>
                                            <h5 class="font-size-15 mb-0 text-<?= $module['color'] ?>"><?= $module['title'] ?></h5>
                                        </div>
                                        <p class="text-muted mb-0 font-size-13"><?= $module['desc'] ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- END Module Navigation Row -->
                    
                    <h5 class="mb-3 mt-4 text-primary"><i class="bi bi-table me-2"></i> Stock Listing & Balance Enquiry</h5>
                    <p class="text-muted">A comprehensive list of all stock items, their current balances, and quick filtering for aging stock.</p>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    
                                    <!-- FILTER/ACTION ROW -->
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-md-4 col-sm-12 mb-2">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                                <input type="text" class="form-control" id="search-item" placeholder="Search Item Name or SKU..." onkeyup="applyFilters(1)">
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
                                        <!-- Filter for Stock Aging -->
                                        <div class="col-md-2 col-sm-6 mb-2">
                                            <select class="form-select" id="filter-aging" onchange="applyFilters(1)">
                                                <option value="">All Stock</option>
                                                <option value="Yes">Aging Stock Only</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-6 mb-2">
                                            <button class="btn btn-secondary w-100" onclick="exportToCsv()">
                                                <i class="bi bi-file-earmark-arrow-down"></i> Export
                                            </button>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="inventoryTable" class="table table-striped table-hover table-bordered mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center" style="width: 50px;">#</th>
                                                    <th>Item Name / SKU</th>
                                                    <th>Category</th>
                                                    <th class="text-end">Avg Cost</th>
                                                    <th class="text-center">UOM</th>
                                                    <th class="text-end">Stock Balance</th>
                                                    <th class="text-end">Value</th>
                                                    <th class="text-center">Last Activity</th>
                                                    <th class="text-center" style="width: 80px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($inventoryItems as $i => $item): ?>
                                                    <?php 
                                                        $isLowStock = $item['in_stock'] <= $item['low_stock_threshold'];
                                                        $isAging = $item['is_aging'] === 'Yes';
                                                        $rowClass = '';
                                                        if ($isLowStock) {
                                                            $rowClass = 'table-warning'; // Low stock highlight
                                                        } elseif ($isAging) {
                                                            $rowClass = 'table-danger'; // Aging stock highlight
                                                        }
                                                        $value = $item['in_stock'] * $item['avg_cost'];
                                                    ?>
                                                    <tr 
                                                        class="<?= $rowClass ?>"
                                                        data-id="<?= $item['id'] ?>" 
                                                        data-search="<?= $item['data-search'] ?>"
                                                        data-category="<?= $item['data-category'] ?>"
                                                        data-status="<?= $item['data-status'] ?>"
                                                        data-is-aging="<?= $item['is_aging'] ?>"
                                                    >
                                                        <td class="text-center"><?= $i + 1 ?></td>
                                                        <td>
                                                            <h6 class="text-truncate font-size-14 mb-0"><?= htmlspecialchars($item['item_name']) ?></h6>
                                                            <p class="text-muted mb-0"><small>SKU: <?= htmlspecialchars($item['sku']) ?></small></p>
                                                        </td>
                                                        <td><span class="badge bg-soft-info text-info p-2"><?= htmlspecialchars($item['category']) ?></span></td>
                                                        <td class="text-end fw-bold">$<?= number_format($item['avg_cost'], 2) ?></td>
                                                        <td class="text-center"><?= htmlspecialchars($item['uom']) ?></td>
                                                        <td class="text-end fw-bold">
                                                            <?= number_format($item['in_stock']) ?>
                                                            <?php if($isLowStock): ?>
                                                                <i class="bi bi-exclamation-triangle-fill text-warning ms-2" title="Below Reorder Threshold (<?= number_format($item['low_stock_threshold']) ?>)"></i>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-end">$<?= number_format($value, 2) ?></td>
                                                        <td class="text-center">
                                                            <?= htmlspecialchars($item['last_activity']) ?>
                                                            <?php if($isAging): ?>
                                                                <i class="bi bi-clock-history text-danger ms-2" title="Stock Age Alert (No activity in over 6 months)"></i>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <button class="btn btn-sm btn-info" onclick="viewDetails(<?= $item['id'] ?>)">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
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

    <!-- START MODULE DETAIL MODAL (Used for all transactional steps: Take, Adjustment, Issue, Receive, Write Off) -->
    <div class="modal fade" id="moduleDetailModal" tabindex="-1" aria-labelledby="moduleDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="moduleDetailModalLabel">Module Action: <span id="modal-title-text" class="text-primary"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modal-desc-text" class="lead text-muted"></p>
                    <div id="modal-content-area" class="mt-3">
                        <!-- Content specific to the module action will be inserted here -->
                    </div>
                    <p class="mt-4 alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i> In a full system, this would open a dedicated form/page to execute the **<span id="modal-action-text" class="fw-bold"></span>** process.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="executeModuleAction()">Go to <span id="modal-action-button-text">Form</span></button>
                </div>
            </div>
        </div>
    </div>
    <!-- END MODULE DETAIL MODAL -->


    <?php include_once 'views/footer_libraries.php'; ?>

    <!-- Required for SweetAlert and Mock Data/Functions -->
    <script src="assets/js/app.js"></script> 
    <script>
    // Store all inventory item rows for filtering
    const allRows = Array.from(document.getElementById('inventoryTable').tBodies[0].rows);
    const itemsPerPage = 15;
    let currentPage = 1;
    let currentFilteredRows = allRows; // Keep track of the currently filtered set

    // Initialize the Bootstrap Modal instance
    const moduleDetailModal = new bootstrap.Modal(document.getElementById('moduleDetailModal'));
    
    // --- PAGINATION FUNCTIONS ---
    function renderPage(rowsToRender, page) {
        const tableBody = document.getElementById('inventoryTable').tBodies[0];
        const totalItems = rowsToRender.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;

        // Hide all rows initially
        allRows.forEach(row => row.style.display = 'none');

        // Display rows for the current page
        let currentDisplayIndex = 0;
        for (let i = start; i < end && i < totalItems; i++) {
            const row = rowsToRender[i];
            row.style.display = '';
            // Update the serial number column
            row.cells[0].textContent = start + 1 + currentDisplayIndex;
            currentDisplayIndex++;
        }

        const infoDiv = document.getElementById('pagination-info');
        if (totalItems === 0) {
            infoDiv.textContent = 'Showing 0 to 0 of 0 entries';
        } else {
            const startIndex = start + 1;
            const endIndex = Math.min(end, totalItems);
            infoDiv.textContent = `Showing ${startIndex} to ${endIndex} of ${totalItems} entries`;
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

    // --- FILTERING FUNCTION (Stock Listing / Stock Balance / Stock Aging) ---
    function applyFilters(page = 1) {
        const search = document.getElementById('search-item').value.toLowerCase().trim();
        const categoryFilter = document.getElementById('filter-category').value;
        const statusFilter = document.getElementById('filter-status').value;
        const agingFilter = document.getElementById('filter-aging').value;
        
        currentFilteredRows = allRows.filter(row => {
            const searchTerm = row.getAttribute('data-search');
            const category = row.getAttribute('data-category');
            const status = row.getAttribute('data-status');
            const isAging = row.getAttribute('data-is-aging');

            let matchesSearch = search === '' || searchTerm.includes(search);
            let matchesCategory = categoryFilter === '' || category === categoryFilter;
            let matchesStatus = statusFilter === '' || status === statusFilter;
            let matchesAging = agingFilter === '' || isAging === agingFilter;

            return matchesSearch && matchesCategory && matchesStatus && matchesAging;
        });

        // If the current page is now beyond the new total number of pages, reset to page 1
        const totalPages = Math.max(1, Math.ceil(currentFilteredRows.length / itemsPerPage));
        if (page > totalPages) {
            page = 1;
        }
        currentPage = page;

        initPagination(currentFilteredRows.length, currentPage);
        renderPage(currentFilteredRows, currentPage);
    }

    // --- MODULE ACTION FUNCTIONS ---

    function showModuleModal(title, description) {
        // Update modal content
        document.getElementById('modal-title-text').textContent = title;
        document.getElementById('modal-desc-text').textContent = description;
        document.getElementById('modal-action-text').textContent = title;
        document.getElementById('modal-action-button-text').textContent = title.replace('Stock ', '');

        // Add specific content for certain modules if needed
        let contentArea = document.getElementById('modal-content-area');
        contentArea.innerHTML = '';
        
        let processDescription = '';
        
        if (title.includes('Stock Setup')) {
            processDescription = `
                <p class="fw-bold">Key Setup Points (Stock setup, Category settings, Barcode, Multi-level pricing, Minimum Price Control):</p>
                <ul>
                    <li>Define Item Master Data (Name, SKU, Barcode, UOM).</li>
                    <li>Assign Category and Location for organizational structure.</li>
                    <li>Configure Multiple Pricing Levels (e.g., Wholesale, Retail, Tier 3).</li>
                    <li>Set Minimum Price Control to prevent unauthorized discounts.</li>
                </ul>
            `;
        } else if (title.includes('Stock Take')) {
            processDescription = `
                <p class="fw-bold">Stock Take Process (Stock Take):</p>
                <ol>
                    <li>Generate Stock Take Sheet/Freeze Inventory Counts.</li>
                    <li>Count & Input Physical Quantity into the system.</li>
                    <li>Run Reconciliation Report to analyze variance.</li>
                    <li>Post Adjustment to correct system stock balance.</li>
                </ol>
            `;
        } else if (title.includes('Stock Receive')) {
            processDescription = `
                <p class="fw-bold">Stock Receive Process (Stock receive):</p>
                <ul>
                    <li>Reference Purchase Order (PO).</li>
                    <li>Validate received quantity against PO.</li>
                    <li>Record batch/lot numbers and expiry dates (if applicable).</li>
                    <li>Update system stock balance.</li>
                </ul>
            `;
        } else if (title.includes('Stock Issue')) {
            processDescription = `
                <p class="fw-bold">Stock Issue Process (Stock issue):</p>
                <ul>
                    <li>Select destination (e.g., Production Floor, Internal Consumption).</li>
                    <li>Select stock items and quantities to be issued.</li>
                    <li>Validate available stock and commit the transaction.</li>
                    <li>Print Stock Issue Voucher/Slip.</li>
                </ul>
            `;
        } else if (title.includes('Stock Write Off')) {
            processDescription = `
                <p class="fw-bold">Stock Write Off Process (Stock Write Off):</p>
                <ul>
                    <li>Identify reason (Damage, Expiry, Obsolescence, Loss).</li>
                    <li>Select stock item, quantity, and associated value.</li>
                    <li>Approve and post the write-off transaction.</li>
                    <li>The stock is permanently removed from inventory.</li>
                </ul>
            `;
        }
        
        contentArea.innerHTML = processDescription;

        moduleDetailModal.show();
    }
    
    function executeModuleAction() {
        const action = document.getElementById('modal-title-text').textContent;
        moduleDetailModal.hide();
        Swal.fire({
            title: `${action} Initiated!`,
            text: `The system is now redirecting you to the dedicated ${action} form/page to perform the transaction. (Mock Action)`,
            icon: 'info',
            confirmButtonText: 'OK'
        });
    }

    function viewDetails(id) {
        // Function for Stock Balance Enquiry (detailed view)
        Swal.fire({
            title: 'Item Master & Balance Enquiry',
            html: `Showing full item master data, current stock balance, location details, and multi-level pricing for Item ID: <b>${id}</b>.`,
            icon: 'info',
            confirmButtonText: 'Close'
        });
    }

    function exportToCsv() {
        const visibleProducts = currentFilteredRows.map(row => {
            // Mock data extraction from rows
            return {
                Name: row.cells[1].querySelector('h6').textContent.trim(),
                SKU: row.cells[1].querySelector('p small').textContent.replace('SKU:', '').trim(),
                Category: row.cells[2].textContent.trim(),
                Avg_Cost: row.cells[3].textContent.trim().replace('$', ''),
                UOM: row.cells[4].textContent.trim(),
                // Clean the stock balance, removing commas and icon text/spaces
                Stock_Balance: row.cells[5].textContent.trim().split(/\s+/)[0].replace(/,/g, ''),
                Total_Value: row.cells[6].textContent.trim().replace('$', '').replace(/,/g, ''),
                // Clean the last activity date
                Last_Activity: row.cells[7].textContent.trim().split(/\s+/)[0].trim()
            };
        });
        
        if (visibleProducts.length === 0) {
            Swal.fire('Export Failed', 'No inventory items to export based on current filters.', 'warning');
            return;
        }
        
        // --- CSV GENERATION LOGIC ---
        const csvRows = [];
        const headers = Object.keys(visibleProducts[0]);
        csvRows.push(headers.join(','));

        for (const product of visibleProducts) {
            const values = headers.map(header => {
                const value = product[header];
                // Escape commas and wrap values in quotes
                return `\"${(value || "").toString().replace(/\"/g, '\"\"')}\"`;
            });
            csvRows.push(values.join(','));
        }

        const csvString = csvRows.join('\n');
        
        // Create a blob and trigger download
        const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement("a");
        const url = URL.createObjectURL(blob);
        
        link.setAttribute("href", url);
        link.setAttribute("download", "Inventory_Hub_Export_" + new Date().toISOString().slice(0, 10) + ".csv");
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        Swal.fire('Export Successful', `Exported ${visibleProducts.length} inventory records to CSV.`, 'success');
    }

    // Initial call to apply lucide icons and filters
    $(document).ready(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons(); // Initialize Lucide Icons for the new cards
        }
        // Initial display: Apply filters (which includes rendering the first page)
        applyFilters(1);
    });
    </script>
</body>
</html>