<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy POS Cashier Interface - Comprehensive Mockup</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* =====================================================================
           CORE LAYOUT STYLES
        ===================================================================== */
        body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f4f6f9; color: #333; }
        .pos-app { display: flex; height: 100vh; overflow: hidden; }

        .col-tabs { width: 80px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 1rem 0; box-shadow: 2px 0 5px rgba(0,0,0,0.05); z-index: 10; }
        .col-main-wrapper { flex-grow: 1; display: flex; flex-direction: row; }
        .col-main { flex-shrink: 0; width: 100%; display: flex; flex-direction: column; background-color: #f8f8f8; }
        .col-cart { width: 380px; background-color: #fff; border-left: 1px solid #e0e0e0; display: flex; flex-direction: column; box-shadow: -2px 0 5px rgba(0,0,0,0.1); z-index: 20; }

        .product-grid-container { flex-grow: 1; padding: 1rem; overflow-y: auto; }
        .product-grid-row { display: flex; flex-wrap: wrap; margin-left: -0.5rem; margin-right: -0.5rem; }
        .product-grid-col { flex-basis: 16.666%; padding-left: 0.5rem; padding-right: 0.5rem; margin-bottom: 1rem; }
        
        .popular-grid-row { display: flex; flex-wrap: wrap; padding: 0 1rem 1rem; }
        .popular-btn { background-color: #00897B; color: white; border: none; padding: 8px 5px; border-radius: 4px; font-size: 10px; font-weight: bold; line-height: 1.2; cursor: pointer; margin: 0.25rem; transition: background-color 0.1s; }
        .popular-btn:hover { background-color: #00695C; }

        /* HEADER */
        .system-header { padding: 10px 1rem; background-color: #00897B; color: white; display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; flex-shrink: 0; }
        .system-header .info-group { display: flex; align-items: center; margin-right: 20px; }
        .system-header .info-group i { margin-right: 5px; width: 16px; height: 16px; }
        .system-header .company-name { font-size: 1.2rem; font-weight: bold; }

        /* TABS */
        .tab-btn { width: 60px; height: 60px; margin: 0.5rem auto; border: 2px solid transparent; border-radius: 12px; background-color: #f0f0f0; display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; cursor: pointer; transition: all 0.2s; position: relative; }
        .tab-btn.active { background-color: #e6f7ff; border-color: #00897B; color: #00897B; }
        .tab-add-btn { background-color: #00897B !important; color: white !important; margin-bottom: 1rem; }
        .tab-badge { position: absolute; top: 0; right: 0; width: 8px; height: 8px; background-color: #e53e3e; border-radius: 50%; border: 2px solid white; }

        /* FILTERS & PRODUCTS */
        .search-header { padding: 1rem; border-bottom: 1px solid #eee; background-color: #fff; display: flex; }
        .filter-bar { padding: 0.5rem 1rem; border-bottom: 1px solid #eee; background-color: #fff; display: flex; gap: 8px; overflow-x: auto; flex-wrap: nowrap; flex-shrink: 0; max-height: 55px; }
        .category-btn { padding: 6px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 0.9rem; cursor: pointer; transition: all 0.2s; background-color: #f0f0f0; white-space: nowrap; }
        .category-btn.active { background-color: #00897B; color: white; border-color: #00897B; }
        
        .product-card { background-color: white; border: 1px solid #eee; border-radius: 6px; padding: 0.75rem; cursor: pointer; transition: all 0.2s; position: relative; height: 160px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,0.05); max-width: 250px; }
        .product-card:hover { transform: translateY(-2px); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .product-card.clicked-success { border: 2px solid #48BB78; background-color: #F0FFF4; } 
        .price-text { font-size: 1.1rem; font-weight: bold; color: #00897B; }
        .low-stock { background-color: #ffcccc; color: #c53030; }
        
        /* CART */
        .cart-header { padding: 1rem; background-color: #e6f7ff; border-bottom: 1px solid #b3e0ff; flex-shrink: 0; }
        .cart-item-row { display: flex; flex-direction: column; padding: 8px 10px; border-bottom: 1px dotted #ccc; font-size: 0.9rem; background: #fff; }
        .cart-item-top { display: flex; justify-content: space-between; align-items: center; width: 100%; }
        .cart-item-meta { display: flex; justify-content: flex-end; align-items: center; width: 100%; margin-top: 4px; font-size: 0.75rem; color: #666; }
        
        .item-name { flex: 1; font-weight: 500; }
        .item-qty-controls { display: flex; align-items: center; }
        .item-price { width: 60px; text-align: right; font-weight: bold; }

        /* SALES REP TAG IN CART */
        .sales-rep-tag { background: #eee; padding: 2px 6px; border-radius: 4px; cursor: pointer; margin-right: 5px; display: flex; align-items: center; gap: 3px; border: 1px solid transparent; }
        .sales-rep-tag:hover { background: #e0e0e0; border-color: #ccc; }
        .sales-rep-tag.highlight { color: #00897B; font-weight: bold; background-color: #e0f2f1; }

        .cart-footer { padding: 1rem; border-top: 1px solid #eee; background-color: #fff; flex-shrink: 0; }
        .total-row { display: flex; justify-content: space-between; font-size: 1rem; margin-top: 0.5rem; }
        .total-grand { font-size: 1.5rem; font-weight: bold; color: #00897B; }
        .action-btns { display: flex; gap: 10px; margin-top: 1rem; }
        .pay-btn, .cancel-btn, .suspend-btn { padding: 1rem; font-size: 1.2rem; font-weight: bold; border-radius: 8px; border: none; cursor: pointer; flex: 1; transition: background-color 0.1s; }
        .pay-btn { background-color: #00897B; color: white; flex: 2; }
        .cancel-btn { background-color: #e53e3e; color: white; }
        .suspend-btn { background-color: #ffc107; color: #333; }
        .no-sale-btn { background-color: #f0f0f0; color: #333; padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 4px; margin-top: 0.5rem; width: 100%;}
        .qty-btn { background-color: #e6f7ff; border: 1px solid #b3e0ff; border-radius: 4px; color: #00897B; font-weight: bold; cursor: pointer; }
        
        /* MODALS */
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); display: none; justify-content: center; align-items: center; z-index: 1000; }
        .payment-modal, .tools-modal { background: white; width: 90%; max-width: 950px; height: 85vh; border-radius: 12px; display: flex; overflow: hidden; }
        .tools-modal { max-width: 700px; display: block; height: auto; max-height: 90vh; }
        .modal-left { flex: 2; padding: 2rem; border-right: 1px solid #eee; overflow-y: auto; }
        .modal-right { flex: 1; padding: 2rem; background-color: #f8f8f8; display: flex; flex-direction: column; justify-content: space-between; }
        
        .payment-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        .payment-option { background: #f0f0f0; border: 1px solid #ddd; padding: 1.5rem 1rem; border-radius: 8px; text-align: center; font-weight: bold; cursor: pointer; transition: all 0.2s; position: relative; }
        .payment-option:hover, .payment-option.selected { background: #00897B; color: white; border-color: #00897B; }
        
        /* NEW: Dynamic Input Areas in Payment Modal */
        .dynamic-input-area { margin-top: 15px; padding: 15px; background: #e6f7ff; border: 1px solid #b3e0ff; border-radius: 6px; display: none; }
        .dynamic-input-area.active { display: block; animation: fadeIn 0.3s; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }

        /* NEW: Item Sales Rep Modal */
        #item-rep-modal { position: absolute; background: white; border: 1px solid #ccc; box-shadow: 0 4px 10px rgba(0,0,0,0.2); border-radius: 4px; width: 220px; z-index: 9999; display: none; padding: 5px; }
        .rep-option { padding: 10px; cursor: pointer; border-bottom: 1px solid #eee; }
        .rep-option:hover { background-color: #f0f0f0; }

        /* Tool-Specific Styles */
        .admin-tool-section { margin-bottom: 2rem; padding: 1.5rem; border: 1px solid #eee; border-radius: 8px; background-color: #fcfcfc; }
        #order-search-results { max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; margin-top: 1rem; background-color: #fff;}
        .found-order-item { padding: 10px; border-bottom: 1px dotted #ccc; display: flex; justify-content: space-between; align-items: center; }
        
        input, select, textarea { padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; width: 100%; }
    </style>
</head>
<body>

    <div id="pos-app" class="pos-app">
        <div class="col-tabs">
            <button id="new-order-btn" class="tab-btn tab-add-btn">
                <i data-lucide="plus" style="width: 20px; height: 20px;"></i> New
            </button>
            <div id="order-tabs-container" style="flex-grow: 1; overflow-y: auto;"></div>
            <button id="show-suspended-btn" class="tab-btn" style="margin-top: 0.5rem; background-color: #f0f0f0;">
                <i data-lucide="clock" style="width: 18px; height: 18px; color: #ffc107;"></i>
                <span style="color: #333;">Hold (<span id="suspended-count">0</span>)</span>
            </button>
            <button id="tools-btn" class="tab-btn" style="margin-top: 0.5rem; background-color: #f0f0f0;">
                <i data-lucide="settings" style="width: 18px; height: 18px; color: #666;"></i>
                <span style="color: #333;">Tools</span>
            </button>
        </div>

        <div class="col-main-wrapper">
            <div id="col-main-panel" class="col-main">
                <div class="system-header">
                    <div class="info-group">
                        <i data-lucide="building" style="width: 20px; height: 20px;"></i>
                        <span class="company-name">HealthFirst Pharmacy Inc.</span>
                    </div>
                    <div class="info-group">
                        <i data-lucide="user-check"></i>
                        <span id="cashier-display">Cashier: <b>Lisa (ID: 882)</b></span>
                    </div>
                    <div class="info-group">
                        <i data-lucide="calendar"></i>
                        <span id="current-datetime">Loading...</span>
                    </div>
                </div>

                <div class="search-header">
                    <div style="position: relative; flex: 1; max-width: 600px;">
                        <i data-lucide="search" style="position: absolute; left: 10px; top: 12px; width: 20px; color: #999;"></i>
                        <input type="text" id="search-input" placeholder="Scan Barcode or Search Item Name/Code..." style="width: 100%; padding: 10px 10px 10px 40px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                </div>
                
                <div id="filter-bar" class="filter-bar"></div>

                <div class="popular-panel">
                    <div style="font-weight: bold; font-size: 0.9rem; color: #00897B; padding: 0 1rem 0.5rem;">🔥 Quick Picks (Popular Items)</div>
                    <div id="popular-items-grid" class="popular-grid-row"></div>
                </div>

                <div id="product-grid-container" class="product-grid-container">
                    <div id="product-grid-row" class="product-grid-row"></div>
                </div>
            </div>
        </div>

        <div class="col-cart">
            <div id="cart-header" class="cart-header"></div>
            
            <div style="padding: 10px; background: #fff; border-bottom: 1px solid #ddd; display: flex; align-items: center; justify-content: space-between;">
                <label style="font-size: 0.85rem; font-weight: bold; color: #555;">Order Sales Rep:</label>
                <select id="global-sales-rep" style="width: 65%; padding: 5px; font-size: 0.9rem;">
                    </select>
            </div>

            <div id="cart-items" class="cart-items" style="flex-grow: 1; overflow-y: auto;">
                <div style="padding: 10px; display: flex; justify-content: space-between; font-weight: bold; border-bottom: 2px solid #ddd;">
                    <span style="flex-basis: 45%;">Item / Comm</span>
                    <span style="flex-basis: 30%; text-align: right;">Qty</span>
                    <span style="flex-basis: 25%; text-align: right;">Price</span>
                </div>
            </div>
            <div id="cart-footer" class="cart-footer"></div>
        </div>
    </div>
    
    <div id="item-rep-modal"></div>

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
                <div id="order-search-results"><p style="text-align: center; color: #999;">Results will appear here.</p></div>
            </div>
            
            <div class="admin-tool-section">
                <h3><i data-lucide="notebook"></i> Write Internal Staff Note</h3>
                <textarea id="staff-note-area" placeholder="Write down important information..." rows="6" style="width: 100%; resize: vertical;"></textarea>
                <button id="save-staff-note" style="margin-top: 10px; background-color: #00897B; color: white; padding: 10px; border: none; border-radius: 4px; width: 100%;">Save Note Locally</button>
                <div style="margin-top: 15px; font-size: 0.8rem; color: #666;">Last Saved Note: <span id="last-note-display">N/A</span></div>
            </div>
        </div>
    </div>

    <div id="payment-modal-overlay" class="modal-overlay">
        <div class="payment-modal">
            <div class="modal-left">
                <h2>Select Payment Method</h2>
                
                <div class="payment-grid">
                    <div class="payment-option" data-method="cash"><i data-lucide="wallet"></i><p>Cash</p></div>
                    <div class="payment-option" data-method="card"><i data-lucide="credit-card"></i><p>Card</p></div>
                    <div class="payment-option" data-method="voucher"><i data-lucide="ticket"></i><p>Voucher</p></div>
                    <div class="payment-option" data-method="points"><i data-lucide="star"></i><p>Loyalty Pts</p></div>
                    <div class="payment-option" data-method="po"><i data-lucide="file-text"></i><p>PO / Account</p></div>
                    <div class="payment-option" data-method="custom"><i data-lucide="grid"></i><p>Custom/Other</p></div>
                </div>

                <div id="area-voucher" class="dynamic-input-area">
                    <h4>Redeem Voucher Code</h4>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" id="voucher-code-input" placeholder="e.g. SAVE10">
                        <button id="apply-voucher-btn" style="background: #00897B; color: white; border: none; padding: 0 20px; border-radius: 4px;">Apply</button>
                    </div>
                    <p id="voucher-msg" style="margin-top: 5px; font-size: 0.9rem;"></p>
                </div>

                <div id="area-points" class="dynamic-input-area">
                    <h4>Redeem Loyalty Points</h4>
                    <p style="font-size: 0.9rem; color: #666;">Available: <span id="modal-pts-avail">0</span> pts</p>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="number" id="points-input" placeholder="Points to use">
                        <span>= $<span id="points-conversion-preview">0.00</span></span>
                    </div>
                    <button id="apply-points-btn" style="margin-top: 10px; width: 100%; background: #e5a000; color: white; border: none; padding: 10px; border-radius: 4px;">Redeem Points</button>
                </div>
                
                <div id="area-custom" class="dynamic-input-area">
                    <h4>Select Custom Method</h4>
                    <select id="custom-method-select">
                        <option value="">-- Choose Method --</option>
                        <option value="GrabPay">E-Wallet: GrabPay</option>
                        <option value="TnG">E-Wallet: Touch 'n Go</option>
                        <option value="Boost">E-Wallet: Boost</option>
                        <option value="Insurance-AIA">Insurance: AIA Panel</option>
                        <option value="Cheque">Bank Cheque</option>
                    </select>
                </div>

                <div id="area-po" class="dynamic-input-area">
                    <h4>PO / Corporate Account</h4>
                    <input type="text" id="po-ref-input" placeholder="Account ID (Try CORP-456)">
                    <button id="verify-po-btn" style="margin-top:5px; background: #3182CE; color: white; padding: 8px; border: none; border-radius: 4px; width: 100%;">Verify Account</button>
                    <div id="po-balance-display" style="margin-top: 10px;"></div>
                </div>

                <h3 style="margin-top: 2rem;">Payment Breakdown</h3>
                <div id="payment-breakdown" style="border: 1px solid #ddd; padding: 10px; min-height: 100px; border-radius: 6px;"></div>
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
                    
                    <button id="add-payment-btn" style="background-color: #00897B; color: white; padding: 10px; border: none; border-radius: 6px; width: 100%; font-weight: bold; margin-bottom: 1rem;">ADD PAYMENT</button>
                    
                    <div id="change-display" style="font-size: 1.2rem; font-weight: bold; text-align: center; color: #48BB78;"></div>
                </div>
                
                <div>
                    <button id="close-modal-btn" style="background-color: #666; color: white; padding: 10px; border: none; border-radius: 6px; width: 100%; font-weight: bold; margin-top: 1rem;">Cancel Transaction</button>
                </div>
            </div>
        </div>
    </div>


    <div id="suspended-modal-overlay" class="modal-overlay">
        <div style="background: white; padding: 2rem; border-radius: 8px; width: 500px;">
            <h2>Suspended Transactions</h2>
            <div id="suspended-list" style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;"></div>
            <button id="close-suspended-modal" style="margin-top: 1rem; padding: 10px; width: 100%; background: #666; color: white;">Close</button>
        </div>
    </div>

    <script>
        // =====================================================================
        // DATA & STATE
        // =====================================================================
        
        // NEW: Staff List
        const STAFF_LIST = [
            { id: 882, name: "Lisa (Cashier)", role: "Cashier" },
            { id: 101, name: "Dr. Aiman (Pharmacist)", role: "Sales" },
            { id: 102, name: "Sarah (Sales Rep)", role: "Sales" },
            { id: 103, name: "Mike (Store Assistant)", role: "Sales" }
        ];

        // NEW: Vouchers Mock
        const VOUCHERS = {
            "SAVE10": { type: "fixed", value: 10.00 },
            "WELCOME5": { type: "percent", value: 5 } // 5% off
        };
        
        const POS_STATE = {
            // Updated Products with commRate (Commission Rate)
            products: [
                { id: 101, name: "Amoxicillin 500mg (10s)", brand: "Amoxil", price: 15.00, stock: 45, requiresRx: true, isPopular: true, category: "Antibiotics", batches: ["B-2023-01"], memberPrice: 14.00, img: 'pill-bottle', commRate: 0.05 },
                { id: 102, name: "Paracetamol 500mg (20s)", brand: "Panadol", price: 5.50, stock: 120, requiresRx: false, isPopular: true, category: "Pain Relief", batches: ["B-GEN-01"], img: 'capsules', commRate: 0.02 },
                { id: 104, name: "Multivitamin (30s)", brand: "Centrum", price: 32.00, stock: 5, requiresRx: false, isPopular: true, category: "Supplements", batches: ["B-SUP-12"], memberPrice: 28.80, img: 'bottle', commRate: 0.10 },
                { id: 107, name: "Antihistamine (10s)", brand: "Zyrtec", price: 12.00, stock: 30, requiresRx: false, isPopular: true, category: "Allergy", batches: ["B-ANT-2"], img: 'pills', commRate: 0.05 },
                { id: 108, name: "Cough Syrup (200ml)", brand: "Delsym", price: 18.00, stock: 50, requiresRx: false, category: "Cough/Cold", batches: ["B-CUG-3"], img: 'syrup-bottle', commRate: 0.03 },
                { id: 109, name: "First Aid Kit", brand: "MediPack", price: 45.00, stock: 15, requiresRx: false, category: "First Aid", img: 'bandage', commRate: 0.05 },
                { id: 110, name: "Sunscreen SPF50", brand: "La Roche", price: 65.00, stock: 70, requiresRx: false, category: "Skin Care", img: 'sun', commRate: 0.08 },
                { id: 111, name: "Band-Aids (50pk)", brand: "Johnson", price: 8.50, stock: 10, requiresRx: false, category: "First Aid", img: 'bandage', commRate: 0.02 },
                { id: 112, name: "Loratadine 10mg", brand: "Generic", price: 9.90, stock: 2, requiresRx: false, category: "Allergy", img: 'pills', commRate: 0.05 },
                // Mocking remaining items to simulate the original 90+ items
                ...Array.from({ length: 90 }, (_, i) => ({ 
                    id: 200 + i, name: `Generic Item ${i + 1}`, brand: `Brand-${i + 1}`, price: (Math.random() * 50).toFixed(2), stock: Math.floor(Math.random() * 200),
                    category: ["Supplements", "First Aid", "Skin Care", "OTC", "Home Care"][Math.floor(Math.random() * 5)],
                    isPopular: Math.random() > 0.95, batches: [`B-X${i + 1}`], img: 'drug', commRate: 0.03
                }))
            ],
            customers: [
                { id: 1, name: "Walk-in Customer", points: 0, phone: "" },
                { id: 2, name: "Jane Doe (Gold)", points: 2450, phone: "0123456789", tier: "Gold", insurance: "Allianz" },
                { id: 3, name: "Mr. Smith", points: 550, phone: "0198765432", tier: "Silver" },
            ],
            orders: [], // Will be initialized
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
            activeOrderId: null,
            selectedCategory: "All",
            staffNote: localStorage.getItem('staffNote') || "No current staff notes.",
            loggedInUser: STAFF_LIST[0]
        };

        let paymentBreakdown = [];
        const LOW_STOCK_THRESHOLD = 10;

        // =====================================================================
        // STATE MANAGEMENT FUNCTIONS
        // =====================================================================

        function getActiveOrder() {
            return POS_STATE.orders.find(o => o.id.toString() === POS_STATE.activeOrderId.toString());
        }

        function createNewOrder() {
            const newId = (Math.random() * 100000).toFixed(0).padStart(4, '0');
            const newOrder = { 
                id: newId, 
                customer: POS_STATE.customers[0], 
                cart: [], 
                status: 'active', 
                payments: [], 
                cartDiscount: 0.00,
                // NEW: Order specific fields
                globalSalesRepId: POS_STATE.loggedInUser.id, 
                voucher: null, 
                pointsRedeemed: 0 
            };
            POS_STATE.orders.push(newOrder);
            POS_STATE.activeOrderId = newId;
            updateStateAndRender();
        }

        function updateStateAndRender() {
            renderTabs();
            renderCategoryFilters();
            renderCustomerHeader();
            renderCart(); // Contains the new sales rep logic
            renderProducts();
            renderStaffNoteDisplay();
            renderStaffSelect(); // Populate the global dropdown
            $('#suspended-count').text(POS_STATE.suspendedOrders.length);
            
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        // =====================================================================
        // RENDER FUNCTIONS
        // =====================================================================
        
        function renderStaffSelect() {
            const $select = $('#global-sales-rep');
            const currentVal = $select.val();
            $select.empty();
            STAFF_LIST.forEach(staff => {
                $select.append(`<option value="${staff.id}">${staff.name}</option>`);
            });
            
            const order = getActiveOrder();
            if(order) {
                $select.val(order.globalSalesRepId);
            }
        }

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
                const lineTotal = (parseFloat(price) * item.qty) - (item.lineDiscount || 0);
                const rxFlag = item.product.requiresRx ? '<span style="color: red; font-weight: bold;">[Rx]</span>' : '';
                
                // NEW: Sales Rep Logic
                const repId = item.salesRepId || order.globalSalesRepId;
                const repName = STAFF_LIST.find(s => s.id === repId)?.name.split(' ')[0] || 'Unknown';
                const isItemSpecific = item.salesRepId && item.salesRepId !== order.globalSalesRepId;

                const itemRow = `
                    <div class="cart-item-row" data-index="${index}">
                        <div class="cart-item-top">
                            <span class="item-name">${item.product.name} ${rxFlag}</span>
                            <div class="item-qty-controls">
                                <button class="qty-btn" data-action="minus" data-index="${index}" style="padding: 2px 6px;">-</button>
                                <span style="padding: 0 8px; min-width: 20px; text-align: center;">${item.qty}</span>
                                <button class="qty-btn" data-action="plus" data-index="${index}" style="padding: 2px 6px;">+</button>
                            </div>
                            <span class="item-price">$${lineTotal.toFixed(2)}</span>
                        </div>
                        <div class="cart-item-meta">
                            <div class="sales-rep-tag ${isItemSpecific ? 'highlight' : ''}" onclick="openItemRepModal(event, ${index})">
                                <i data-lucide="user" width="12"></i> 
                                ${isItemSpecific ? 'Item Rep: ' : 'Order Rep: '} ${repName}
                            </div>
                            <button class="delete-item-btn" data-index="${index}" style="margin-left: 10px; background: none; border: none; color: #e53e3e; cursor: pointer;">
                                <i data-lucide="x" style="width: 16px;"></i>
                            </button>
                        </div>
                    </div>
                `;
                $itemsContainer.append(itemRow);
            });

            const cartIsEmpty = order.cart.length === 0;
            const remainingPoints = customer.points - order.pointsRedeemed;

            $('#cart-footer').html(`
                <div style="font-weight: bold; padding-bottom: 0.5rem; border-bottom: 1px dashed #ddd;">
                    Loyalty: ${customer.name !== "Walk-in Customer" ? `${remainingPoints.toFixed(0)} Points Remaining` : 'Link Customer'}
                </div>
                
                <div class="total-row"><span>Subtotal:</span><span>$${totals.subtotal.toFixed(2)}</span></div>
                ${order.voucher ? `<div class="total-row" style="color:green;"><span>Voucher (${order.voucher.code})</span><span>-$${totals.voucherDed.toFixed(2)}</span></div>` : ''}
                ${order.pointsRedeemed ? `<div class="total-row" style="color:#e5a000;"><span>Points Red.</span><span>-$${totals.pointsDed.toFixed(2)}</span></div>` : ''}
                <div class="total-row" style="color: #666;"><span>Tax (6%):</span><span>$${totals.tax.toFixed(2)}</span></div>
                
                <div class="total-row" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #ddd;">
                    <span style="font-size: 1.5rem; font-weight: bold;">GRAND TOTAL:</span>
                    <span class="total-grand">$${totals.finalTotal.toFixed(2)}</span>
                </div>

                <div style="text-align: right; font-size: 0.9rem; color: #00897B; margin-top: 5px;">
                    Est. Commission: $${totals.totalCommission.toFixed(2)}
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
            `);
        }

        function updateClock() {
            const now = new Date();
            $('#current-datetime').text(now.toLocaleString());
        }
        
        function renderStaffNoteDisplay() {
            const note = POS_STATE.staffNote;
            $('#staff-note-area').val(note === "No current staff notes." ? '' : note);
        }

        // =====================================================================
        // CALCULATION LOGIC (ENHANCED)
        // =====================================================================

        function calculateTotals() { 
            const order = getActiveOrder();
            if (!order) return { subtotal: 0, tax: 0, finalTotal: 0, voucherDed: 0, pointsDed: 0, totalCommission: 0 };
            const customer = order.customer;
            
            let subtotal = 0;
            let totalCommission = 0;
            
            // 1. Calculate Subtotal and Commission
            order.cart.forEach(item => {
                const basePrice = customer.tier && item.product.memberPrice ? item.product.memberPrice : item.product.price;
                const lineTotal = (parseFloat(basePrice) * item.qty) - (item.lineDiscount || 0);
                subtotal += lineTotal;

                // Commission Logic
                const commVal = lineTotal * (item.product.commRate || 0);
                totalCommission += commVal;
            });

            // 2. Voucher
            let voucherDed = 0;
            if(order.voucher) {
                if(order.voucher.type === 'fixed') voucherDed = order.voucher.value;
                else voucherDed = subtotal * (order.voucher.value / 100);
            }

            // 3. Points (100 pts = $1)
            const pointsDed = order.pointsRedeemed / 100;

            const taxable = Math.max(0, subtotal - voucherDed - pointsDed);
            const tax = taxable * 0.06;
            const finalTotal = taxable + tax;
            
            return { 
                subtotal: subtotal,
                voucherDed: voucherDed,
                pointsDed: pointsDed,
                tax: tax, 
                finalTotal: finalTotal,
                totalCommission: totalCommission
            };
        }

        // =====================================================================
        // UI HELPERS
        // =====================================================================

        // FIX: Added boundary check to prevent modal from going off-screen
        function openItemRepModal(e, itemIdx) {
            e.stopPropagation();
            const $modal = $('#item-rep-modal');
            $modal.empty();
            
            // Add option to use Order Default
            $modal.append(`<div class="rep-option" onclick="setItemRep(${itemIdx}, null)">Use Order Default</div>`);
            
            // Add all staff
            STAFF_LIST.forEach(s => {
                $modal.append(`<div class="rep-option" onclick="setItemRep(${itemIdx}, ${s.id})">${s.name}</div>`);
            });

            // Boundary Logic
            const modalWidth = 220;
            const windowWidth = $(window).width();
            let leftPos = e.pageX;
            
            // If the modal would go off the right edge, shift it left
            if (leftPos + modalWidth > windowWidth) {
                leftPos = e.pageX - modalWidth; 
            }

            $modal.css({ top: e.pageY, left: leftPos, display: 'block' });
            
            // Close on click elsewhere
            $(document).one('click', () => $modal.hide());
        }

        function setItemRep(idx, repId) {
            const order = getActiveOrder();
            order.cart[idx].salesRepId = repId;
            renderCart();
            $('#item-rep-modal').hide();
        }


        // =====================================================================
        // PAYMENT & TRANSACTION LOGIC (MODIFIED)
        // =====================================================================
        
        function renderPaymentModal() { 
            const totals = calculateTotals();
            const paid = paymentBreakdown.reduce((sum, p) => sum + p.amount, 0);
            const remaining = totals.finalTotal - paid;
            const order = getActiveOrder();

            $('#modal-total-display').text(`$${totals.finalTotal.toFixed(2)}`);
            $('#modal-remaining-display').text(`$${Math.max(0, remaining).toFixed(2)}`);
            $('#payment-amount').val(Math.max(0, remaining).toFixed(2));
            $('#change-display').empty();

            // Points UI Update
            $('#modal-pts-avail').text(order.customer.points);
            $('#points-input').attr('max', order.customer.points);
            
            renderPaymentBreakdown();
            $('#payment-modal-overlay').css('display', 'flex');
        }

        function renderPaymentBreakdown() { 
            const $breakdown = $('#payment-breakdown');
            $breakdown.empty();
            
            paymentBreakdown.forEach(p => {
                $breakdown.append(`
                    <div style="display: flex; justify-content: space-between; font-size: 0.9rem; padding: 4px 0; border-bottom: 1px dotted #ccc;">
                        <span>${p.method.toUpperCase()} ${p.detail ? '('+p.detail+')' : ''}</span>
                        <span style="font-weight: bold;">$${p.amount.toFixed(2)}</span>
                    </div>
                `);
            });
            
            const paid = paymentBreakdown.reduce((sum, p) => sum + p.amount, 0);
            $breakdown.append(`<div style="font-size: 1.2rem; font-weight: bold; color: #00897B; margin-top: 1rem;">TOTAL TENDERED: $${paid.toFixed(2)}</div>`);
        }
        
        function recordPayment(amount, method, detail = '') { 
            const totals = calculateTotals();
            let paid = paymentBreakdown.reduce((sum, p) => sum + p.amount, 0);
            let remaining = totals.finalTotal - paid;
            let change = 0;

            if (method === 'cash' && amount > remaining) {
                change = amount - remaining;
                amount = remaining;
            } else if (method !== 'cash' && amount > remaining) {
                amount = remaining;
            }
            
            if (amount > 0) {
                 paymentBreakdown.push({ method, amount: parseFloat(amount.toFixed(2)), detail });
                 paid += amount;
                 remaining = totals.finalTotal - paid;
            }

            if (remaining <= 0) {
                 $('#change-display').html(`✅ Transaction Paid. **CHANGE DUE: $${Math.max(0, change).toFixed(2)}**`);
                 setTimeout(() => finalizeTransaction(change), 1500);
            } else {
                renderPaymentModal(); 
            }
        }
        
        function finalizeTransaction(changeDue) { 
             const totals = calculateTotals();
             const order = getActiveOrder();
             
             POS_STATE.completedOrders.push({
                 id: order.id, customerName: order.customer.name, total: totals.finalTotal, 
                 date: new Date().toLocaleString(), items: order.cart.length
             });

             alert(`Transaction Complete!\nChange: $${changeDue.toFixed(2)}`);
             
             POS_STATE.orders = POS_STATE.orders.filter(o => o.id.toString() !== POS_STATE.activeOrderId.toString());
             if (POS_STATE.orders.length === 0) createNewOrder();
             else POS_STATE.activeOrderId = POS_STATE.orders[0].id;
             
             paymentBreakdown = [];
             updateStateAndRender();
             $('#payment-modal-overlay').hide();
        }

        // =====================================================================
        // EVENT HANDLERS
        // =====================================================================
        
        $(document).ready(function() {
            init();

            function init() {
                if(POS_STATE.orders.length === 0) createNewOrder();
                else updateStateAndRender();
                updateClock();
                setInterval(updateClock, 1000); 
            }

            // --- STAFF / GLOBAL REP ---
            $(document).on('change', '#global-sales-rep', function() {
                const order = getActiveOrder();
                if(order) {
                    order.globalSalesRepId = parseInt($(this).val());
                    renderCart(); 
                }
            });
            
            // --- TOOLS MODAL HANDLERS ---
            $('#tools-btn').on('click', function() {
                $('#tools-modal-overlay').css('display', 'flex');
                renderStaffNoteDisplay();
            });
            $('#close-tools-btn').on('click', function() {
                $('#tools-modal-overlay').hide();
            });
            
            // Order Search Logic
            $('#execute-order-search').on('click', function() {
                const query = $('#order-search-input').val().trim().toLowerCase();
                const $results = $('#order-search-results');
                $results.empty();
                if (query.length < 2) return $results.html('<p style="color:red;">Enter 2+ chars.</p>');
                const foundOrders = POS_STATE.completedOrders.filter(order => 
                    order.id.toString().includes(query) || order.customerName.toLowerCase().includes(query) || order.date.includes(query)
                );
                if (foundOrders.length === 0) return $results.html('<p>No results.</p>');
                foundOrders.forEach(order => {
                    $results.append(`<div class="found-order-item"><span>#${order.id} - ${order.customerName}</span><b>$${order.total.toFixed(2)}</b></div>`);
                });
            });
            
            // Staff Note Logic
            $('#save-staff-note').on('click', function() {
                const note = $('#staff-note-area').val();
                POS_STATE.staffNote = note;
                localStorage.setItem('staffNote', note);
                renderStaffNoteDisplay();
                alert('Note Saved');
            });

            // --- TAB HANDLERS ---
            $(document).on('click', '.tab-btn:not(.tab-add-btn, #tools-btn, #show-suspended-btn)', function() {
                POS_STATE.activeOrderId = $(this).data('order-id').toString();
                paymentBreakdown = [];
                updateStateAndRender();
            });
            $('#new-order-btn').on('click', createNewOrder);

            // --- SUSPEND HANDLERS ---
            $(document).on('click', '#suspend-order-btn:not([disabled])', function() {
                const activeOrder = getActiveOrder();
                activeOrder.status = 'suspended';
                activeOrder.lastUpdated = new Date().toLocaleString();
                POS_STATE.suspendedOrders.push(activeOrder);
                POS_STATE.orders = POS_STATE.orders.filter(o => o.id.toString() !== activeOrder.id.toString());
                createNewOrder();
            });
            
            $(document).on('click', '#show-suspended-btn', function() {
                const $list = $('#suspended-list'); $list.empty();
                if (POS_STATE.suspendedOrders.length === 0) $list.html('<p>No suspended orders.</p>');
                else {
                    POS_STATE.suspendedOrders.forEach(order => {
                        $list.append(`<div style="display:flex; justify-content:space-between; padding:10px; border-bottom:1px solid #eee;">
                                <span>#${order.id} (${order.customer.name})</span>
                                <button class="retrieve-suspended-btn" data-order-id="${order.id}" style="background:#00897B; color:white; border:none; padding:5px;">Retrieve</button>
                            </div>`);
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
                    paymentBreakdown = [];
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
                
                if (existingItem) existingItem.qty += 1;
                else order.cart.push({ product, qty: 1, selectedBatch: 'N/A', lineDiscount: 0.00, salesRepId: null });
                
                const $el = $(this).closest('.product-card, .popular-btn');
                $el.addClass('clicked-success');
                setTimeout(() => { $el.removeClass('clicked-success'); }, 200);
                updateStateAndRender();
            });
            
            $(document).on('click', '.qty-btn', function() {
                const index = parseInt($(this).data('index'));
                const action = $(this).data('action');
                const order = getActiveOrder();
                if (action === 'plus') order.cart[index].qty += 1;
                else if (action === 'minus' && order.cart[index].qty > 1) order.cart[index].qty -= 1;
                updateStateAndRender();
            });

            $(document).on('click', '.delete-item-btn', function() {
                const index = parseInt($(this).data('index'));
                getActiveOrder().cart.splice(index, 1);
                updateStateAndRender();
            });

            $(document).on('click', '.category-btn', function() {
                POS_STATE.selectedCategory = $(this).data('category');
                $('#search-input').val(''); 
                renderProducts(); renderCategoryFilters();
            });
            
            $('#search-input').on('input', function() {
                POS_STATE.selectedCategory = 'All';
                renderProducts($(this).val()); renderCategoryFilters();
            });

            $(document).on('click', '#cancel-order-btn:not([disabled])', function() {
                if (confirm('VOID ORDER?')) {
                    const o = getActiveOrder();
                    o.cart = []; o.voucher = null; o.pointsRedeemed = 0;
                    updateStateAndRender();
                }
            });

            $(document).on('click', '#no-sale-btn', function() { alert("Drawer opened."); });

            $(document).on('click', '#lookup-member-btn', function() {
                const val = $('#member-phone-input').val().trim();
                const member = POS_STATE.customers.find(c => c.phone === val || c.id.toString() === val);
                const activeOrder = getActiveOrder();
                if (member) activeOrder.customer = member;
                else activeOrder.customer = POS_STATE.customers[0];
                updateStateAndRender();
            });

            // --- PAYMENT MODAL ---
            $(document).on('click', '#pay-button:not([disabled])', function() {
                paymentBreakdown = [];
                renderPaymentModal();
            });
            $(document).on('click', '#close-modal-btn', function() { $('#payment-modal-overlay').hide(); });

            // Payment Options Toggle
            $(document).on('click', '.payment-option', function() {
                const method = $(this).data('method');
                $('.payment-option').removeClass('selected');
                $(this).addClass('selected');
                $('.dynamic-input-area').removeClass('active');
                $('#payment-input-area').show();

                if(method === 'voucher') { $('#area-voucher').addClass('active'); $('#payment-input-area').hide(); }
                else if(method === 'points') { $('#area-points').addClass('active'); $('#payment-input-area').hide(); }
                else if(method === 'po') { $('#area-po').addClass('active'); $('#payment-input-area').hide(); }
                else if(method === 'custom') { $('#area-custom').addClass('active'); }
                
                const remaining = parseFloat($('#modal-remaining-display').text().replace('$',''));
                $('#payment-amount').val(remaining.toFixed(2));
            });

            // Advanced Payment Actions
            $('#apply-voucher-btn').click(function() {
                const code = $('#voucher-code-input').val().trim();
                if(VOUCHERS[code]) {
                    getActiveOrder().voucher = { code: code, ...VOUCHERS[code] };
                    $('#voucher-msg').text("Applied!").css('color', 'green');
                    renderPaymentModal();
                } else $('#voucher-msg').text("Invalid").css('color', 'red');
            });

            $('#points-input').on('input', function() {
                const pts = parseInt($(this).val()) || 0;
                $('#points-conversion-preview').text((pts/100).toFixed(2));
            });

            $('#apply-points-btn').click(function() {
                const pts = parseInt($('#points-input').val()) || 0;
                const order = getActiveOrder();
                if(pts > order.customer.points) return alert("Not enough points");
                order.pointsRedeemed = pts;
                alert("Points Applied");
                renderPaymentModal();
            });

            $('#verify-po-btn').click(function() {
                if($('#po-ref-input').val() === 'CORP-456') {
                    $('#po-balance-display').html('<b style="color:green">Verified: ACME Corp.</b><button id="po-pay-confirm" style="width:100%; margin-top:5px; background:#48BB78; color:white; border:none; padding:10px;">Confirm PO Payment</button>');
                } else $('#po-balance-display').html('<b style="color:red">Invalid</b>');
            });

            $(document).on('click', '#po-pay-confirm', function() {
                const remaining = parseFloat($('#modal-remaining-display').text().replace('$',''));
                recordPayment(remaining, 'PO', 'CORP-456');
            });

            $(document).on('click', '#add-payment-btn', function() {
                const amt = parseFloat($('#payment-amount').val());
                const method = $('.payment-option.selected').data('method');
                let detail = '';
                if (!method) return alert('Select method');
                if (method === 'custom') detail = $('#custom-method-select').val();
                
                recordPayment(amt, method, detail);
            });

        });
    </script>
</body>
</html>