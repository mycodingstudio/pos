<?php
$pageTitle = "Transaction History";
// Assume 'views/header.php' includes necessary dependencies like Bootstrap/CSS, jQuery, and SweetAlert2 (Swal)
include_once 'views/header.php'; 

// --- MOCK DATA FOR TRANSACTIONS ---
function generateMockTransactions($count = 150) {
    $transactions = [];
    $staff = ["Ali Bin Abu", "Siti Aminah", "Ah Meng", "John Doe", "Jane Smith", "Kumar V.", "Fatin Z."];
    $pos_counters = ["A01", "B02", "C03"];
    $pos_numbers = ["POS-8001", "POS-8002", "POS-8003"];
    $payment_methods = ["Cash", "Credit Card", "QR Pay", "Voucher"];
    $products = ["Milk Tea", "Coffee Latte", "Chicken Sandwich", "Muffin", "Mineral Water", "Soup of the Day", "Fries", "Coca Cola", "Orange Juice"];

    $base_timestamp = time() - (30 * 24 * 60 * 60); // Start 30 days ago

    for ($i = 1; $i <= $count; $i++) {
        $trans_time = date('Y-m-d H:i:s', $base_timestamp + (rand(0, 30 * 24 * 60 * 60)));
        $status = (rand(1, 10) > 8) ? 'Cancelled' : 'Completed';
        $amount = rand(500, 5000) / 10.0; // Amount between 50.00 and 500.00
        $items_count = 0;
        
        // Generate mock items for item search
        $num_items = rand(1, 4);
        $item_list = [];
        $item_search_string = "";
        for ($j = 0; $j < $num_items; $j++) {
            $item_name = $products[array_rand($products)];
            $item_qty = rand(1, 3);
            $item_list[] = ["name" => $item_name, "qty" => $item_qty];
            $item_search_string .= $item_name . " ";
            $items_count += $item_qty;
        }

        $counter = $pos_counters[array_rand($pos_counters)];
        $pos_number = $pos_numbers[array_rand($pos_numbers)];
        $on_duty_person = $staff[array_rand($staff)];
        $date_only = date('Y-m-d', strtotime($trans_time));

        $transactions[] = [
            "id" => 1000 + $i,
            "trans_id" => "TRX-" . date('Ymd', strtotime($trans_time)) . "-" . str_pad($i, 4, '0', STR_PAD_LEFT),
            "date" => $date_only,
            "time" => date('H:i:s', strtotime($trans_time)),
            "counter" => $counter,
            "pos_number" => $pos_number,
            "on_duty_person" => $on_duty_person,
            "total_amount" => number_format($amount, 2),
            "items_count" => $items_count,
            "payment_method" => $payment_methods[array_rand($payment_methods)],
            "status" => $status,
            "items_list" => $item_list, // Detailed item list
            // Combined string for client-side search/filter (ID, Staff, Item, Counter, POS No.)
            "search_data" => strtolower("TRX-" . $i . " " . $counter . " " . $pos_number . " " . $on_duty_person . " " . $item_search_string)
        ];
    }
    
    // Sort transactions by date descending
    usort($transactions, function($a, $b) {
        return strtotime($b['date'] . ' ' . $b['time']) - strtotime($a['date'] . ' ' . $a['time']);
    });
    return $transactions;
}

$transactions = generateMockTransactions();

// Extract unique POS numbers for the filter dropdown
$pos_numbers = array_unique(array_column($transactions, 'pos_number'));
sort($pos_numbers);
?>

