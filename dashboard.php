<?php
$pageTitle = "Branch A Management Dashboard";
include_once 'views/header.php';

// --- MOCK DATA AGGREGATION (Now focused on Branch A only) ---
// In a real application, this data would be fetched from a database based on the user's branch ID.

// 1. Financial & Sales Data (Branch A)
$financialData = [
    'Q4_2025_Sales' => [
        'Dec' => 2500000, // Branch A Revenue from previous mock
        'Nov' => 2350000,
        'Oct' => 2200000,
    ],
    'Q4_2025_Profit' => [
        'Dec' => 850000,
        'Nov' => 800000,
        'Oct' => 740000,
    ],
    'Inventory_Value' => 12500000, // Branch A inventory value in USD
    'Weekly_Transactions' => 15000, // Last 7 days transactions for Branch A
    'Average_AOV' => 48.50, // Average Order Value for Branch A
    'EBITDA_Margin' => 34.0, // Current Margin % for Branch A
    'Top_Categories' => [
        ['name' => 'Prescription', 'sales' => 1100000, 'growth' => 4.5],
        ['name' => 'OTC & Vitamins', 'sales' => 800000, 'growth' => 9.2],
        ['name' => 'Beauty & Skincare', 'sales' => 350000, 'growth' => -2.0],
        ['name' => 'Medical Devices', 'sales' => 250000, 'growth' => 12.0],
    ],
    // Branch Performance data is removed as requested.
];

// 2. Inventory Status Data (Branch A)
$inventoryData = [
    'hotSales' => [
        ['name' => 'Flu-Shield 500mg (Seasonal)', 'sales_qty' => 350, 'revenue' => 4200],
        ['name' => 'Essential Multivitamin Pack', 'sales_qty' => 280, 'revenue' => 3100],
        ['name' => 'Premium Skincare Serum', 'sales_qty' => 150, 'revenue' => 4500],
    ],
    'outOfStock' => [
        ['name' => 'PainAway Extra Strength', 'last_order' => '2025-11-28', 'days_oos' => 3],
        ['name' => 'Knee Brace (Large)', 'last_order' => '2025-12-01', 'days_oos' => 1],
        ['name' => 'Children\'s Cough Syrup (Berry)', 'last_order' => '2025-11-20', 'days_oos' => 12],
    ],
    'expiringSoon' => [
        ['name' => 'Diabetes Test Strips (Lot A)', 'expiry_date' => '2026-02-15', 'stock_qty' => 120],
        ['name' => 'Antibiotic Cream 10g (Batch X)', 'expiry_date' => '2026-03-01', 'stock_qty' => 300],
        ['name' => 'Contact Lens Solution (Standard)', 'expiry_date' => '2026-03-30', 'stock_qty' => 180],
    ],
];


// --- DATA PROCESSING FUNCTIONS ---

function formatCurrency($value) {
    if ($value >= 1000000) {
        return '$' . number_format($value / 1000000, 1) . 'M';
    } elseif ($value >= 1000) {
        return '$' . number_format($value / 1000, 0) . 'K';
    }
    return '$' . number_format($value, 2);
}


