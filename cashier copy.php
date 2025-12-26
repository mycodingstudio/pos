<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy POS Cashier Interface - Comprehensive Mockup</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Lucide is used for icons, providing a clean, modern look -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* =====================================================================
           1. CORE LAYOUT STYLES: Container, Row, Column Structure (Flexbox)
        ===================================================================== */
        body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f4f6f9; color: #333; }
        .pos-app { display: flex; height: 100vh; overflow: hidden; }

        /* Columns (Main Structure) */
        .col-tabs { width: 80px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 1rem 0; box-shadow: 2px 0 5px rgba(0,0,0,0.05); z-index: 10; }
        .col-main-wrapper { flex-grow: 1; display: flex; flex-direction: row; } /* Removed transition/transform */
        .col-main { flex-shrink: 0; width: 100%; display: flex; flex-direction: column; background-color: #f8f8f8; }
        .col-tools { flex-shrink: 0; width: 100%; padding: 2rem; background-color: #fff; overflow-y: auto; }
        .col-cart { width: 380px; background-color: #fff; border-left: 1px solid #e0e0e0; display: flex; flex-direction: column; box-shadow: -2px 0 5px rgba(0,0,0,0.1); z-index: 20; }

        /* Item Grid Container (Row/Col Structure for Products) */
        .product-grid-container { flex-grow: 1; padding: 1rem; overflow-y: auto; }
        .product-grid-row { display: flex; flex-wrap: wrap; margin-left: -0.5rem; margin-right: -0.5rem; }
        /* 6 columns for product grid */
        .product-grid-col { flex-basis: 16.666%; padding-left: 0.5rem; padding-right: 0.5rem; margin-bottom: 1rem; }
        
        /* Popular Items Row */
        .popular-grid-row { display: flex; flex-wrap: wrap; padding: 0 1rem 1rem; }
        .popular-btn { background-color: #00897B; color: white; border: none; padding: 8px 5px; border-radius: 4px; font-size: 10px; font-weight: bold; line-height: 1.2; cursor: pointer; margin: 0.25rem; transition: background-color 0.1s; }
        .popular-btn:hover { background-color: #00695C; }


        /* =====================================================================
           2. COMPONENT STYLES
        ===================================================================== */
        /* Header Bar */
        .system-header {
            padding: 10px 1rem;
            background-color: #00897B; /* Primary color: Teal/Green */
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            flex-shrink: 0;
        }
        .system-header .info-group { display: flex; align-items: center; margin-right: 20px; }
        .system-header .info-group i { margin-right: 5px; width: 16px; height: 16px; }
        .system-header .company-name { font-size: 1.2rem; font-weight: bold; }


        /* Tabs */
        .tab-btn { width: 60px; height: 60px; margin: 0.5rem auto; border: 2px solid transparent; border-radius: 12px; background-color: #f0f0f0; display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; cursor: pointer; transition: all 0.2s; position: relative; }
        .tab-btn.active { background-color: #e6f7ff; border-color: #00897B; color: #00897B; }
        .tab-add-btn { background-color: #00897B !important; color: white !important; margin-bottom: 1rem; }
        .tab-badge { position: absolute; top: 0; right: 0; width: 8px; height: 8px; background-color: #e53e3e; border-radius: 50%; border: 2px solid white; }

        /* Products, Cart */
        .search-header { padding: 1rem; border-bottom: 1px solid #eee; background-color: #fff; display: flex; }
        .filter-bar { 
            padding: 0.5rem 1rem; /* Adjusted vertical padding */
            border-bottom: 1px solid #eee; 
            background-color: #fff; 
            display: flex; 
            gap: 8px; 
            overflow-x: auto; /* Ensures horizontal scrolling */
            flex-wrap: nowrap; 
            flex-shrink: 0;
            max-height: 55px; /* Fixed height to prevent cutting */
        }
        .category-btn { padding: 6px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 0.9rem; cursor: pointer; transition: all 0.2s; background-color: #f0f0f0; white-space: nowrap; }
        .category-btn.active { background-color: #00897B; color: white; border-color: #00897B; }
        
        .product-card { 
            background-color: white; border: 1px solid #eee; border-radius: 6px; padding: 0.75rem; 
            cursor: pointer; transition: all 0.2s; position: relative; 
            height: 160px; /* FORCED UNIFORM HEIGHT */
            display: flex; flex-direction: column; justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            max-width: 250px;
        }
        .product-card:hover { transform: translateY(-2px); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .product-card.clicked-success { border: 2px solid #48BB78; background-color: #F0FFF4; } 
        .price-text { font-size: 1.1rem; font-weight: bold; color: #00897B; }
        .low-stock { background-color: #ffcccc; color: #c53030; } /* Highlight for low stock */
        
        .cart-header { padding: 1rem; background-color: #e6f7ff; border-bottom: 1px solid #b3e0ff; flex-shrink: 0; }
        .cart-item-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; border-bottom: 1px dotted #ccc; font-size: 0.9rem; }
        .item-name { flex-basis: 45%; }
        .item-qty-controls { flex-basis: 30%; display: flex; align-items: center; justify-content: flex-end; }
        .item-price { flex-basis: 25%; text-align: right; font-weight: bold; }
        .cart-footer { padding: 1rem; border-top: 1px solid #eee; background-color: #fff; flex-shrink: 0; }
        .total-row { display: flex; justify-content: space-between; font-size: 1rem; margin-top: 0.5rem; }
        .total-grand { font-size: 1.5rem; font-weight: bold; color: #00897B; }
        .action-btns { display: flex; gap: 10px; margin-top: 1rem; }
        .pay-btn, .cancel-btn, .suspend-btn { padding: 1rem; font-size: 1.2rem; font-weight: bold; border-radius: 8px; border: none; cursor: pointer; flex: 1; transition: background-color 0.1s; }
        .pay-btn { background-color: #00897B; color: white; flex: 2; }
        .pay-btn:hover { background-color: #00695C; }
        .cancel-btn { background-color: #e53e3e; color: white; }
        .cancel-btn:hover { background-color: #c53030; }
        .suspend-btn { background-color: #ffc107; color: #333; }
        .suspend-btn:hover { background-color: #e0a800; }
        .no-sale-btn { background-color: #f0f0f0; color: #333; padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 4px; margin-top: 0.5rem; width: 100%;}
        .qty-btn { background-color: #e6f7ff; border: 1px solid #b3e0ff; border-radius: 4px; color: #00897B; font-weight: bold; cursor: pointer; }
        
        /* Modals - Updated to display: flex when shown */
        .modal-overlay { 
            position: fixed; top: 0; left: 0; right: 0; bottom: 0; 
            background: rgba(0,0,0,0.7); 
            display: none; /* Default hidden state */
            justify-content: center; 
            align-items: center; 
            z-index: 1000; 
        }
        .payment-modal, .tools-modal { 
            background: white; width: 90%; 
            max-width: 900px; 
            height: 80vh; 
            border-radius: 12px; 
            display: flex; 
            overflow: hidden; 
        }
        .tools-modal {
            max-width: 700px;
            display: block; /* Tools modal content flows vertically */
            height: auto;
            max-height: 90vh;
        }
        .modal-left { flex: 2; padding: 2rem; border-right: 1px solid #eee; overflow-y: auto; }
        .modal-right { flex: 1; padding: 2rem; background-color: #f8f8f8; display: flex; flex-direction: column; justify-content: space-between; }
        .payment-option { background: #f0f0f0; border: 1px solid #ddd; padding: 1.5rem; border-radius: 8px; text-align: center; font-weight: bold; cursor: pointer; transition: all 0.2s; }
        .payment-option:hover, .payment-option.selected { background: #00897B; color: white; border-color: #00897B; }
        #payment-breakdown { border: 1px solid #ddd; padding: 1rem; border-radius: 6px; min-height: 100px; background-color: #fff; }
        .payment-input-group { margin-top: 1rem; flex-grow: 1; }
        #payment-amount { font-size: 1.5rem !important; }

        /* Tool-Specific Styles */
        .admin-tool-section { margin-bottom: 2rem; padding: 1.5rem; border: 1px solid #eee; border-radius: 8px; background-color: #fcfcfc; }
        #order-search-results { max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; margin-top: 1rem; background-color: #fff;}
        .found-order-item { padding: 10px; border-bottom: 1px dotted #ccc; display: flex; justify-content: space-between; align-items: center; }
        .found-order-item:last-child { border-bottom: none; }
        
        /* Form Inputs */
        input[type="text"], input[type="number"], textarea {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>

    <div id="pos-app" class="pos-app">
        <div class="col-tabs">
            <!-- Button to create a new order/tab -->
            <button id="new-order-btn" class="tab-btn tab-add-btn">
                <i data-lucide="plus" style="width: 20px; height: 20px;"></i>
                New
            </button>
            <!-- Container for dynamic order tabs -->
            <div id="order-tabs-container" style="flex-grow: 1; overflow-y: auto;"></div>
            
            <!-- Button to view suspended orders -->
            <button id="show-suspended-btn" class="tab-btn" style="margin-top: 0.5rem; background-color: #f0f0f0;">
                <i data-lucide="clock" style="width: 18px; height: 18px; color: #ffc107;"></i>
                <span style="color: #333;">Hold (<span id="suspended-count">0</span>)</span>
            </button>

            <!-- Button to open Admin Tools modal -->
            <button id="tools-btn" class="tab-btn" style="margin-top: 0.5rem; background-color: #f0f0f0;">
                <i data-lucide="settings" style="width: 18px; height: 18px; color: #666;"></i>
                <span style="color: #333;">Tools</span>
            </button>
        </div>

        <div class="col-main-wrapper">
            <!-- Main POS Screen (Product Selection) -->
            <div id="col-main-panel" class="col-main">
                
                <div class="system-header">
                    <div class="info-group">
                        <i data-lucide="building" style="width: 20px; height: 20px;"></i>
                        <span class="company-name">HealthFirst Pharmacy Inc.</span>
                    </div>
                    <div class="info-group" style="font-size: 0.8rem;">
                        <i data-lucide="map-pin"></i>
                        <span>123 Main St, Central City (Branch ID: **BCH-007**)</span>
                    </div>
                    <div class="info-group">
                        <i data-lucide="calendar"></i>
                        <span id="current-datetime">Loading Date & Time...</span>
                    </div>
                </div>

                <div class="search-header">
                    <div style="position: relative; flex: 1; max-width: 600px;">
                        <i data-lucide="search" style="position: absolute; left: 10px; top: 12px; width: 20px; color: #999;"></i>
                        <input type="text" id="search-input" placeholder="Scan Barcode or Search Item Name/Code..." style="width: 100%; padding: 10px 10px 10px 40px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                </div>
                
                <!-- Category Filter Bar (Fixed UI Issue) -->
                <div id="filter-bar" class="filter-bar"></div>

                <!-- Popular Items Quick Pick Panel -->
                <div class="popular-panel">
                    <div style="font-weight: bold; font-size: 0.9rem; color: #00897B; padding: 0 1rem 0.5rem;">🔥 Quick Picks (Popular Items)</div>
                    <div id="popular-items-grid" class="popular-grid-row"></div>
                </div>

                <!-- Product Grid -->
                <div id="product-grid-container" class="product-grid-container">
                    <div id="product-grid-row" class="product-grid-row"></div>
                </div>
            </div>
            
            <!-- NOTE: col-tools-panel removed from here and moved to a modal below -->
        </div>

        <!-- Cart Panel -->
        <div class="col-cart">
            <div id="cart-header" class="cart-header"></div>
            <div id="cart-items" class="cart-items" style="flex-grow: 1; overflow-y: auto;">
                <div style="padding: 10px; display: flex; justify-content: space-between; font-weight: bold; border-bottom: 2px solid #ddd;">
                    <span style="flex-basis: 45%;">Item (Rx Flag)</span>
                    <span style="flex-basis: 30%; text-align: right;">Qty</span>
                    <span style="flex-basis: 25%; text-align: right;">Price</span>
                </div>
            </div>
            <div id="cart-footer" class="cart-footer"></div>
        </div>
    </div>
    
    <!-- STAFF ADMIN TOOLS MODAL (New Modal Structure) -->
    <div id="tools-modal-overlay" class="modal-overlay">
        <div id="col-tools-panel" class="tools-modal">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 2px solid #00897B; padding-bottom: 10px;">
                <h1 style="color: #00897B;"><i data-lucide="settings" style="width: 24px; vertical-align: middle;"></i> Staff Admin Tools</h1>
                <button id="close-tools-btn" style="background-color: #e53e3e; color: white; padding: 10px 15px; border: none; border-radius: 6px; cursor: pointer;">
                    <i data-lucide="x" style="width: 16px; vertical-align: middle;"></i> Close Tools
                </button>
            </div>

            <div class="admin-tool-section">
                <h3><i data-lucide="receipt"></i> Search Order History (Completed)</h3>
                <p style="font-size: 0.9rem; color: #666;">Search by Order ID (e.g., 9900), Customer Name (e.g., Jane), or Date (e.g., 2025-11-30).</p>
                <input type="text" id="order-search-input" placeholder="Enter ID, Customer, or Date..." style="width: 100%; margin-bottom: 10px;">
                <button id="execute-order-search" style="background-color: #4a5568; color: white; padding: 10px; border: none; border-radius: 4px; width: 100%;">Search Orders</button>
                
                <div id="order-search-results">
                    <p style="text-align: center; color: #999;">Results will appear here.</p>
                </div>
            </div>
            
            <div class="admin-tool-section">
                <h3><i data-lucide="notebook"></i> Write Internal Staff Note</h3>
                <textarea id="staff-note-area" placeholder="Write down important information, low stock alerts, or handover notes for the next shift." rows="6" style="width: 100%; resize: vertical;"></textarea>
                <button id="save-staff-note" style="margin-top: 10px; background-color: #00897B; color: white; padding: 10px; border: none; border-radius: 4px; width: 100%;">Save Note Locally</button>
                <p id="note-status" style="margin-top: 10px; font-size: 0.9rem; color: #48BB78;"></p>
                <div style="margin-top: 15px; font-size: 0.8rem; color: #666;">
                    Last Saved Note: <span id="last-note-display">N/A</span>
                </div>
            </div>
        </div>
    </div>


    <!-- PAYMENT MODAL -->
    <div id="payment-modal-overlay" class="modal-overlay">
        <div class="payment-modal">
            <div class="modal-left">
                <h2>Payment Methods</h2>
                <div class="grid grid-cols-3 gap-4" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                    <!-- Payment Options -->
                    <div class="payment-option" data-method="cash"><i data-lucide="wallet"></i><p>Cash</p></div>
                    <div class="payment-option" data-method="card"><i data-lucide="credit-card"></i><p>Credit Card</p></div>
                    <div class="payment-option" data-method="ewallet"><i data-lucide="smartphone"></i><p>E-Wallet (QR)</p></div>
                    <div class="payment-option" data-method="voucher"><i data-lucide="ticket"></i><p>Voucher/Gift</p></div>
                    <div class="payment-option" data-method="points"><i data-lucide="star"></i><p>Points Redemption</p></div>
                    <div class="payment-option" data-method="po"><i data-lucide="file-text"></i><p>PO/Account</p></div>
                </div>

                <div id="po-lookup-area" style="display: none; margin-top: 1rem;"></div>
                <div id="loyalty-tools-area" style="margin-top: 1rem;"></div>

                <h3 style="margin-top: 2rem;">Payment Breakdown</h3>
                <div id="payment-breakdown"></div>
            </div>

            <div class="modal-right">
                <div>
                    <h3 style="text-align: right;">Grand Total</h3>
                    <div id="modal-total-display" style="font-size: 2.5rem; font-weight: bold; text-align: right; color: #00897B; margin-bottom: 1rem;">$0.00</div>
                    
                    <h3 style="text-align: right; color: #e53e3e;">Remaining Balance</h3>
                    <div id="modal-remaining-display" style="font-size: 2rem; font-weight: bold; text-align: right; color: #e53e3e; margin-bottom: 2rem;">$0.00</div>
                </div>

                <div id="payment-input-area" class="payment-input-group">
                    <label for="payment-amount" style="font-weight: bold; display: block; margin-bottom: 0.5rem;">Amount Tendered</label>
                    <input type="number" id="payment-amount" placeholder="Enter amount..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 1.2rem; margin-bottom: 1rem;">
                    
                    <button id="add-payment-btn" style="background-color: #00897B; color: white; padding: 10px; border: none; border-radius: 6px; width: 100%; font-weight: bold; margin-bottom: 1rem;">TENDER PAYMENT</button>
                    
                    <div id="change-display" style="font-size: 1.2rem; font-weight: bold; text-align: center; color: #48BB78;"></div>
                </div>
                
                <div>
                    <button id="close-modal-btn" style="background-color: #666; color: white; padding: 10px; border: none; border-radius: 6px; width: 100%; font-weight: bold; margin-top: 1rem;">Cancel Transaction</button>
                </div>
            </div>
        </div>
    </div>


    <!-- SUSPENDED ORDERS MODAL -->
    <div id="suspended-modal-overlay" class="modal-overlay">
        <div style="background: white; padding: 2rem; border-radius: 8px; width: 500px;">
            <h2>Suspended Transactions</h2>
            <div id="suspended-list" style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                </div>
            <button id="close-suspended-modal" style="margin-top: 1rem; padding: 10px; width: 100%; background: #666; color: white;">Close</button>
        </div>
    </div>

    <script>
        // =====================================================================
        // 4. GLOBAL STATE & DATA SIMULATION
        // =====================================================================
        
        const POS_STATE = {
            products: [
                { id: 101, name: "Amoxicillin 500mg (10s)", brand: "Amoxil", price: 15.00, stock: 45, requiresRx: true, isPopular: true, category: "Antibiotics", batches: ["B-2023-01"], memberPrice: 14.00, img: 'pill-bottle' },
                { id: 102, name: "Paracetamol 500mg (20s)", brand: "Panadol", price: 5.50, stock: 120, requiresRx: false, isPopular: true, category: "Pain Relief", batches: ["B-GEN-01"], img: 'capsules' },
                { id: 104, name: "Multivitamin (30s)", brand: "Centrum", price: 32.00, stock: 5, requiresRx: false, isPopular: true, category: "Supplements", batches: ["B-SUP-12"], memberPrice: 28.80, img: 'bottle' },
                { id: 107, name: "Antihistamine (10s)", brand: "Zyrtec", price: 12.00, stock: 30, requiresRx: false, isPopular: true, category: "Allergy", batches: ["B-ANT-2"], img: 'pills' },
                { id: 108, name: "Cough Syrup (200ml)", brand: "Delsym", price: 18.00, stock: 50, requiresRx: false, category: "Cough/Cold", batches: ["B-CUG-3"], img: 'syrup-bottle' },
                { id: 109, name: "First Aid Kit", brand: "MediPack", price: 45.00, stock: 15, requiresRx: false, category: "First Aid", img: 'bandage' },
                { id: 110, name: "Sunscreen SPF50", brand: "La Roche", price: 65.00, stock: 70, requiresRx: false, category: "Skin Care", img: 'sun' },
                { id: 111, name: "Band-Aids (50pk)", brand: "Johnson", price: 8.50, stock: 10, requiresRx: false, category: "First Aid", img: 'bandage' },
                { id: 112, name: "Loratadine 10mg", brand: "Generic", price: 9.90, stock: 2, requiresRx: false, category: "Allergy", img: 'pills' },
                ...Array.from({ length: 90 }, (_, i) => ({ 
                    id: 200 + i, name: `Generic Item ${i + 1}`, brand: `Brand-${i + 1}`, price: (Math.random() * 50).toFixed(2), stock: Math.floor(Math.random() * 200),
                    category: ["Supplements", "First Aid", "Skin Care", "OTC", "Home Care"][Math.floor(Math.random() * 5)],
                    isPopular: Math.random() > 0.95, batches: [`B-X${i + 1}`], img: 'drug'
                }))
            ],
            customers: [
                { id: 1, name: "Walk-in Customer", points: 0, phone: "" },
                { id: 2, name: "Jane Doe (Gold)", points: 2450, phone: "0123456789", tier: "Gold", insurance: "Allianz" },
                { id: 3, name: "Mr. Smith", points: 550, phone: "0198765432", tier: "Silver" },
            ],
            orders: [
                { id: 1, customer: { id: 1, name: "Walk-in Customer", points: 0, phone: "" }, cart: [], status: 'active', payments: [], cartDiscount: 0.00 }
            ],
            completedOrders: [
                { id: 9900, customerName: "Jane Doe (Gold)", total: 55.45, date: "2025-11-28 14:30:00", items: 2 },
                { id: 9901, customerName: "Corporate Buyer", total: 150.00, date: "2025-11-29 09:15:00", items: 5 },
                { id: 9902, customerName: "Walk-in Customer", total: 12.99, date: "2025-11-30 10:05:00", items: 1 }
            ],
            suspendedOrders: [],
            promotions: [
                { id: 'BOGO-VITC', type: 'BOGO', requiredProductId: 104, requiredQty: 1, discountProductId: 107, discountPercent: 100, isActive: true},
                { id: 'DISCOUNT-VIT', type: 'CATEGORY_PERCENT', category: 'Supplements', percent: 20, isActive: true}
            ],
            activeOrderId: 1,
            selectedCategory: "All",
            pointsRedeemed: 0, // In currency value
            staffNote: localStorage.getItem('staffNote') || "No current staff notes.",
        };

        let paymentBreakdown = [];
        const LOW_STOCK_THRESHOLD = 10;


        // =====================================================================
        // 5. CORE STATE MANAGEMENT FUNCTIONS
        // =====================================================================

        function getActiveOrder() {
            return POS_STATE.orders.find(o => o.id.toString() === POS_STATE.activeOrderId.toString());
        }

        function createNewOrder() {
            // Generate a unique, short ID
            const newId = (Math.random() * 100000).toFixed(0).padStart(4, '0');
            const newOrder = { 
                id: newId, customer: POS_STATE.customers[0], cart: [], status: 'active', payments: [], cartDiscount: 0.00
            };
            POS_STATE.orders.push(newOrder);
            POS_STATE.activeOrderId = newId;
            updateStateAndRender();
        }

        function updateStateAndRender() {
            renderTabs();
            renderCategoryFilters();
            renderCustomerHeader();
            renderCart();
            renderProducts();
            renderStaffNoteDisplay();
            $('#suspended-count').text(POS_STATE.suspendedOrders.length); // Update suspended count
            
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        // =====================================================================
        // 6. RENDER FUNCTIONS (LAYOUT AND DATA VISUALIZATION)
        // =====================================================================
        
        function renderTabs() { 
            const $container = $('#order-tabs-container'); $container.empty();
            POS_STATE.orders.forEach(order => {
                const isActive = order.id.toString() === POS_STATE.activeOrderId.toString();
                const cartCount = order.cart.length;
                const tabHtml = `
                    <button class="tab-btn ${isActive ? 'active' : ''}" data-order-id="${order.id}">
                        <i data-lucide="user" style="width: 18px; height: 18px;"></i>
                        <span>#${order.id}</span>
                        ${cartCount > 0 ? '<span class="tab-badge"></span>' : ''}
                    </button>
                `;
                $container.append(tabHtml);
            });
        }

        function renderCategoryFilters() { 
            const $bar = $('#filter-bar');
            $bar.empty();
            // Get unique categories and add "All"
            const categories = ["All", ...new Set(POS_STATE.products.map(p => p.category).filter(c => c))].sort();

            categories.forEach(cat => {
                const isActive = POS_STATE.selectedCategory === cat;
                $bar.append(`
                    <button class="category-btn ${isActive ? 'active' : ''}" data-category="${cat}">
                        ${cat}
                    </button>
                `);
            });
        }

        function renderProducts(query = '') { 
            const $gridRow = $('#product-grid-row');
            const $popularGridRow = $('#popular-items-grid');
            $gridRow.empty();
            $popularGridRow.empty();
            const lowerQuery = query.toLowerCase();
            const order = getActiveOrder();
            const isMember = order.customer.tier;

            let filteredProducts = POS_STATE.products;

            if (POS_STATE.selectedCategory !== "All") {
                filteredProducts = filteredProducts.filter(p => p.category === POS_STATE.selectedCategory);
            }
            if (query) {
                filteredProducts = filteredProducts.filter(p =>
                    p.name.toLowerCase().includes(lowerQuery) || p.brand.toLowerCase().includes(lowerQuery) || p.id.toString() === query
                );
            }
            
            // Render Quick Picks if no search or category filter is active
            if (query === '' && POS_STATE.selectedCategory === 'All') {
                const popularItems = POS_STATE.products.filter(p => p.isPopular);
                popularItems.forEach(product => {
                    const price = isMember && product.memberPrice ? product.memberPrice : product.price;
                    $popularGridRow.append(`
                        <button class="popular-btn" data-product-id="${product.id}">
                            ${product.name.split(' ')[0]}<br>($${parseFloat(price).toFixed(2)})
                        </button>
                    `);
                });
                $('.popular-panel').show();
            } else {
                $('.popular-panel').hide();
            }

            // Render main product grid
            filteredProducts.forEach(product => {
                const price = isMember && product.memberPrice ? product.memberPrice : product.price;
                const rxFlag = product.requiresRx ? ' <span style="color: red; font-weight: bold;">[Rx]</span>' : '';
                const stockClass = product.stock < LOW_STOCK_THRESHOLD ? 'low-stock' : '';
                
                const cardHtml = `
                    <div class="product-grid-col"> 
                        <div class="product-card" data-product-id="${product.id}">
                            <div style="height: 40px; background-color: #f8f8f8; border-radius: 4px; margin-bottom: 0.5rem; display: flex; justify-content: center; align-items: center;">
                                <i data-lucide="${product.img || 'drug'}"></i>
                            </div>
                            <h4 style="font-size: 0.9rem; font-weight: bold; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${product.name}">${product.name}</h4>
                            <p style="font-size: 0.7rem; color: #666; margin: 0 0 0.5rem 0;">${product.brand}${rxFlag}</p>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="price-text">$${parseFloat(price).toFixed(2)}</span>
                                <span class="stock-badge ${stockClass}" style="font-size: 0.7rem; padding: 1px 4px; border-radius: 4px; background: ${stockClass ? '#ffcccc' : '#d1fae5'}; color: ${stockClass ? '#c53030' : '#065f46'};">
                                    ${product.stock} left
                                </span>
                            </div>
                        </div>
                    </div>
                `;
                $gridRow.append(cardHtml);
            });
        }

        function renderCart() { 
            const order = getActiveOrder();
            if (!order) return;

            const totals = calculateTotals();
            const customer = order.customer;
            
            const $itemsContainer = $('#cart-items');
            $itemsContainer.find('.cart-item-row').remove();
            
            order.cart.forEach((item, index) => {
                const price = customer.tier && item.product.memberPrice ? item.product.memberPrice : item.product.price;
                const lineTotal = (parseFloat(price) * item.qty) - item.lineDiscount;
                const rxFlag = item.product.requiresRx ? '<span style="color: red; font-weight: bold;">[Rx]</span>' : '';
                
                const itemRow = `
                    <div class="cart-item-row" data-index="${index}">
                        <span class="item-name">${item.product.name} ${rxFlag}</span>
                        <div class="item-qty-controls">
                            <button class="qty-btn" data-action="minus" data-index="${index}" style="padding: 2px 6px;">-</button>
                            <span style="padding: 0 8px; min-width: 20px; text-align: center;">${item.qty}</span>
                            <button class="qty-btn" data-action="plus" data-index="${index}" style="padding: 2px 6px;">+</button>
                        </div>
                        <span class="item-price">$${lineTotal.toFixed(2)}</span>
                        <button class="delete-item-btn" data-index="${index}" style="margin-left: 10px; background: none; border: none; color: #e53e3e; cursor: pointer;">
                            <i data-lucide="x" style="width: 16px;"></i>
                        </button>
                    </div>
                `;
                $itemsContainer.append(itemRow);
            });

            const cartIsEmpty = order.cart.length === 0;
            const pointsEarned = Math.floor(totals.netTotal / 10).toFixed(0);
            const totalDiscountDisplay = (totals.discount + totals.pointsRedeemed).toFixed(2);
            const remainingPoints = customer.points - (POS_STATE.pointsRedeemed * 100);

            $('#cart-footer').html(`
                <div style="font-weight: bold; padding-bottom: 0.5rem; border-bottom: 1px dashed #ddd;">
                    Loyalty: ${customer.name !== "Walk-in Customer" ? `${remainingPoints.toFixed(0)} Points Remaining` : 'Link Customer'}
                </div>
                
                <div class="total-row"><span>Subtotal:</span><span>$${totals.subtotal.toFixed(2)}</span></div>
                <div class="total-row" style="color: #c53030;"><span>Total Discount:</span><span>-$${totalDiscountDisplay}</span></div>
                <div class="total-row" style="color: #666;"><span>Tax (6%):</span><span>$${totals.tax.toFixed(2)}</span></div>
                
                <div class="total-row" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #ddd;">
                    <span style="font-size: 1.5rem; font-weight: bold;">GRAND TOTAL:</span>
                    <span class="total-grand">$${totals.total.toFixed(2)}</span>
                </div>

                <div style="text-align: right; font-size: 0.9rem; color: #00897B; margin-top: 5px;">
                    Potential Points Earned: +${pointsEarned}
                </div>

                <div class="action-btns">
                    <button id="suspend-order-btn" class="suspend-btn" ${cartIsEmpty ? 'disabled' : ''}><i data-lucide="clock" style="width: 20px; vertical-align: middle;"></i> SUSPEND</button>
                    <button id="cancel-order-btn" class="cancel-btn" ${cartIsEmpty ? 'disabled' : ''}><i data-lucide="trash-2" style="width: 20px; vertical-align: middle;"></i> CANCEL</button>
                    <button id="pay-button" class="pay-btn" ${cartIsEmpty ? 'disabled' : ''}><i data-lucide="chevrons-right" style="width: 20px; vertical-align: middle;"></i> PAY NOW</button>
                </div>
                <button id="no-sale-btn" class="no-sale-btn">No Sale / Open Drawer</button>
            `);
        }
        
        function renderCustomerHeader() { 
            const order = getActiveOrder();
            const customer = order.customer;
            const $header = $('#cart-header');
            $header.empty();

            $header.html(`
                <div style="font-weight: bold; font-size: 1.1rem; margin-bottom: 0.5rem;">
                    Customer: <span style="color: ${customer.tier ? '#e5a000' : '#00897B'};">${customer.name} (${customer.tier || 'New'})</span>
                </div>
                
                <div style="display: flex; gap: 5px;">
                    <input type="text" id="member-phone-input" placeholder="Name/Phone/ID" value="${customer.phone || ''}" style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <button id="lookup-member-btn" style="background-color: #4a5568; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;">Lookup</button>
                </div>
                
                ${customer.points > 0 ? `
                <div style="margin-top: 0.5rem; padding: 0.5rem; border: 1px solid #ffe0b2; background-color: #fff8e1; border-radius: 4px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.9rem; font-weight: bold;">Available Points:</span>
                        <span style="font-size: 1.2rem; font-weight: bold; color: #e5a000;">${customer.points}</span>
                    </div>
                </div>
                ` : `<p style="font-size: 0.8rem; color: #666; margin-top: 0.5rem;">No active membership or walk-in.</p>`}
            `);
        }

        function updateClock() {
            const now = new Date();
            const date = now.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
            const time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
            $('#current-datetime').text(`${date} ${time}`);
        }
        
        function renderStaffNoteDisplay() {
            const note = POS_STATE.staffNote;
            const time = localStorage.getItem('staffNoteTime') || 'Never';
            
            $('#staff-note-area').val(note === "No current staff notes." ? '' : note);
            $('#last-note-display').text(`${time}`);
            $('#note-status').empty();
        }

        // =====================================================================
        // 7. CALCULATION & PRICING LOGIC
        // =====================================================================

        function calculateTotals() { 
            const order = getActiveOrder();
            if (!order) return { subtotal: 0, tax: 0, total: 0, discount: 0, netTotal: 0, pointsRedeemed: 0 };
            const customer = order.customer;
            
            let subtotal = 0;
            let promotionDiscount = 0;
            let lineDiscountTotal = 0;
            
            // 1. Calculate Subtotal and Line Discounts
            order.cart.forEach(item => {
                const basePrice = customer.tier && item.product.memberPrice ? item.product.memberPrice : item.product.price;
                subtotal += (parseFloat(basePrice) * item.qty);
                lineDiscountTotal += item.lineDiscount || 0;
            });
            
            // 2. Calculate Cart Discount (manual discount)
            let cartDiscount = order.cartDiscount || 0;
            
            // 3. Apply Promotions
            POS_STATE.promotions.forEach(promo => {
                if (promo.isActive) {
                    if (promo.type === 'BOGO') {
                        const requiredItem = order.cart.find(i => i.product.id === promo.requiredProductId);
                        const discountItem = order.cart.find(i => i.product.id === promo.discountProductId);

                        if (requiredItem && discountItem) {
                            const price = customer.tier && discountItem.product.memberPrice ? discountItem.product.memberPrice : discountItem.product.price;
                            const freeQty = Math.min(requiredItem.qty, discountItem.qty);
                            if (freeQty > 0) {
                                 promotionDiscount += (freeQty * parseFloat(price)) * (promo.discountPercent / 100);
                            }
                        }
                    } else if (promo.type === 'CATEGORY_PERCENT') {
                        order.cart.filter(i => i.product.category === promo.category).forEach(item => {
                            const price = customer.tier && item.product.memberPrice ? item.product.memberPrice : item.product.price;
                            promotionDiscount += (item.qty * parseFloat(price)) * (promo.percent / 100);
                        });
                    }
                }
            });
            
            const totalDiscount = promotionDiscount + lineDiscountTotal + cartDiscount;
            
            // 4. Net Total (Pre-Tax, Pre-Points)
            const netTotal = Math.max(0, subtotal - totalDiscount); 
            
            // 5. Apply Points Redemption
            const pointsRedeemed = POS_STATE.pointsRedeemed; // This value is set in the payment modal logic
            const postPointsTotal = Math.max(0, netTotal - pointsRedeemed);
            
            // 6. Calculate Tax and Grand Total
            const taxRate = 0.06;
            const tax = postPointsTotal * taxRate;
            const total = postPointsTotal + tax;
            
            return { 
                subtotal, 
                tax: parseFloat(tax.toFixed(2)), 
                total: parseFloat(total.toFixed(2)), 
                discount: parseFloat(totalDiscount.toFixed(2)), 
                netTotal: parseFloat(netTotal.toFixed(2)), // Total before points and tax
                postPointsTotal: parseFloat(postPointsTotal.toFixed(2)), // Total before tax
                pointsRedeemed: parseFloat(pointsRedeemed.toFixed(2))
            };
        }


        // =====================================================================
        // 8. PAYMENT & TRANSACTION LOGIC
        // =====================================================================
        
        function renderPaymentModal() { 
            const totals = calculateTotals();
            const paid = paymentBreakdown.reduce((sum, p) => sum + p.amount, 0);
            const remaining = totals.total - paid;

            $('#modal-total-display').text(`$${totals.total.toFixed(2)}`);
            $('#modal-remaining-display').text(`$${Math.max(0, remaining).toFixed(2)}`);
            // Set the default payment amount to the remaining balance
            $('#payment-amount').val(Math.max(0, remaining).toFixed(2));
            $('#change-display').empty();
            
            // Re-render points area
            renderPaymentLoyaltyTools();
            renderPaymentModalDetails();
            $('#payment-modal-overlay').css('display', 'flex'); // Use display: flex
        }
        
        function renderPaymentLoyaltyTools() {
             const totals = calculateTotals();
             const order = getActiveOrder();
             const customer = order.customer;
             const $area = $('#loyalty-tools-area');
             $area.empty();
             
             if (customer.points > 0) {
                 const pointsValue = customer.points / 100; // Assuming 100 points = $1 redemption value
                 const redeemableAmount = Math.min(pointsValue, totals.postPointsTotal);
                 
                 $area.html(`
                    <div style="margin-top: 1rem; padding: 0.8rem; border: 1px solid #00897B; background-color: #e0f2f1; border-radius: 4px; text-align: center;">
                        <p style="margin: 0; font-weight: bold; color: #00897B;">Loyalty Account: ${customer.name}</p>
                        <p style="margin: 5px 0 10px 0;">Available Points: ${customer.points} ($${pointsValue.toFixed(2)} value)</p>
                        
                        ${POS_STATE.pointsRedeemed > 0 ? 
                            `<button id="remove-points-btn" style="background-color: #e53e3e; color: white; padding: 8px; border: none; border-radius: 4px;">Remove Points ($${POS_STATE.pointsRedeemed.toFixed(2)})</button>` :
                            `<button id="apply-points-btn" data-redeem-amount="${redeemableAmount.toFixed(2)}"
                                style="background-color: #00897B; color: white; padding: 8px; border: none; border-radius: 4px;">
                                Apply Max Points ($${redeemableAmount.toFixed(2)})
                            </button>`
                        }
                    </div>
                 `);
             }
        }
        
        function recordPayment(amount, method, reference = '') { 
            const totals = calculateTotals();
            let paid = paymentBreakdown.reduce((sum, p) => sum + p.amount, 0);
            let remaining = totals.total - paid;
            
            let change = 0;

            if (amount <= 0) {
                // If amount is 0, it's a trigger for calculation update (like points redemption)
                if (method !== 'points') return; 
            }

            if (method === 'cash') {
                // Cash can overshoot the remaining balance
                if (amount > remaining) {
                    change = amount - remaining;
                    amount = remaining;
                }
            } else {
                // Non-cash payments cannot overshoot the remaining balance
                if (amount > remaining) {
                    amount = remaining;
                }
            }
            
            // Only add payment to breakdown if it's a real monetary payment
            if (amount > 0 && method !== 'points') {
                 paymentBreakdown.push({ method, amount: parseFloat(amount.toFixed(2)), reference });
                 paid += amount;
                 remaining = totals.total - paid;
            }


            if (remaining <= 0) {
                 // Finalize the transaction
                 $('#change-display').html(`✅ Transaction Paid. **CHANGE DUE: $${Math.max(0, change).toFixed(2)}**`);
                 setTimeout(() => finalizeTransaction(change), 1500);
            } else {
                // Update the modal immediately
                renderPaymentModal(); 
            }
        }
        
        function finalizeTransaction(changeDue) { 
             const totals = calculateTotals();
             const order = getActiveOrder();
             const pointsEarned = Math.floor(totals.netTotal / 10);
             
             // SIMULATION: Log the completed order and remove the active order
             POS_STATE.completedOrders.push({
                 id: order.id, customerName: order.customer.name, total: totals.total, 
                 date: new Date().toLocaleString(), items: order.cart.length
             });

             alert(`
                --- TRANSACTION COMPLETE ---
                Order ID: #${order.id}
                ----------------
                Total: $${totals.total.toFixed(2)}
                Paid: $${(totals.total + changeDue).toFixed(2)}
                Change: $${changeDue.toFixed(2)}
                --- Payment Breakdown ---
                ${paymentBreakdown.map(p => `${p.method.toUpperCase()}: $${p.amount.toFixed(2)}`).join('\n')}
                --- Loyalty ---
                Points Earned: +${pointsEarned}
             `);
             
             POS_STATE.orders = POS_STATE.orders.filter(o => o.id.toString() !== POS_STATE.activeOrderId.toString());
             if (POS_STATE.orders.length === 0) createNewOrder();
             else POS_STATE.activeOrderId = POS_STATE.orders[0].id; // Switch to the next available order
             
             paymentBreakdown = [];
             POS_STATE.pointsRedeemed = 0;
             updateStateAndRender();
             $('#payment-modal-overlay').hide();
        }

        function renderPaymentModalDetails() { 
            const totals = calculateTotals();
            const paid = paymentBreakdown.reduce((sum, p) => sum + p.amount, 0);
            
            const $breakdown = $('#payment-breakdown');
            $breakdown.empty();
            
            // Display cash/card payments
            paymentBreakdown.forEach(p => {
                $breakdown.append(`
                    <div style="display: flex; justify-content: space-between; font-size: 0.9rem; padding: 4px 0; border-bottom: 1px dotted #ccc;">
                        <span>${p.method.toUpperCase()} (${p.reference || 'N/A'})</span>
                        <span style="font-weight: bold;">$${p.amount.toFixed(2)}</span>
                    </div>
                `);
            });
            
            // Display points redemption (as a discount, not a payment)
            const pointsHtml = totals.pointsRedeemed > 0 ? `<div style="display: flex; justify-content: space-between; font-size: 0.9rem; padding: 4px 0; border-bottom: 1px dotted #ccc; color: #e5a000;"><span>POINTS REDEEMED:</span><span style="font-weight: bold;">-$${totals.pointsRedeemed.toFixed(2)}</span></div>` : '';

            $breakdown.append(pointsHtml);
            
            $breakdown.append(`<div style="font-size: 1.2rem; font-weight: bold; color: #00897B; margin-top: 1rem;">TOTAL TENDERED: $${paid.toFixed(2)}</div>`);
        }
        
        // =====================================================================
        // 9. EVENT HANDLERS
        // =====================================================================
        
        $(document).ready(function() {
            // Initial render and start the clock
            updateStateAndRender();
            updateClock();
            setInterval(updateClock, 1000); 
            
            // --- TOOLS MODAL HANDLERS ---
            $('#tools-btn').on('click', function() {
                $('#tools-modal-overlay').css('display', 'flex');
                renderStaffNoteDisplay(); // Ensure the note is loaded when modal opens
            });
            $('#close-tools-btn').on('click', function() {
                $('#tools-modal-overlay').hide();
            });
            
            // Order Search Logic
            $('#execute-order-search').on('click', function() {
                const query = $('#order-search-input').val().trim().toLowerCase();
                const $results = $('#order-search-results');
                $results.empty();
                
                if (query.length < 2) {
                    $results.html('<p style="text-align: center; color: red;">Please enter at least 2 characters to search.</p>');
                    return;
                }
                
                const foundOrders = POS_STATE.completedOrders.filter(order => 
                    order.id.toString().includes(query) || 
                    order.customerName.toLowerCase().includes(query) || 
                    order.date.includes(query)
                );
                
                if (foundOrders.length === 0) {
                    $results.html('<p style="text-align: center; color: #999;">No completed orders matched your search criteria.</p>');
                    return;
                }
                
                foundOrders.forEach(order => {
                    $results.append(`
                        <div class="found-order-item">
                            <span>#${order.id} - ${order.customerName}</span>
                            <span style="font-weight: bold; color: #00897B;">$${order.total.toFixed(2)}</span>
                            <span style="font-size: 0.8em; color: #666;">(${order.date.split(' ')[0]})</span>
                        </div>
                    `);
                });
            });
            
            // Staff Note Logic
            $('#save-staff-note').on('click', function() {
                const note = $('#staff-note-area').val();
                const time = new Date().toLocaleString();
                
                POS_STATE.staffNote = note;
                localStorage.setItem('staffNote', note);
                localStorage.setItem('staffNoteTime', time);
                
                $('#note-status').text(`Note saved successfully on ${time}`);
                renderStaffNoteDisplay();
            });

            // --- TAB AND SUSPENDED ORDER HANDLERS ---
            $(document).on('click', '.tab-btn:not(.tab-add-btn, #tools-btn, #show-suspended-btn)', function() {
                POS_STATE.activeOrderId = $(this).data('order-id').toString();
                paymentBreakdown = [];
                POS_STATE.pointsRedeemed = 0;
                updateStateAndRender();
            });
            $('#new-order-btn').on('click', createNewOrder);

            $(document).on('click', '#suspend-order-btn:not([disabled])', function() {
                const activeOrder = getActiveOrder();
                activeOrder.status = 'suspended';
                // Add current customer/payment context for retrieval
                activeOrder.lastUpdated = new Date().toLocaleString();
                POS_STATE.suspendedOrders.push(activeOrder);
                
                POS_STATE.orders = POS_STATE.orders.filter(o => o.id.toString() !== activeOrder.id.toString());
                
                createNewOrder();
                alert(`Order #${activeOrder.id} suspended and put on hold.`);
            });
            
            $(document).on('click', '#show-suspended-btn', function() {
                const $list = $('#suspended-list');
                $list.empty();
                
                if (POS_STATE.suspendedOrders.length === 0) {
                    $list.html('<p style="text-align: center; color: #666;">No orders currently suspended.</p>');
                } else {
                    POS_STATE.suspendedOrders.forEach(order => {
                        $list.append(`
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #eee;">
                                <span>Order #${order.id} (${order.customer.name}) - <span style="font-size: 0.8em; color: #999;">${order.lastUpdated.split(',')[1]}</span></span>
                                <button class="retrieve-suspended-btn" data-order-id="${order.id}" style="background: #00897B; color: white; border: none; padding: 5px 10px; border-radius: 4px;">Retrieve</button>
                            </div>
                        `);
                    });
                }
                $('#suspended-modal-overlay').css('display', 'flex');
            });
            
            $(document).on('click', '.retrieve-suspended-btn', function() {
                const orderId = $(this).data('order-id').toString();
                const index = POS_STATE.suspendedOrders.findIndex(o => o.id.toString() === orderId);
                
                if (index !== -1) {
                    const order = POS_STATE.suspendedOrders.splice(index, 1)[0];
                    order.status = 'active';
                    POS_STATE.orders.push(order);
                    POS_STATE.activeOrderId = order.id;
                    paymentBreakdown = []; // Clear old breakdown, start fresh payment on retrieve
                    POS_STATE.pointsRedeemed = 0; // Clear old points status
                    updateStateAndRender();
                    $('#suspended-modal-overlay').hide();
                }
            });
            $(document).on('click', '#close-suspended-modal', () => $('#suspended-modal-overlay').hide());

            // --- PRODUCT & CART HANDLERS ---
            
            $(document).on('click', '.product-card, .popular-btn', function() {
                const productId = parseInt($(this).data('product-id'));
                const product = POS_STATE.products.find(p => p.id === productId);
                const order = getActiveOrder();
                const existingItem = order.cart.find(item => item.product.id === productId);
                
                if (!product) return;
                
                if (existingItem) {
                    existingItem.qty += 1;
                } else {
                    const batch = product.batches && product.batches.length > 0 ? product.batches[0] : 'N/A';
                    order.cart.push({ product, qty: 1, selectedBatch: batch, lineDiscount: 0.00 });
                }
                
                const $el = $(this).closest('.product-card, .popular-btn');
                $el.addClass('clicked-success');
                setTimeout(() => { $el.removeClass('clicked-success'); }, 200);

                updateStateAndRender();
            });
            
            $(document).on('click', '.qty-btn', function() {
                const index = parseInt($(this).data('index'));
                const action = $(this).data('action');
                const order = getActiveOrder();
                
                if (action === 'plus') {
                    order.cart[index].qty += 1;
                } else if (action === 'minus' && order.cart[index].qty > 1) {
                    order.cart[index].qty -= 1;
                }
                updateStateAndRender();
            });

            $(document).on('click', '.delete-item-btn', function() {
                const index = parseInt($(this).data('index'));
                const order = getActiveOrder();
                order.cart.splice(index, 1);
                updateStateAndRender();
            });

            $(document).on('click', '.category-btn', function() {
                POS_STATE.selectedCategory = $(this).data('category');
                // The re-rendering of categories is handled by updateStateAndRender via renderCategoryFilters/renderProducts
                $('#search-input').val(''); 
                renderProducts(); // Render products for the new category
                renderCategoryFilters(); // Re-render to update active class
            });
            
            $('#search-input').on('input', function() {
                // Clear category selection when searching
                POS_STATE.selectedCategory = 'All';
                renderProducts($(this).val());
                renderCategoryFilters();
            });

            $(document).on('click', '#cancel-order-btn:not([disabled])', function() {
                const activeOrder = getActiveOrder();
                if (confirm(`Are you sure you want to VOID/CANCEL Order #${activeOrder.id}? All items will be removed.`)) {
                    activeOrder.cart = [];
                    activeOrder.cartDiscount = 0.00;
                    POS_STATE.pointsRedeemed = 0;
                    updateStateAndRender();
                }
            });

            $(document).on('click', '#no-sale-btn', function() {
                alert("Drawer opened. NO SALE logged.");
            });

            // --- CUSTOMER & LOYALTY HANDLERS ---

            $(document).on('click', '#lookup-member-btn', function() {
                const phoneOrId = $('#member-phone-input').val().trim();
                const member = POS_STATE.customers.find(c => c.phone === phoneOrId || c.id.toString() === phoneOrId);
                const activeOrder = getActiveOrder();

                if (member && member.id !== 1) { // 1 is Walk-in customer
                    activeOrder.customer = member;
                    alert(`Member Found: ${member.name}. Tier: ${member.tier}. Points: ${member.points}`);
                } else {
                    activeOrder.customer = POS_STATE.customers[0]; // Reset to Walk-in
                    alert(`Member Not Found. Resetting to Walk-in.`);
                }
                updateStateAndRender();
            });

            // --- PAYMENT MODAL HANDLERS ---

            $(document).on('click', '#pay-button:not([disabled])', function() {
                paymentBreakdown = [];
                // Only reset pointsRedeemed if it's the first time opening the payment modal for this order
                if (POS_STATE.pointsRedeemed === 0) {
                   // Auto apply max points if customer is linked
                   const totals = calculateTotals();
                   const customer = getActiveOrder().customer;
                   if (customer.points > 0) {
                        const pointsValue = customer.points / 100;
                        const redeemableAmount = Math.min(pointsValue, totals.postPointsTotal);
                        if (redeemableAmount > 0) {
                            POS_STATE.pointsRedeemed = redeemableAmount;
                        }
                   }
                }
                renderPaymentModal();
            });
            
            $(document).on('click', '#close-modal-btn', function() {
                $('#payment-modal-overlay').hide();
                paymentBreakdown = [];
                POS_STATE.pointsRedeemed = 0; // Reset points on payment cancellation
                renderCart();
            });

            $(document).on('click', '.payment-option', function() {
                const method = $(this).data('method');
                $('.payment-option').removeClass('selected');
                $(this).addClass('selected');
                
                $('#payment-input-area').show();
                $('#po-lookup-area').hide().empty();
                $('#change-display').empty();
                
                const totals = calculateTotals();
                const paid = paymentBreakdown.reduce((sum, p) => sum + p.amount, 0);
                const remaining = totals.total - paid;

                if (method === 'po') {
                     $('#payment-input-area').hide();
                     $('#po-lookup-area').html(`
                         <h4 style="margin-top: 0;">Purchase Order / Account Payment</h4>
                         <input type="text" id="po-ref-input" placeholder="Enter Account ID / PO Ref (Try CORP-456)" style="width: 100%; padding: 8px; margin-bottom: 8px;">
                         <button id="verify-po-btn" style="background: #3182CE; color: white; padding: 8px; border: none; border-radius: 4px; width: 100%;">Verify Account</button>
                         <div id="po-balance-display" style="margin-top: 10px;"></div>
                     `).show();
                } else if (method === 'points') {
                    // Points redemption is now handled via the #loyalty-tools-area buttons (Apply/Remove)
                    // We don't want the user to enter an amount for points.
                    $('.payment-option').removeClass('selected'); // Deselect points option
                    alert("Use the 'Apply Points' button in the Loyalty Account section to redeem points.");
                } else {
                    $('#payment-amount').val(Math.max(0, remaining).toFixed(2));
                }
            });

            $(document).on('click', '#add-payment-btn', function() {
                const amount = parseFloat($('#payment-amount').val());
                const method = $('.payment-option.selected').data('method');
                
                if (!method || method === 'po' || method === 'points') { 
                    alert('Select a valid payment method first (Cash, Card, E-Wallet, Voucher).'); return; 
                }
                if (isNaN(amount) || amount <= 0) { alert('Enter a valid amount.'); return; }
                
                recordPayment(amount, method);
            });
            
            // --- LOYALTY POINTS BUTTON HANDLERS (Within Modal) ---
            $(document).on('click', '#apply-points-btn', function() {
                const redeemAmount = parseFloat($(this).data('redeem-amount'));
                if (redeemAmount > 0) {
                    POS_STATE.pointsRedeemed = redeemAmount;
                    recordPayment(0, 'points'); // Trigger recalculation and modal refresh
                } else {
                    alert("Not enough points or total bill is zero/discounted fully.");
                }
            });
            
            $(document).on('click', '#remove-points-btn', function() {
                 POS_STATE.pointsRedeemed = 0;
                 recordPayment(0, 'points'); // Trigger recalculation and modal refresh
            });

            // --- PO/ACCOUNT PAYMENT HANDLERS (Within Modal) ---
            $(document).on('click', '#verify-po-btn', function() {
                 const poRef = $('#po-ref-input').val();
                 if (poRef === 'CORP-456') {
                     const totals = calculateTotals();
                     const paid = paymentBreakdown.reduce((sum, p) => sum + p.amount, 0);
                     const remaining = totals.total - paid;
                     $('#po-balance-display').html(`
                         <p style="color: green; font-weight: bold;">✅ Verified: ACME Corporation</p>
                         <p>Available Credit: $5,000.00 (Sufficient)</p>
                         <button id="apply-full-po" data-amount="${remaining.toFixed(2)}" data-ref="${poRef}"
                             style="background: #48BB78; color: white; padding: 10px; border: none; border-radius: 4px; width: 100%; margin-top: 10px;">
                             Apply $${remaining.toFixed(2)} to PO
                         </button>
                     `);
                 } else {
                     $('#po-balance-display').html('<p style="color: red;">❌ Invalid or Unregistered Account ID.</p>');
                 }
            });
            $(document).on('click', '#apply-full-po', function() {
                // Ensure PO is selected before recording payment
                $('.payment-option[data-method="po"]').addClass('selected');
                recordPayment(parseFloat($(this).data('amount')), 'PO', $(this).data('ref'));
            });

        });
    </script>
</body>
</html>