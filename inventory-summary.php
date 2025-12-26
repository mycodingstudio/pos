<?php
$pageTitle = "Inventory Stock Summary";
include_once 'views/header.php';

// --- START MOCK DATA GENERATION (150 Items) ---

$locations = ['Main Store A', 'Main Store B', 'Warehouse 1', 'Retail Floor', 'Counter 1', 'Counter 2'];
$categories = ['Pharmaceuticals', 'OTC Medicine', 'Health Supplements', 'Personal Care', 'Beverages', 'Medical Devices'];
$itemNames = [
    'Paracetamol 500mg', 'Vitamin C 1000mg', 'Hand Sanitizer Gel', 'N95 Face Mask', 'Digital Thermometer',
    'Cold & Flu Syrup', 'Antacid Tablets', 'Pain Relief Balm', 'Electrolyte Drink', 'Protein Powder Vanilla',
    'Blood Pressure Monitor', 'First Aid Kit Small', 'Cough Suppressant', 'Aspirin Low Dose', 'Gauze Pads Sterile',
    'Baby Diapers Size 3', 'Infant Formula Powder', 'Shampoo Anti-Dandruff', 'Body Wash Sensitive', 'Facial Moisturizer'
];

$inventoryData = [];
$totalCostValue = 0;
$totalSalesValue = 0;
$lowStockCount = 0;
$expiredCount = 0;

for ($i = 1; $i <= 150; $i++) {
    $sku = 'SKU-' . str_pad($i, 4, '0', STR_PAD_LEFT);
    $itemName = $itemNames[array_rand($itemNames)] . ' (' . ($i % 5 === 0 ? 'Large' : 'Small') . ')';
    $location = $locations[array_rand($locations)];
    $category = $categories[array_rand($categories)];
    
    // Generate realistic stock levels and prices
    $cost = rand(500, 5000) / 100; // RM 5.00 to RM 50.00
    $margin = rand(20, 50) / 100; // 20% to 50% margin
    $sellingPrice = round($cost * (1 + $margin), 2);
    
    $qty = rand(0, 300);
    $minQty = rand(10, 50);
    
    // Stock Status Logic
    $isLowStock = $qty <= $minQty;
    if ($isLowStock) {
        $lowStockCount++;
    }
    
    // Expiry Date Logic
    $expiryDays = rand(-30, 730); // 30 days ago to 2 years from now
    $expiryDate = date('Y-m-d', strtotime("+$expiryDays days"));
    $isExpired = $expiryDays <= 0;
    
    if ($isExpired) {
        $expiredCount++;
    }
    
    // NEW: Generate Mock Batch Number
    $batchNo = 'BN' . date('ym', strtotime($expiryDate)) . str_pad(rand(10, 999), 3, '0', STR_PAD_LEFT);
    
    $totalCostValue += $qty * $cost;
    $totalSalesValue += $qty * $sellingPrice;

    $inventoryData[] = [
        "sku" => $sku,
        "item_name" => $itemName,
        "location" => $location,
        "category" => $category,
        "qty" => $qty,
        "min_qty" => $minQty,
        "cost" => $cost,
        "selling_price" => $sellingPrice,
        "expiry_date" => $expiryDate,
        "batch_no" => $batchNo, // ADDED BATCH NO
        "is_low_stock" => $isLowStock,
        "is_expired" => $isExpired,
    ];
}

// Helper to format currency
function formatCurrency($amount) {
    return 'RM ' . number_format($amount, 2);
}

// Helper to determine status badge color
function getStockStatusBadge($qty, $minQty, $isExpired) {
    if ($isExpired && $qty > 0) {
        return '<span class="badge bg-danger">EXPIRED</span>';
    } elseif ($qty === 0) {
        return '<span class="badge bg-dark">OUT OF STOCK</span>';
    } elseif ($qty <= $minQty) {
        return '<span class="badge bg-warning text-dark">LOW STOCK</span>';
    } else {
        return '<span class="badge bg-success">HEALTHY</span>';
    }
}