// Process Data
$currentMonthRevenue = $financialData['Q4_2025_Sales']['Dec'];
$currentMonthProfit = $financialData['Q4_2025_Profit']['Dec'];
$YoYGrowth = 7.8; // Mock YoY growth for presentation

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

                    <!-- FROM HERE -->
                    <style>
                        /* Define the primary theme color for consistency */
                        :root {
                            --primary-theme: #00897B; /* Teal/Teal-Green */
                            --secondary-theme: #4DB6AC; /* Lighter Teal */
                        }
                        .text-primary-theme {
                            color: var(--primary-theme) !important;
                        }
                        .bg-primary-theme {
                            background-color: var(--primary-theme) !important;
                        }
                        .border-primary-theme {
                            border-left-color: var(--primary-theme) !important;
                        }
                        
                        /* Custom rule: Use primary theme color for active navigation pills */
                        .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
                            background-color: var(--primary-theme) !important;
                            color: white !important; /* Ensure text is readable */
                        }

                        /* CSS for a professional, high-density dashboard layout */
                        .dashboard-grid {
                            display: grid;
                            grid-template-columns: repeat(12, 1fr);
                            gap: 20px;
                        }
                        .kpi-card {
                            background-color: #fff;
                            border-radius: 12px;
                            padding: 20px;
                            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                            transition: transform 0.2s;
                            border-left: 5px solid var(--primary-theme); /* Primary Brand Color */
                        }
                        .kpi-card:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
                        }
                        .kpi-value {
                            font-size: 2.25rem; /* text-4xl */
                            font-weight: 700; /* font-bold */
                            color: #1f2937; /* text-gray-800 */
                        }
                        .kpi-label {
                            font-size: 0.875rem; /* text-sm */
                            font-weight: 500;
                            color: #6b7280; /* text-gray-500 */
                            margin-bottom: 5px;
                        }
                        .kpi-change {
                            font-size: 0.75rem; /* text-xs */
                            font-weight: 600;
                            padding: 4px 8px;
                            border-radius: 9999px; /* rounded-full */
                        }
                        .chart-panel {
                            background-color: #fff;
                            border-radius: 12px;
                            padding: 20px;
                            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                        }
                        .table-panel {
                            max-height: 400px;
                            overflow-y: auto;
                        }
                        /* Grid spans for different sections */
                        .span-12 { grid-column: span 12 / span 12; }
                        .span-3 { grid-column: span 3 / span 3; }
                        .span-4 { grid-column: span 4 / span 4; }
                        .span-5 { grid-column: span 5 / span 5; }
                        .span-6 { grid-column: span 6 / span 6; }
                        .span-7 { grid-column: span 7 / span 7; }
                        
                        /* Responsive adjustments */
                        @media (max-width: 1024px) {
                            .span-lg-6 { grid-column: span 6 / span 6; }
                            .span-lg-12 { grid-column: span 12 / span 12; }
                        }
                        @media (max-width: 768px) {
                            .span-3, .span-4, .span-5, .span-6, .span-7 { grid-column: span 12 / span 12; }
                            .kpi-card { border-left: none; border-top: 5px solid var(--primary-theme); }
                        }
                    </style>
                    <div class="content">
                        <div class="container-fluid">
                            
                            <!-- Start Content -->
                            <div class="dashboard-grid mt-4">

                                <!-- Row 1: Financial KPIs (Grid of 4) -->
                                <div class="kpi-card span-3 span-lg-6">
                                    <div class="kpi-label">Current Month Revenue (Dec)</div>
                                    <div class="kpi-value text-primary-theme"><?php echo formatCurrency($currentMonthRevenue); ?></div>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="kpi-change text-white bg-primary-theme">
                                            +<?php echo number_format($YoYGrowth, 1); ?>% YoY
                                        </span>
                                        <i class="mdi mdi-arrow-up-bold text-primary-theme font-size-24"></i>
                                    </div>
                                </div>
                                
                                <div class="kpi-card span-3 span-lg-6">
                                    <div class="kpi-label">EBITDA Margin</div>
                                    <div class="kpi-value text-primary-theme"><?php echo number_format($financialData['EBITDA_Margin'], 2); ?>%</div>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="kpi-change text-white bg-primary-theme" style="background-color: var(--secondary-theme) !important;">
                                            Target: 30.0%
                                        </span>
                                        <i class="mdi mdi-trending-up text-primary-theme font-size-24"></i>
                                    </div>
                                </div>

                                <div class="kpi-card span-3 span-lg-6">
                                    <div class="kpi-label">Weekly Transactions (Last 7 Days)</div>
                                    <div class="kpi-value"><?php echo number_format($financialData['Weekly_Transactions']); ?></div>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="kpi-change text-white bg-primary-theme" style="background-color: var(--secondary-theme) !important;">
                                            Daily Avg: 2,142
                                        </span>
                                        <i class="mdi mdi-cart-outline text-primary-theme font-size-24"></i>
                                    </div>
                                </div>

                                <div class="kpi-card span-3 span-lg-6">
                                    <div class="kpi-label">Average Order Value (AOV)</div>
                                    <div class="kpi-value text-primary-theme"><?php echo formatCurrency($financialData['Average_AOV']); ?></div>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="kpi-change text-white bg-primary-theme">
                                            +$0.35 MoM
                                        </span>
                                        <i class="mdi mdi-arrow-up-bold text-primary-theme font-size-24"></i>
                                    </div>
                                </div>

                                <!-- Row 2: Charts -->
                                
                                <!-- Chart 1: Monthly Sales & Profit Trend (Financial) -->
                                <div class="chart-panel span-7 span-lg-12">
                                    <h4 class="card-title mb-4">Branch A Monthly Sales & Profit Trend (Q4 2025)</h4>
                                    <div id="sales_profit_chart" style="height: 350px;"></div>
                                </div>

                                <!-- Chart 2: Top Selling Categories (Financial) -->
                                <div class="chart-panel span-5 span-lg-12">
                                    <h4 class="card-title mb-4">Top 4 Categories Revenue Share</h4>
                                    <div id="category_pie_chart" style="height: 350px;"></div>
                                </div>
                                
                                <!-- Row 3: Critical Inventory Status (Full Width for Prominence) -->
                                <div class="chart-panel span-12">
                                    <h4 class="card-title mb-4">Critical Inventory Status</h4>
                                    <div>
                                        <!-- Nav tabs for Inventory Status -->
                                        <ul class="nav nav-pills nav-justified bg-light p-1 mb-3 rounded-lg" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#hot-sales-tab" role="tab" aria-selected="true">
                                                    <i class="mdi mdi-fire me-1"></i> Hot Sales
                                                </a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" data-bs-toggle="tab" href="#oos-tab" role="tab" aria-selected="false">
                                                    <i class="mdi mdi-alert-circle-outline me-1"></i> Out of Stock
                                                </a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" data-bs-toggle="tab" href="#expiring-tab" role="tab" aria-selected="false">
                                                    <i class="mdi mdi-clock-alert-outline me-1"></i> Expiring Soon
                                                </a>
                                            </li>
                                        </ul>
                                        
                                        <!-- Tab content -->
                                        <div class="tab-content">
                                            
                                            <!-- Hot Sales Tab -->
                                            <div class="tab-pane active" id="hot-sales-tab" role="tabpanel">
                                                <div class="table-panel" style="max-height: 300px;">
                                                    <table class="table table-sm table-striped mb-0">
                                                        <thead>
                                                            <tr class="text-secondary">
                                                                <th>Item</th>
                                                                <th class="text-center">Qty Sold (MoM)</th>
                                                                <th class="text-end">Revenue</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($inventoryData['hotSales'] as $item): ?>
                                                                <tr>
                                                                    <td><span class="text-primary-theme font-weight-bold"><?php echo htmlspecialchars($item['name']); ?></span></td>
                                                                    <td class="text-center"><?php echo number_format($item['sales_qty']); ?></td>
                                                                    <td class="text-end text-primary-theme font-weight-bold"><?php echo formatCurrency($item['revenue']); ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- Out of Stock Tab (Danger Color Kept for Alert) -->
                                            <div class="tab-pane" id="oos-tab" role="tabpanel">
                                                <div class="table-panel" style="max-height: 300px;">
                                                    <table class="table table-sm table-striped mb-0">
                                                        <thead>
                                                            <tr class="text-secondary">
                                                                <th>Item</th>
                                                                <th>Last Order</th>
                                                                <th class="text-center">Days OOS</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($inventoryData['outOfStock'] as $item): ?>
                                                                <tr>
                                                                    <td><span class="text-danger font-weight-bold"><?php echo htmlspecialchars($item['name']); ?></span></td>
                                                                    <td><?php echo date('M d, Y', strtotime($item['last_order'])); ?></td>
                                                                    <td class="text-center"><span class="badge bg-danger"><?php echo $item['days_oos']; ?></span></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- Expiring Soon Tab (Warning Color Kept for Alert) -->
                                            <div class="tab-pane" id="expiring-tab" role="tabpanel">
                                                <div class="table-panel" style="max-height: 300px;">
                                                    <table class="table table-sm table-striped mb-0">
                                                        <thead>
                                                            <tr class="text-secondary">
                                                                <th>Item</th>
                                                                <th class="text-center">Stock Qty</th>
                                                                <th class="text-center">Expiry Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($inventoryData['expiringSoon'] as $item): ?>
                                                                <tr>
                                                                    <td><span class="text-warning font-weight-bold"><?php echo htmlspecialchars($item['name']); ?></span></td>
                                                                    <td class="text-center"><?php echo number_format($item['stock_qty']); ?></td>
                                                                    <td class="text-center"><span class="badge bg-warning text-dark"><?php echo date('Y-m-d', strtotime($item['expiry_date'])); ?></span></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div> <!-- end dashboard-grid -->
                            <!-- End Content -->
                        </div>
                    </div>
                    <!-- TO HERE -->

                </div>
            </div>

            <?php include_once 'views/footer.php'; ?>
        </div>
    </div>

    <?php include_once 'views/footer_libraries.php'; ?>

    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>

    <script src="assets/js/app.js"></script>

    <script>
    // --- Data passed from PHP ---
    const financialData = <?php echo json_encode($financialData); ?>;
    
    // Define the color variables to be used in ApexCharts
    const primaryThemeColor = '#00897B';
    const secondaryThemeColor = '#4DB6AC';
    const profitColor = '#FFC107'; // Keeping profit slightly different for contrast

    // --- 1. MONTHLY SALES & PROFIT CHART (Line/Area) ---
    function initSalesProfitChart() {
        const months = Object.keys(financialData.Q4_2025_Sales);
        const revenue = Object.values(financialData.Q4_2025_Sales);
        const profit = Object.values(financialData.Q4_2025_Profit);

        const options = {
            chart: {
                type: 'area',
                height: 350,
                stacked: false,
                toolbar: { show: false }
            },
            series: [{
                name: 'Revenue',
                type: 'column',
                data: revenue
            }, {
                name: 'Profit',
                type: 'line',
                data: profit
            }],
            xaxis: {
                categories: months,
                title: { text: 'Month' }
            },
            yaxis: [{
                title: { text: 'Revenue (USD)' },
                labels: { formatter: (val) => '$' + (val / 1000000).toFixed(1) + 'M' }
            }, {
                opposite: true,
                title: { text: 'Profit (USD)' },
                labels: { formatter: (val) => '$' + (val / 1000000).toFixed(1) + 'M' }
            }],
            colors: [primaryThemeColor, profitColor], // Primary color for Sales, Contrast for Profit
            stroke: { width: [0, 3], curve: 'smooth' },
            dataLabels: { enabled: true, enabledOnSeries: [1] },
            plotOptions: { bar: { columnWidth: '60%', borderRadius: 4 } },
            tooltip: { shared: true, intersect: false }
        };

        var chart = new ApexCharts(document.querySelector("#sales_profit_chart"), options);
        chart.render();
    }
    
    // --- 2. CATEGORY REVENUE SHARE CHART (Donut) ---
    function initCategoryPieChart() {
        const series = financialData.Top_Categories.map(c => c.sales);
        const labels = financialData.Top_Categories.map(c => c.name);
        
        const options = {
            chart: {
                type: 'donut',
                height: 350,
            },
            series: series,
            labels: labels,
            // Using a theme-consistent palette for categories
            colors: [primaryThemeColor, secondaryThemeColor, '#F44336', '#FF9800'], 
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Sales',
                                formatter: (w) => '$' + (w.globals.seriesTotals.reduce((a, b) => a + b, 0) / 1000000).toFixed(1) + 'M'
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: (val) => Math.round(val) + '%'
            }
        };

        var chart = new ApexCharts(document.querySelector("#category_pie_chart"), options);
        chart.render();
    }

    
    // --- Document Ready Handler ---
    $(document).ready(function() {
        if (typeof ApexCharts !== 'undefined') {
            // Financial Charts
            initSalesProfitChart();
            initCategoryPieChart();
        }
    });
    </script>
</body>

</html>