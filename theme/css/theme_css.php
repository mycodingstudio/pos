<style>
:root {
    --theme-bg-color: #f5f6f8;
    --theme-primary-color: #00897B;
    --theme-green-color: #00897B;
    --theme-red-color: #e74c3c;
    --theme-pink-color: #fd79a8;
    --theme-purple-color: #3F51BB;
    --theme-disabled-color: rgb(174, 151, 129);
    --bs-sidebar-menu-item-active-color: #000000;
    --bs-primary-rgb: 0, 0, 0;
    --bs-link-color-rgb: 0, 0, 0;
    --bs-danger-rgb: 231, 76, 60;
    --bs-pagination-active-bg: #000000 !important;
    --bs-pagination-active-border-color: #000000 !important;

}

.nav-tabs-custom .nav-item .nav-link.active {
    color: var(--theme-primary-color) !important;
}

.nav-tabs-custom .nav-item .nav-link::after {
    background: var(--theme-primary-color) !important;
}

.active>.page-link,
.page-link.active {
    border-color: var(--theme-primary-color) !important;
    background-color: var(--theme-primary-color) !important;
}

.nav-pills .nav-link.active,
.nav-pills .show>.nav-link {
    background-color: var(--theme-primary-color) !important;
}


.text-info {
    color: var(--theme-primary-color) !important;
}

.btn-outline-primary {
    background-color: var(--theme-primary-color) !important;
    color: white !important;
    border-color: var(--theme-primary-color) !important;
}

.bg-success {
    background-color: var(--theme-primary-color) !important;
}

.text-success {
    color: var(--theme-primary-color) !important;
}

.border-success,
.border-info,
.border-primary {
    --bs-border-opacity: 1;
    border-color: var(--theme-primary-color) !important;
    color: var(--theme-primary-color) !important;
}

.btn-outline-info {
    --bs-border-opacity: 1;
    border-color: var(--theme-primary-color) !important;
     color: var(--theme-primary-color) !important;
}

:root,
[data-bs-theme=light] {
    --bs-primary: #3F51BB;
}

.pagination {
    --bs-pagination-active-bg: #3F51BB !important;
    --bs-pagination-active-border-color: #3F51BB;

}

.activity-feed .feed-item:after {
    border: 2px solid var(--theme-primary-color);
}

.btn-info,
.btn-success {
    --bs-btn-color: #fff;
    --bs-btn-bg: var(--theme-primary-color);
    --bs-btn-border-color: var(--theme-primary-color);
    --bs-btn-hover-color: #fff;
    --bs-btn-hover-bg: var(--theme-primary-color);
    --bs-btn-hover-border-color: var(--theme-primary-color);
    --bs-btn-focus-shadow-rgb: 48, 99, 106;
    --bs-btn-active-color: #fff;
    --bs-btn-active-bg: var(--theme-primary-color);
    --bs-btn-active-border-color: var(--theme-primary-color);
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    --bs-btn-disabled-color: #fff;
    --bs-btn-disabled-bg: var(--theme-primary-color);
    --bs-btn-disabled-border-color: var(--theme-primary-color);
}

.fc-event,
.fc-event-dot {
    background-color: #000000;
}

body[data-sidebar-size=sm] .vertical-menu #sidebar-menu>ul>li:hover>a,
body[data-sidebar-size=sm] .vertical-menu #sidebar-menu>ul>li:hover>a i {
    color: #000000;
}

.page-link {
    color: #000000;
}

.active>.page-link,
.page-link.active {
    color: #ffffff !important;
}

.mm-active .active {
    font-weight: 600 !important;
}

.authentication-bg {
    background-color: var(--theme-bg-color);
}

.border-radius-20 {
    border-radius: 20px;
}

.btn-primary {
    --bs-btn-color: #fff;
    --bs-btn-bg: var(--theme-primary-color);
    --bs-btn-border-color: var(--theme-primary-color);
    --bs-btn-hover-color: #fff;
    --bs-btn-hover-bg: var(--theme-primary-color);
    --bs-btn-hover-border-color: var(--theme-primary-color);
    --bs-btn-focus-shadow-rgb: 116, 136, 235;
    --bs-btn-active-color: #fff;
    --bs-btn-active-bg: var(--theme-primary-color);
    --bs-btn-active-border-color: var(--theme-primary-color);
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    --bs-btn-disabled-color: #fff;
    --bs-btn-disabled-bg: var(--theme-disabled-color);
    --bs-btn-disabled-border-color: var(--theme-disabled-color);
}


.text-primary {
    --bs-text-opacity: 1;
    color: rgb(0 0 0) !important;
}