// --- MOCK SUMMARY METRICS ---
$summaryMetrics = [
    [
        "title" => "Total Active Items", 
        "value" => number_format(count($inventoryData)), 
        "icon" => "uil-box", 
        "color" => "primary",
        "description" => "Total number of stock keeping units."
    ],
    [
        "title" => "Total Stock Value (Cost)", 
        "value" => formatCurrency($totalCostValue), 
        "icon" => "uil-usd-square", 
        "color" => "info",
        "description" => "Total cost of current stock holding."
    ],
    [
        "title" => "Low Stock Alerts", 
        "value" => number_format($lowStockCount), 
        "icon" => "uil-exclamation-octagon", 
        "color" => "warning",
        "description" => "Items requiring re-order." 
    ],
    [
        "title" => "Expired Stock", 
        "value" => number_format($expiredCount), 
        "icon" => "uil-history", 
        "color" => "danger",
        "description" => "Items that have passed their expiry date."
    ],
];
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
                        <?php foreach ($summaryMetrics as $metric): ?>
                        <div class="col-xl-3 col-sm-6 col-6"> 
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-muted fw-medium mb-2" style="font-size: 13px;"><?php echo $metric['title']; ?></p>
                                            <h4 class="mb-0 text-<?php echo $metric['color']; ?>"><?php echo $metric['value']; ?></h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle bg-light text-<?php echo $metric['color']; ?> me-0">
                                                <span class="avatar-title rounded-circle bg-transparent">
                                                    <i class="uil <?php echo $metric['icon']; ?> font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <p class="text-muted mb-0" style="font-size: 11px;"><?php echo $metric['description']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="card-title">Full Inventory Status (<?php echo number_format(count($inventoryData)); ?> Items)</h4>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="uil uil-export me-1"></i> Export to CSV
                                        </button>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-3 mb-2">
                                            <label for="filter-location" class="form-label visually-hidden">Location</label>
                                            <select class="form-select" id="filter-location">
                                                <option value="">All Locations</option>
                                                <?php foreach ($locations as $loc): ?>
                                                    <option value="<?php echo $loc; ?>"><?php echo $loc; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label for="filter-category" class="form-label visually-hidden">Category</label>
                                            <select class="form-select" id="filter-category">
                                                <option value="">All Categories</option>
                                                <?php foreach ($categories as $cat): ?>
                                                    <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label for="filter-status" class="form-label visually-hidden">Stock Status</label>
                                            <select class="form-select" id="filter-status">
                                                <option value="">All Stock Status</option>
                                                <option value="LOW">Low Stock</option>
                                                <option value="EXPIRED">Expired Stock</option>
                                                <option value="OOS">Out of Stock</option>
                                                <option value="HEALTHY">Healthy Stock</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-2 d-flex">
                                            <input type="text" class="form-control" placeholder="Search SKU or Item Name..." id="search-input">
                                            <button class="btn btn-secondary ms-2" onclick="applyFilters()">
                                                <i class="uil uil-filter"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover align-middle table-nowrap mb-0" id="fullInventoryTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" style="width: 50px;">No</th> 
                                                    <th scope="col">SKU</th>
                                                    <th scope="col">Item Name</th>
                                                    <th scope="col">Category</th>
                                                    <th scope="col">Location</th>
                                                    <th scope="col" class="text-center">Current Qty</th>
                                                    <th scope="col" class="text-center">Min Qty</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col" class="text-end">Cost Value</th>
                                                    <th scope="col">Batch No</th> <th scope="col">Expiry Date</th>
                                                    <th scope="col" style="width: 80px;">Actions</th> </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $no = 1; // Initialize counter
                                                // Loop through the 150 mock items
                                                foreach ($inventoryData as $item): 
                                                    $itemValue = $item['qty'] * $item['cost'];
                                                    $qtyClass = ($item['qty'] <= $item['min_qty']) ? 'text-danger fw-bold' : '';
                                                    $expiryClass = ($item['expiry_date'] <= date('Y-m-d', strtotime('+30 days'))) ? 'text-danger fw-bold' : '';
                                                    $expiryDateDisplay = ($item['is_expired']) ? '<span class="text-danger">EXPIRED</span>' : date('d M Y', strtotime($item['expiry_date']));
                                                ?>
                                                <tr>
                                                    <td><?php echo $no++; ?></td> 
                                                    <td><?php echo $item['sku']; ?></td>
                                                    <td><?php echo $item['item_name']; ?></td>
                                                    <td><?php echo $item['category']; ?></td>
                                                    <td><?php echo $item['location']; ?></td>
                                                    <td class="text-center <?php echo $qtyClass; ?>"><?php echo number_format($item['qty']); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['min_qty']); ?></td>
                                                    <td><?php echo getStockStatusBadge($item['qty'], $item['min_qty'], $item['is_expired']); ?></td>
                                                    <td class="text-end"><?php echo formatCurrency($itemValue); ?></td>
                                                    <td><?php echo $item['batch_no']; ?></td> <td class="<?php echo $expiryClass; ?>"><?php echo $expiryDateDisplay; ?></td>
                                                    <td>
                                                        <a href="inventory-transactions.php?action=adjust&sku=<?php echo $item['sku']; ?>" class="btn btn-sm btn-outline-info" title="Adjust Stock">
                                                            <i class="uil uil-edit-alt"></i>
                                                        </a>
                                                        <a href="inventory-transactions.php?action=transfer&sku=<?php echo $item['sku']; ?>" class="btn btn-sm btn-outline-secondary ms-1" title="Transfer Stock">
                                                            <i class="uil uil-exchange-alt"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3 d-flex justify-content-between align-items-center">
                                        <p class="text-muted mb-0">Showing 1 to 100 of <?php echo number_format(count($inventoryData)); ?> entries (100 per page)</p>
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination pagination-sm justify-content-end mb-0">
                                                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                            </ul>
                                        </nav>
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
            console.log('Inventory Summary Page Loaded with 150 mock items, filter controls, and Batch No.');
        });
        
        function applyFilters() {
            const location = document.getElementById('filter-location').value;
            const category = document.getElementById('filter-category').value;
            const status = document.getElementById('filter-status').value;
            const search = document.getElementById('search-input').value;

            // In a real application, this function would send an AJAX request 
            // to the server with these parameters to fetch filtered data.
            alert(`Filtering with:\nLocation: ${location}\nCategory: ${category}\nStatus: ${status}\nSearch: ${search}\n\n(Simulated filter application)`);
        }
    </script>
</body>

</html>