<body>
    <?php include_once 'views/loading_spinner.php'; // Assume a spinner component ?>

    <div id="layout-wrapper">
        <?php include_once 'views/top-bar.php'; // Assume top navigation ?>
        <?php include_once 'views/sidebar.php'; // Assume sidebar navigation ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <!-- Page Title and Breadcrumb -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 font-size-18">Transaction History</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Main</a></li>
                                        <li class="breadcrumb-item active">Transaction</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Page Title -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Transaction Ledger</h4>
                                    <p class="card-title-desc">View and search through all Point-of-Sale transactions.
                                    </p>

                                    <!-- Filter Controls -->
                                    <div class="row mb-4 g-3">
                                        <!-- General Search -->
                                        <div class="col-md-4">
                                            <label class="form-label text-muted">Search ID/Staff</label>
                                            <input type="text" id="transaction-search" class="form-control"
                                                placeholder="Search by ID or Staff name..." onkeyup="applyFilters()">
                                        </div>
                                        <!-- Item Search -->
                                        <div class="col-md-4">
                                            <label class="form-label text-muted">Search Item</label>
                                            <input type="text" id="filter-item-search" class="form-control"
                                                placeholder="Search by item name..." onkeyup="applyFilters()">
                                        </div>
                                        <!-- POS Number Filter -->
                                        <div class="col-md-4">
                                            <label class="form-label text-muted">Filter POS No.</label>
                                            <select id="filter-pos-no" class="form-select" onchange="applyFilters()">
                                                <option value="">All POS No.</option>
                                                <?php foreach ($pos_numbers as $pos): ?>
                                                <option value="<?= $pos ?>"><?= $pos ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <!-- Date From -->
                                        <div class="col-md-3">
                                            <label class="form-label text-muted">Date From</label>
                                            <input type="date" id="filter-date-from" class="form-control"
                                                onchange="applyFilters()">
                                        </div>
                                        <!-- Date To -->
                                        <div class="col-md-3">
                                            <label class="form-label text-muted">Date To</label>
                                            <input type="date" id="filter-date-to" class="form-control"
                                                onchange="applyFilters()">
                                        </div>
                                        <!-- Status Filter -->
                                        <div class="col-md-3">
                                            <label class="form-label text-muted">Status</label>
                                            <select id="filter-status" class="form-select" onchange="applyFilters()">
                                                <option value="">All Statuses</option>
                                                <option value="Completed">Completed</option>
                                                <option value="Cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                        <!-- Export Button -->
                                        <div class="col-md-3 d-flex align-items-end">
                                            <button class="btn btn-success w-100" onclick="exportToCSV()">
                                                <i class="uil-import me-1"></i> Export Data
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Transaction Table -->
                                    <div class="table-responsive">
                                        <table class="table table-centered table-nowrap mb-0" id="transactionTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Date/Time</th>
                                                    <th>Counter</th>
                                                    <th>POS No.</th>
                                                    <th>On Duty Person</th>
                                                    <th>Items</th>
                                                    <th>Amount (RM)</th>
                                                    <th>Status</th>
                                                    <th>Details</th>
                                                </tr>
                                            </thead>
                                            <!-- Rows will be generated by JavaScript based on filters/pagination -->
                                            <tbody id="transactionBody">
                                            </tbody>
                                        </table>
                                        <div id="no-results" class="text-center p-4 d-none">
                                            <i class="uil-exclamation-octagon font-size-24 text-warning"></i>
                                            <p class="mt-2">No transactions found matching your filters.</p>
                                        </div>
                                    </div>
                                    <!-- End Transaction Table -->

                                    <!-- Pagination Controls -->
                                    <div class="row mt-4">
                                        <div class="col-sm-12 col-md-5">
                                            <div id="pagination-info" class="dataTables_info"></div>
                                        </div>
                                        <div class="col-sm-12 col-md-7">
                                            <div class="dataTables_paginate paging_simple_numbers float-end">
                                                <ul class="pagination" id="pagination-controls">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Pagination Controls -->

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php include_once 'views/footer.php'; // Assume a footer component ?>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <?php include_once 'views/footer_libraries.php'; // Assume JS libraries and closing tags ?>
    <script src="assets/js/app.js"></script>
    <script>
    // Store all transaction data globally
    const allTransactions = <?= json_encode($transactions) ?>;

    let currentPage = 1;
    const itemsPerPage = 10;

    // --- Core Rendering Function ---

    /**
     * Renders the current page of filtered transactions to the table body.
     * @param {Array} filteredData - The array of transactions after filtering.
     * @param {number} page - The current page number to render.
     */
    function renderPage(filteredData, page) {
        const tableBody = document.getElementById('transactionBody');
        const noResultsDiv = document.getElementById('no-results');
        tableBody.innerHTML = '';

        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedData = filteredData.slice(start, end);

        if (paginatedData.length === 0) {
            noResultsDiv.classList.remove('d-none');
            document.getElementById('pagination-info').innerText = 'Showing 0 to 0 of 0 entries';
            return;
        } else {
            noResultsDiv.classList.add('d-none');
        }

        paginatedData.forEach(trans => {
            const badgeClass = (trans.status === 'Completed') ? 'bg-success' : 'bg-danger';
            const row = `
                    <tr 
                        data-date="${trans.date}"
                        data-pos="${trans.pos_number}"
                        data-status="${trans.status}"
                        data-payment="${trans.payment_method}"
                        data-search="${trans.search_data}"
                    >
                        <td><a href="#" class="text-primary fw-bold">${trans.trans_id}</a></td>
                        <td>${trans.date}<br><small class="text-muted">${trans.time}</small></td>
                        <td class="fw-medium">${trans.counter}</td>
                        <td>${trans.pos_number}</td>
                        <td>${trans.on_duty_person}</td>
                        <td>${trans.items_count}</td>
                        <td class="fw-bold text-success">RM ${trans.total_amount}</td>
                        <td>
                            <span class='badge ${badgeClass} font-size-12'>${trans.status}</span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="showDetails(${trans.id})">
                                <i class="uil-eye"></i> View
                            </button>
                        </td>
                    </tr>
                `;
            tableBody.innerHTML += row;
        });

        // Update pagination information
        const totalEntries = filteredData.length;
        const showingFrom = start + 1;
        const showingTo = Math.min(end, totalEntries);
        document.getElementById('pagination-info').innerText =
            `Showing ${showingFrom} to ${showingTo} of ${totalEntries} entries`;
    }

    // --- Pagination Controls ---

    /**
     * Initializes and updates the pagination controls.
     * @param {number} totalEntries - Total number of filtered items.
     */
    function initPagination(totalEntries) {
        const totalPages = Math.max(1, Math.ceil(totalEntries / itemsPerPage));
        const controls = document.getElementById('pagination-controls');
        controls.innerHTML = '';

        // Previous Button
        controls.innerHTML += `<li class="paginate_button page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a href="#" class="page-link" onclick="changePage(event, ${currentPage - 1})">Previous</a>
            </li>`;

        // Page Buttons (Simplified display)
        const maxButtons = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
        let endPage = Math.min(totalPages, startPage + maxButtons - 1);

        if (endPage - startPage + 1 < maxButtons) {
            startPage = Math.max(1, endPage - maxButtons + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            controls.innerHTML += `<li class="paginate_button page-item ${i === currentPage ? 'active' : ''}">
                    <a href="#" class="page-link" onclick="changePage(event, ${i})">${i}</a>
                </li>`;
        }

        // Next Button
        controls.innerHTML += `<li class="paginate_button page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a href="#" class="page-link" onclick="changePage(event, ${currentPage + 1})">Next</a>
            </li>`;
    }

    /**
     * Changes the current page and re-renders the table.
     * @param {Event} event - The click event.
     * @param {number} newPage - The page number to navigate to.
     */
    function changePage(event, newPage) {
        event.preventDefault();
        const totalEntries = applyFilters(newPage);
        const totalPages = Math.max(1, Math.ceil(totalEntries / itemsPerPage));

        if (newPage >= 1 && newPage <= totalPages) {
            currentPage = newPage;
            applyFilters(currentPage);
        }
    }

    // --- Filtering Logic ---

    /**
     * Applies all filters to the full transaction list.
     * @param {number} [page=1] - The page number to display after filtering.
     * @returns {number} The total number of filtered entries.
     */
    function applyFilters(page = 1) {
        const generalSearch = document.getElementById('transaction-search').value.toLowerCase();
        const itemSearch = document.getElementById('filter-item-search').value.toLowerCase();
        const posFilter = document.getElementById('filter-pos-no').value;
        const statusFilter = document.getElementById('filter-status').value;
        const dateFrom = document.getElementById('filter-date-from').value;
        const dateTo = document.getElementById('filter-date-to').value;

        currentPage = page;

        const filteredTransactions = allTransactions.filter(trans => {
            const searchData = trans.search_data; // Includes ID, Staff, Counter, POS No., Items

            // 1. General Search Filter (ID/Staff)
            // Filtered by trans_id and on_duty_person
            let matchesGeneralSearch = generalSearch === '' ||
                trans.trans_id.toLowerCase().includes(generalSearch) ||
                trans.on_duty_person.toLowerCase().includes(generalSearch);

            // 2. Item Search Filter
            // Checks if the item search term is anywhere in the combined search data string (which contains item names)
            let matchesItemSearch = itemSearch === '' || searchData.includes(itemSearch);

            // 3. POS Number Filter
            let matchesPos = posFilter === '' || trans.pos_number === posFilter;

            // 4. Status Filter
            let matchesStatus = statusFilter === '' || trans.status === statusFilter;

            // 5. Date Range Filter
            let matchesDate = true;
            if (dateFrom && trans.date < dateFrom) {
                matchesDate = false;
            }
            if (dateTo && trans.date > dateTo) {
                matchesDate = false;
            }

            return matchesGeneralSearch && matchesItemSearch && matchesPos && matchesStatus && matchesDate;
        });

        // Check if the current page is valid after filtering
        const totalPages = Math.max(1, Math.ceil(filteredTransactions.length / itemsPerPage));
        if (currentPage > totalPages) {
            currentPage = totalPages;
        }

        // 1. Render the page
        renderPage(filteredTransactions, currentPage);

        // 2. Update pagination controls
        initPagination(filteredTransactions.length);

        return filteredTransactions.length; // Return total filtered count
    }


    // --- Detail and Export Functions ---

    /**
     * Simulates showing transaction details in a modal/alert.
     */
    function showDetails(transId) {
        const transaction = allTransactions.find(t => t.id === transId);
        if (transaction) {
            let itemsHtml = transaction.items_list.map(item =>
                `<li>${item.name} x ${item.qty}</li>`
            ).join('');

            Swal.fire({
                title: `<span class="text-primary">${transaction.trans_id}</span>`,
                html: `
                        <p class="text-muted mb-4">Transaction Details</p>
                        <div class="row text-start ps-4 pe-4">
                            <div class="col-6 mb-2"><strong>Date/Time:</strong> ${transaction.date} ${transaction.time}</div>
                            <div class="col-6 mb-2"><strong>Amount:</strong> <span class="text-success fw-bold">RM ${transaction.total_amount}</span></div>
                            <div class="col-6 mb-2"><strong>Counter:</strong> ${transaction.counter}</div>
                            <div class="col-6 mb-2"><strong>POS No.:</strong> ${transaction.pos_number}</div>
                            <div class="col-12 mb-3"><strong>On Duty:</strong> ${transaction.on_duty_person}</div>
                            <div class="col-12"><strong>Items Sold:</strong>
                                <ul class="list-unstyled mt-1 ps-3">${itemsHtml}</ul>
                            </div>
                        </div>
                    `,
                icon: 'info',
                confirmButtonText: 'Close'
            });
        } else {
            Swal.fire('Error', 'Transaction details not found.', 'error');
        }
    }

    /**
     * Exports the currently filtered transaction data to a CSV file.
     */
    function exportToCSV() {
        // Get data based on current filters (run filtering logic without rendering)
        const generalSearch = document.getElementById('transaction-search').value.toLowerCase();
        const itemSearch = document.getElementById('filter-item-search').value.toLowerCase();
        const posFilter = document.getElementById('filter-pos-no').value;
        const statusFilter = document.getElementById('filter-status').value;
        const dateFrom = document.getElementById('filter-date-from').value;
        const dateTo = document.getElementById('filter-date-to').value;

        const exportData = allTransactions.filter(trans => {
            const searchData = trans.search_data;

            let matchesGeneralSearch = generalSearch === '' || trans.trans_id.toLowerCase().includes(
                generalSearch) || trans.on_duty_person.toLowerCase().includes(generalSearch);
            let matchesItemSearch = itemSearch === '' || searchData.includes(itemSearch);
            let matchesPos = posFilter === '' || trans.pos_number === posFilter;
            let matchesStatus = statusFilter === '' || trans.status === statusFilter;

            let matchesDate = true;
            if (dateFrom && trans.date < dateFrom) {
                matchesDate = false;
            }
            if (dateTo && trans.date > dateTo) {
                matchesDate = false;
            }

            return matchesGeneralSearch && matchesItemSearch && matchesPos && matchesStatus && matchesDate;
        });

        if (exportData.length === 0) {
            Swal.fire('No Data', 'There are no transactions visible to export.', 'warning');
            return;
        }

        // Define CSV structure
        const headers = ["ID", "Date", "Time", "Counter", "POS No.", "On Duty Person", "Items Count",
            "Total Amount (RM)", "Payment Method", "Status", "Items Sold"
        ];
        let csvRows = [];
        csvRows.push(headers.join(','));

        exportData.forEach(trans => {
            const itemsSold = trans.items_list.map(i => `${i.name} x ${i.qty}`).join('; ');

            const rowData = [
                trans.trans_id,
                trans.date,
                trans.time,
                trans.counter,
                trans.pos_number,
                trans.on_duty_person,
                trans.items_count,
                trans.total_amount,
                trans.payment_method,
                trans.status,
                itemsSold
            ];
            // Quote all values and escape quotes within values
            csvRows.push(rowData.map(value => `"${value.toString().replace(/"/g, '""')}"`).join(','));
        });

        const csvString = csvRows.join('\n');

        // Create a blob and trigger download
        const blob = new Blob([csvString], {
            type: 'text/csv;charset=utf-8;'
        });
        const link = document.createElement("a");
        const url = URL.createObjectURL(blob);

        link.setAttribute("href", url);
        link.setAttribute("download", "Transaction_Export_" + new Date().toISOString().slice(0, 10) + ".csv");
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        Swal.fire('Export Successful', `Exported ${exportData.length} transaction records to CSV.`, 'success');
    }

    // Initialize on page load: apply filters with default settings (page 1)
    $(document).ready(function() {
        applyFilters(1);
    });
    </script>
</body>

</html>