<div class="vertical-menu">

    <div class="navbar-brand-box">
        <a href="index" class="logo logo-dark">
            <span class="logo-sm">
                <p><strong>All Day POS</strong></p>
            </span>
            <span class="logo-lg">
                <p><strong>All Day POS</strong></p>
            </span>
        </a>

        <a href="index" class="logo logo-light">
            <span class="logo-sm">
                <img src="assets/images/logo-sm.png" alt="" height="35">
            </span>
            <span class="logo-lg">
                <img src="assets/images/logo-light.png" alt="" height="45">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">

        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Main</li>

                <li>
                    <a href="dashboard.php" class="waves-effect">
                        <i class="uil-home-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="menu-title">POS & Sales</li>

                <li>
                    <a href="cashier.php" class="waves-effect" target="_blank">
                        <i class="uil-desktop"></i> <span>POS Cashier</span>
                    </a>
                </li>

                <li>
                    <a href="pos-list.php" class="waves-effect">
                        <i class="uil-desktop"></i> <span>POS Terminal List</span>
                    </a>
                </li>

                <li>
                    <a href="transaction.php" class="waves-effect">
                        <i class="uil-bill"></i> <span>Sales Transaction</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="uil-chart-bar"></i> <span>Sales Reports</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="sales-reports.php">Sales & Profit Analysis</a></li>
                        <li><a href="pos-reports.php">Cashier/POS Reports</a></li>
                        <li><a href="sales-enquiry.php">Quick Sales Enquiries</a></li>
                    </ul>
                </li>

                <li class="menu-title">Customer & Loyalty</li>

                <li>
                    <a href="member-maintenance.php" class="waves-effect">
                        <i class="uil-users-alt"></i> <span>Member Maintenance</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="uil-award"></i> <span>Loyalty & Points</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="point-redemption.php">Point Redemption</a></li>
                        <li><a href="member-reports.php">Member & Loyalty Reports</a></li>
                        <li><a href="customer-setup.php">Customer/Group Setup</a></li>
                    </ul>
                </li>

                <li class="menu-title">Inventory & Product</li>

                <li>
                    <a href="product-master.php" class="waves-effect">
                        <i class="uil-box"></i> <span>Product Master</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="uil-store"></i> <span>Inventory/Stock</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="inventory-summary.php">Stock Listing & Balance</a></li>
                        <li><a href="inventory-transactions.php">Stock Transactions</a></li>
                        <li><a href="inventory-take.php">Stock Counting & Take</a></li>
                        <li><a href="inventory-adjustments.php">Adjustments / Write-Off</a></li>
                        <li><a href="inventory-reports.php">Stock Reports & Aging</a></li>
                    </ul>
                </li>

                <li class="menu-title">Purchase & Supplier</li>

                <li>
                    <a href="purchase-order.php" class="waves-effect">
                        <i class="uil-file-contract-dollar"></i> <span>Purchase Order (PO)</span>
                    </a>
                </li>

                <li>
                    <a href="grn.php" class="waves-effect">
                        <i class="uil-file-contract-dollar"></i> <span>Goods Received Note (GRN)</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="uil-money-bill-stack"></i> <span>Payables & Reports</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="supplier-setup.php">Supplier Setup</a></li>
                        <li><a href="supplier-invoices.php">Supplier Invoices/Bills</a></li>
                        <li><a href="purchase-reports.php">Purchase Reports</a></li>
                    </ul>
                </li>

                <li class="menu-title">HR & Settings</li>

                <li>
                    <a href="staff-list.php" class="waves-effect">
                        <i class="uil-users-alt"></i>
                        <span>Staff List</span>
                    </a>
                </li>

                <li>
                    <a href="setup.php" class="waves-effect">
                        <i class="uil-setting"></i>
                        <span>System Setup</span>
                    </a>
                </li>

                <li>
                    <a href="#" class="side-nav-link-ref" onclick="signOutFunc()">
                        <i class="mdi mdi-logout"></i>
                        <span>Logout</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>