.form-check-input:checked {
    background-color: var(--theme-primary-color);
    border-color: var(--theme-primary-color);
}

.product-wishlist a {
    border: 1px solid #000000;
    color: #000000;
}

.bg-primary-subtle {
    background-color: var(--theme-primary-color) !important;
}

.social-icon-bg {
    background-color: var(--theme-primary-color);
}

.text-theme {
    color: var(--theme-primary-color);
}

.bg-primary,
.bg-info {
    background-color: var(--theme-primary-color) !important;
}

.bg-shipped-subtle {
    background-color: #d1d8ff !important;
}

.text-shipped {
    color: var(--theme-primary-color) !important;
}

.thicker-text {
    font-weight: 500;
}

.select2-container--default .select2-results__option[aria-selected=true]:hover {
    background-color: var(--theme-primary-color) !important;
}

.flatpickr-months .flatpickr-month {
    height: 35px;
}

.flatpickr-current-month .flatpickr-monthDropdown-months,
.flatpickr-current-month input.cur-year {
    font-size: 14px;
}

.flatpickr-current-month input.cur-year {
    margin-left: 10px;
}

.flatpickr-next-month svg,
.flatpickr-prev-month svg {
    top: -5px;
    position: relative;
}

.server-flex {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    padding-left: 3px;
    padding-right: 3px;
}

.server-flex h5 {
    margin-bottom: 0px;
    margin-left: 5px;
}

.server-flex i {
    margin-left: 10px;
}

.onlineStatus {
    width: 6px;
    height: 6px;
    background-color: var(--theme-green-color);
    border-radius: 99px;
}

.offlineStatus {
    width: 6px;
    height: 6px;
    background-color: var(--theme-red-color);
    border-radius: 99px;
}

.d-flex-2 {
    display: flex;
    align-items: center;
    justify-content: center;
}

.border-bottom-theme {
    border-bottom: var(--bs-border-width) dashed var(--theme-purple-color) !important;
}

.text-pink {
    color: var(--theme-pink-color)
}

.circle-social {
    height: 40px;
    width: 40px;
}

.product-social-link {
    display: flex;
    align-items: center;
    justify-content: center;
}

div:where(.swal2-container) button:where(.swal2-styled):where(.swal2-confirm) {
    border: 0;
    border-radius: .25em;
    background: initial;
    background-color: var(--theme-purple-color) !important;
    color: #fff;
    font-size: 1em;
}

div:where(.swal2-icon).swal2-info {
    border-color: var(--theme-purple-color) !important;
    color: var(--theme-purple-color) !important;
}

.btn-text {
    cursor: pointer;
}

.form-check-input:checked {
    background-color: var(--theme-green-color) !important;
    border-color: var(--theme-green-color) !important;
}

.form-control:disabled {
    background-color: #f5f6f8;
    opacity: 1;
}

.clickEleStyle {
    cursor: pointer;
}

.disabled-btn,
.disabled-btn:hover {
    background-color: #6d7178;
    border-color: #6d7178;
    opacity: 1;
    cursor: not-allowed;
}

#side-menu {
    display: none;
}

.w-50 {
    width: 50%;
}

.w-46 {
    width: 46%;
}

.dz-remove {
    display: inline-block !important;
    /* Always show the remove button */
    visibility: visible !important;
    /* Ensure it doesn't get hidden */
    opacity: 1 !important;
    /* Make it fully opaque */
    position: absolute;
    /* Adjust position if needed */
    top: -25px;
    /* Add some space for better UX */
}

.dropzone {
    padding-top: 40px;
}

.dropzone .dz-preview .dz-image img {
    display: block;
    object-fit: cover;
    width: 100%;
    object-position: top;
}

.theme-bottom-line {
    border: 1px solid var(--theme-purple-color);
}

.dropzone .dz-preview .dz-details .dz-size {
    display: none;
}

.dropzone .dz-preview .dz-image {
    width: 200px !important;
    ;
    height: 300px !important;
}

.deleteBtn {
    position: relative;
    top: -2px;
}

.form-control-sm {
    border: 1px solid red;
}

input,
select,
.form-control {
    border: 1px solid var(--theme-primary-color) !important;
}

.select2-container .select2-selection--single {
    border: 1px solid var(--theme-primary-color) !important;
}

hr {
    margin: 1rem 0;
    color: var(--theme-primary-color);
    border: 0;
    border-top: var(--bs-border-width) solid;
    opacity: .25;
}

.table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before,
table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before {
    line-height: 12px !important;
}

.main-content .content {
    margin-top: 0px !important;
}

.nav-link:hover {
    color: var(--theme-primary-color) !important;
}
</style>