<script>
var deviceCount = 0;
var totalAmount = 0.00;
var orderId = "";
var priceList;
var isStaffPrice = false;

const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

var serialNumberList = [];
var isUpdatingData = false;
var byCash = false;
var skipPaymentProof = false;

var code = "";

if (urlParams.get('code')) {
    code = urlParams.get('code');
}

$(document).ready(function() {
    initAllBasicInfo();

    // New event listener for the Order Status dropdown
    $('#orderStatus').on('change', function() {
        const selectedStatus = $(this).val();

        if (!byCash && !skipPaymentProof) {
            if (selectedStatus === 'order_confirmed' || selectedStatus === 'shipped' ||
                selectedStatus ===
                'completed') {

                if (!isStaffPrice) {
                    $('#paymentProofContainer').show();
                }

            } else {
                $('#paymentProofContainer').hide();
            }
        }

    });


});

function initAllBasicInfo() {
    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken
    };

    const url = "controller/order/getBasicInfo";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;

        if (status == 200) {
            priceList = data.data.price_list;
            priceList['Wall-Mount-Bracket'] = 35;
            priceList['iBar-2'] = 299;
            priceList['iBar-3'] = 358;
            resellerList = data.data.reseller_list;

            $.each(resellerList, function(index) {
                const resellerDbCode = resellerList[index].reseller_db_code;
                const name = resellerList[index].name;
                const resellerContent = `<option value="${resellerDbCode}">${name}</option>`;
                $('#resellerName').append(resellerContent);
            });

        }

        initOrderInfo();

    });
}

function initOrderInfo() {

    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken,
        code: code
    };

    const url = "controller/order/loadOrderInfo";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;

        if (status == 200) {
            const orderInfo = data.data;
            updateOrderInfo(orderInfo);
        }

    });
}

function updateOrderInfo(data) {

    isUpdatingData = true;

    var resellerName = data.reseller_db_code;
    const orderDetails = data.order_details;
    const orderLog = data.order_log;
    const serialNumberList = data.serial_number_list;
    const orderStatus = data.status;
    byCash = data.by_cash;

    const totalAmount = formatCurrency(data.total_amount);
    orderId = data.id;

    const clientEmail = $('#clientEmail').val(data.client_email);
    const clientName = $('#clientName').val(data.client_name);
    const clientPhone = $('#clientPhone').val(data.client_phone);

    if (resellerName != 'Select' && resellerName != '') {
        resellerName = $('#resellerName').val(resellerName);
    }

    $("#fromPlatform").select2("destroy");
    $("#orderStatus").select2("destroy");

    $('#orderStatus').val(orderStatus);
    $('#orderRemark').val(data.remark);
    $('#trackingLink').val(data.tracking_link);
    $('#billingFullAddress').val(data.billing_address);
    $('#shippingFullAddress').val(data.shipping_address);
    $('#totalAmount').text(totalAmount);
    $('#fromPlatform').val(data.order_from);

    if (data.order_from == "B2C") {
        skipPaymentProof = true;
    }


    // If a payment proof link exists, we might want to display it
    const paymentProofLink = data.payment_proof;
    if (paymentProofLink) {
        // You can create an element to show the existing proof
        const proofHtml = `   <div class="mb-3">
                                                    <label class="form-label" class="control-label">
                                                        Uploaded</label>
                                                                                            <div class="clearfix"></div>
                                                    <a href="${paymentProofLink}" target="_blank" class="btn btn-success waves-effect waves-ligh">View Payment Proof</a>
                                                </div>
        
        `;
        $('#paymentProofContainer').html(proofHtml);
    } else {
        // Trigger change to show/hide the input field as per the status
        $('#orderStatus').trigger('change');
    }



    var updateCommandList = [];

    $.each(orderDetails, function(index) {
        renderNewDeviceRow();

        $(`#deviceColor${index+1}`).select2("destroy");
        $(`#deviceType${index+1}`).select2("destroy");

        const quantity = parseInt(orderDetails[index].quantity);
        const deviceType = orderDetails[index].device_type;
        var deviceColor = orderDetails[index].device_color;
        // const devicePrice = orderDetails[index].device_price;
        const devicePrice = priceList[deviceType];
        const deviceRemark = orderDetails[index].device_remark;

        if (parseFloat(devicePrice) == 250) {
            isStaffPrice = true;
        }

        if (deviceColor == "Milky White") {
            deviceColor = "White";
        } else if (deviceColor == "Baby Pink") {
            deviceColor = "Pink";
        } else if (deviceColor == "Dark Grey") {
            deviceColor = "Grey";
        }



        $(`#deviceQuantity${index+1}`).val(quantity);
        $(`#devicePrice${index+1}`).val(devicePrice);
        $(`#deviceType${index+1}`).val(deviceType);
        $(`#deviceColor${index+1}`).val(deviceColor);
        $(`#deviceRemark${index+1}`).val(deviceRemark);

        $(`#deviceColor${index+1}`).select2();
        $(`#deviceType${index+1}`).select2();

        if (deviceType == "Installation") {
            $(`#deviceColor${index+1}Div`).hide();
        } else if (deviceType == "Wall-Mount-Bracket") {
            $(`#deviceColor${index+1}Div`).hide();
        } else if (deviceType == "iBar-2" || deviceType == "iBar-3" || deviceType == "iBar-4" || deviceType ==
            "iBar-5") {
            $(`#deviceColor${index+1}`).select2("destroy");
            $(`#deviceColor${index+1}`).val('Black').attr('disabled', true);
            $(`#deviceColor${index+1}Div`).hide();
            $(`#deviceColor${index+1}`).select2();
        } else {
            $(`#deviceColor${index+1}Div`).show();
        }



        var serialNumberStr = "";
        for (i = 1; i <= quantity; i++) {

            const getUploadedSerialNumber = getAndRemoveSerialNumber(serialNumberList, deviceType);
            console.log(`getUploadedSerialNumber: ${getUploadedSerialNumber}`);

            serialNumberStr = serialNumberStr + ` <div class="serialDivFlex">
                    <label class="form-label serialDivFlexLabel">${i}</label>

                    <div class="serialInputFlex">
                        <input allSerialInput id="serial-number-${deviceType}-${index}-${i}" required type="text" 
                            class="form-control" placeholder="xxxx-xxxxx-xxxxx" onchange="updateSerialNumberInput('${orderId}', '${code}', '${deviceType}', this);" value>
                        
                        <div class="invalid-feedback">
                            Please fill in serial number.
                        </div>
                    </div>
                </div>`;

            if (getUploadedSerialNumber != null && getUploadedSerialNumber != "") {
                const tempObj2 = {
                    'ele_id': `serial-number-${deviceType}-${index}-${i}`,
                    'value': getUploadedSerialNumber
                }
                updateCommandList.push(tempObj2);
            }


        }

        const serialContentStr = `<div class="col-md-4">

                                        <label class="form-label" class="control-label">Device
                                            Type: ${deviceType}</label>

                                        <div class="card pt-2">${serialNumberStr}</div>

                                    </div>`;

        $('#orderSerialNumberRow').append(serialContentStr);
    });

    $.each(orderLog, function(index) {
        const content = orderLog[index].content;
        const updatedBy = orderLog[index].updated_by;
        const createdDatetime = orderLog[index].created_datetime;

        const contentStr = `<div class="col-lg-12">
                                <div class="form-control mb-2 theme-bottom-line">
                                    <p style="margin-bottom: 0px;"><strong>Date Time:
                                        </strong> ${createdDatetime}</p>
                                    <p style="margin-bottom: 0px;"><strong>Content:
                                        </strong> ${content}</p>
                                    <p style="margin-bottom: 0px;"><strong>Updated By:
                                        </strong> ${updatedBy}</p>
                                </div>
                            </div>`;

        $('#orderHistoryRow').append(contentStr);

    });

    $("#fromPlatform").select2();

    for (i = 0; i < updateCommandList.length; i++) {
        const eleId = updateCommandList[i].ele_id;
        const value = updateCommandList[i].value;

        $(`#${eleId}`).val(value).change().prop('disabled', true);
    }

    isUpdatingData = false;

    $("#orderStatus").select2();
    recalculatePrice();

    $("#orderStatus").val('processing').prop('disabled', false);

    $('#orderStatus').trigger('change');

    if (isStaffPrice || byCash || skipPaymentProof) {
        $('#paymentProofContainer').hide();
    }


    $('#byCash').prop('checked', byCash);

}

function getAndRemoveSerialNumber(serialNumberList, deviceType) {
    // Find the index of the first matching device type
    const index = serialNumberList.findIndex(item => item.device_type === deviceType);

    if (index !== -1) {
        // Extract the serial number before removing it
        const serialNumber = serialNumberList[index].serial_number;

        // Remove the item from the array
        serialNumberList.splice(index, 1);

        return serialNumber;
    }

    return null; // Return null if no match is found
}

function updateSerialNumberInput(orderId, orderDbCode, deviceType, input) {

    const inputValue = input.value;

    // CHECK IF SERIAL NUMBER IS VALID

    if (!isUpdatingData) {
        const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
        const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

        const params = {
            adminUsername: adminUsername,
            adminAccesstoken: adminAccesstoken,
            serialNumber: inputValue
        };

        const url = "controller/device/checkSerialNumberForOrder";
        sendPostRequestWithCallback(params, url).then(function(data) {

            const status = data.status;
            const message = data.message;

            if (status == 200) {

                const tempObj = {
                    "order_id": orderId,
                    "order_db_code": orderDbCode,
                    "device_type": deviceType,
                    "serial_number": inputValue,
                };

                serialNumberList.push(tempObj);
                serialNumberList = removeDuplicateSerialNumbers(serialNumberList);

            } else {

                const tempObj = {
                    "order_id": orderId,
                    "order_db_code": orderDbCode,
                    "device_type": deviceType,
                    "serial_number": inputValue,
                };

                serialNumberList.push(tempObj);
                serialNumberList = removeDuplicateSerialNumbers(serialNumberList);


                // showDialogMessage("Serial Number", message, "error", true);
                // input.value = '';

            }

        });
    } else {
        const tempObj = {
            "order_id": orderId,
            "order_db_code": orderDbCode,
            "device_type": deviceType,
            "serial_number": inputValue,
        };

        serialNumberList.push(tempObj);
        serialNumberList = removeDuplicateSerialNumbers(serialNumberList);
    }
}

function saveSerialNumberToOrder(event) {
    // Get the form element
    const form = document.querySelector('.needs-validation-serial');

    // Check if form is valid
    if (!form.checkValidity()) {
        event.preventDefault(); // Prevent submission only if validation fails
        event.stopPropagation(); // Stop the event from bubbling up
        form.classList.add('was-validated'); // Add Bootstrap validation styling
        return;
    }

    // Prevent default form submission after validation success
    event.preventDefault();

    const serialNumberListStr = JSON.stringify(serialNumberList);
    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken,
        serialNumberList: serialNumberListStr
    };

    const url = "controller/order/updateSerialNumber";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;

        if (status == 200) {
            showDialogMessage("Update Serial Number", message, "success", true);
        } else {
            showDialogMessage("Update Serial Number", message, "error", true);
        }

    });

}


function removeDuplicateSerialNumbers(data) {
    let seenSerials = new Set();

    let filteredData = data.filter(item => {
        if (!seenSerials.has(item.serial_number)) {
            seenSerials.add(item.serial_number);
            return true;
        }
        return false;
    });

    return filteredData;
}

function reinitRules() {
    // $('.needs-validation').parsley();
    $('.devicePrice').on('change', function(e) {
        $(e.target).val($(e.target).val().replace(/[^\d\.]/g, ''))
    })
    $('.devicePrice').on('keypress', function(e) {
        keys = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.']
        return keys.indexOf(event.key) > -1
    })
    // ONLY ALLOW NUMBER ON INPUT

}

function addNewDeviceFunc() {
    renderNewDeviceRow();
}

// function updateOrderFunc(event) {

//     var getOrderDetail = reinitOrderList();
//     getOrderDetail = JSON.stringify(getOrderDetail);

//     // Get the form element
//     const form = document.querySelector('.needs-validation');

//     // Check if form is valid
//     if (!form.checkValidity()) {
//         event.preventDefault(); // Prevent submission only if validation fails
//         event.stopPropagation(); // Stop the event from bubbling up
//         form.classList.add('was-validated'); // Add Bootstrap validation styling
//         return;
//     }

//     // Prevent default form submission after validation success
//     event.preventDefault();
//     const clientEmail = $('#clientEmail').val();
//     const clientName = $('#clientName').val();
//     const clientPhone = $('#clientPhone').val();
//     const resellerName = $('#resellerName').val();
//     const fromPlatform = $('#fromPlatform').val();
//     const orderRemark = $('#orderRemark').val();
//     const trackingLink = $('#trackingLink').val();
//     const billingFullAddress = $('#billingFullAddress').val();
//     const shippingFullAddress = $('#shippingFullAddress').val();
//     const orderStatus = $('#orderStatus').val();

//     // NEW VALIDATION FOR PAYMENT PROOF
//     var paymentProofFile;
//     var hasNewFile = false;
//     if (!$('#paymentProofContainer').find('a').length && !isStaffPrice && !byCash && !skipPaymentProof) {
//         paymentProofFile = $('#paymentProof')[0].files[0];
//         const isNewProofRequired = (orderStatus === 'order_confirmed' || orderStatus === 'shipped' || orderStatus ===
//             'completed');
//         if (isNewProofRequired && !paymentProofFile && !$('#paymentProofContainer').find('a').length) {
//             event.preventDefault();
//             event.stopPropagation();
//             $('#paymentProof').addClass('is-invalid'); // Add Bootstrap's invalid class
//             showDialogMessage("Validation Error", "Please upload a payment proof for the selected order status.",
//                 "error",
//                 true);
//             return;
//         } else {
//             $('#paymentProof').removeClass('is-invalid'); // Remove the invalid class if valid
//         }
//     }
//     // END OF NEW VALIDATION

//     if (paymentProofFile === undefined) {
//         hasNewFile = false;
//     } else {
//         hasNewFile = true;
//     }

//     // IF UPDATE TO COMPLETE STATUS, ALL THE SERIAL NUMBER SHOULD BE FILLED IN FIRST
//     if (orderStatus == 'completed') {
//         var allowToUpdate = true;
//         var requireDatas = document.querySelectorAll('[allSerialInput]');

//         requireDatas.forEach(function(data) {
//             const detailVal = $(`#${data.id}`).val();

//             if (detailVal == "" || detailVal == "Choose") {
//                 allowToUpdate = false;
//             }
//         });

//         // if (!allowToUpdate) {
//         //     showDialogMessage("Update Error", "Please fill in all serial number before update to Completed status",
//         //         "error", true);
//         //     return;
//         // }
//     }


//     const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
//     const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);


//     // Use FormData for file uploads
//     const formData = new FormData();
//     formData.append('code', code);
//     formData.append('orderId', orderId);
//     formData.append('adminUsername', adminUsername);
//     formData.append('adminAccesstoken', adminAccesstoken);
//     formData.append('clientEmail', clientEmail);
//     formData.append('clientName', clientName);
//     formData.append('clientPhone', clientPhone);
//     formData.append('resellerName', resellerName);
//     formData.append('fromPlatform', fromPlatform);
//     formData.append('orderRemark', orderRemark);
//     formData.append('trackingLink', trackingLink);
//     formData.append('billingFullAddress', billingFullAddress);
//     formData.append('shippingFullAddress', shippingFullAddress);
//     formData.append('getOrderDetail', getOrderDetail);
//     formData.append('totalAmount', totalAmount);
//     formData.append('orderStatus', orderStatus);
//     formData.append('hasNewFile', hasNewFile);

//     if (paymentProofFile) {
//         formData.append('paymentProof', paymentProofFile);
//     }

//     const url = "controller/order/updateOrder";

//     // Use a new AJAX function for FormData
//     sendPostRequestWithFile(formData, url).then(function(data) {
//         loadingClose();
//         const status = data.status;
//         const message = data.message;

//         if (status == 200) {
//             Swal.fire({
//                 title: "Update Order",
//                 text: message,
//                 showCancelButton: false,
//                 icon: 'success',
//                 confirmButtonText: "Okay, continue",
//             }).then((result) => {
//                 redirectPageWithTimer(0, 'orders');
//             });
//         } else {
//             showDialogMessage("Update Order", message, "error", true);
//         }
//     });


// }

function submitOrderFunc(event) {
    console.log('submitOrderFunc');
    var getOrderDetail = reinitOrderList();
    getOrderDetail = JSON.stringify(getOrderDetail);

    // Get the form element
    const form = document.querySelector('.needs-validation');
    const orderStatus = $('#orderStatus').val();

    // Manually validate shipping address if checkbox is not checked
    if (!$('#shippingSameAsBilling').is(':checked') && !$('#shippingFullAddress').val()) {
        event.preventDefault();
        event.stopPropagation();
        $('#shippingFullAddress').closest('.mb-3').find('.invalid-feedback').show();
        form.classList.add('was-validated');
        return;
    } else {
        $('#shippingFullAddress').closest('.mb-3').find('.invalid-feedback').hide();
    }

    // Check if form is valid
    if (!form.checkValidity()) {
        event.preventDefault(); // Prevent submission only if validation fails
        event.stopPropagation(); // Stop the event from bubbling up
        form.classList.add('was-validated'); // Add Bootstrap validation styling
        return;
    }

    const byCash = $('#byCash').prop('checked');

    // NEW VALIDATION FOR PAYMENT PROOF
    const paymentProofFile = $('#paymentProof')[0].files[0];

    if (!byCash) {

        if ((orderStatus === 'order_confirmed' || orderStatus === 'shipped' || orderStatus === 'completed') && !
            paymentProofFile) {
            event.preventDefault();
            event.stopPropagation();
            $('#paymentProof').addClass('is-invalid'); // Add Bootstrap's invalid class
            showDialogMessage("Validation Error", "Please upload a payment proof for the selected order status.",
                "error",
                true);
            return;
        } else {
            $('#paymentProof').removeClass('is-invalid'); // Remove the invalid class if valid
        }
    }
    // END OF NEW VALIDATION

    // Prevent default form submission after validation success
    event.preventDefault();

    if (totalAmount == 0) {
        showDialogMessage("New Order", "Please choose a product", "error", true);
        return;
    }

    const clientEmail = $('#clientEmail').val();
    const clientName = $('#clientName').val();
    const clientPhone = $('#clientPhone').val();
    const resellerName = "";
    const seCode = readFromLocalDecrypted('salescode', SECRET_KEY);
    const fromPlatform = "Manual";
    const orderRemark = $('#orderRemark').val();
    const trackingLink = "";
    const billingFullAddress = $('#billingFullAddress').val();
    var shippingFullAddress = $('#shippingFullAddress').val();

    if ($('#shippingSameAsBilling').prop('checked')) {
        shippingFullAddress = billingFullAddress;
    }

    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const today = new Date();
    const year = today.getFullYear();
    const month = (today.getMonth() + 1).toString().padStart(2, '0');
    const day = today.getDate().toString().padStart(2, '0');

    const formattedDate = `${year}-${month}-${day}`;

    // Use FormData for file uploads
    const formData = new FormData();
    formData.append('adminUsername', adminUsername);
    formData.append('adminAccesstoken', adminAccesstoken);
    formData.append('clientEmail', clientEmail);
    formData.append('clientName', clientName);
    formData.append('clientPhone', clientPhone);
    formData.append('resellerName', resellerName);
    formData.append('seCode', seCode);
    formData.append('fromPlatform', fromPlatform);
    formData.append('orderRemark', orderRemark);
    formData.append('trackingLink', trackingLink);
    formData.append('billingFullAddress', billingFullAddress);
    formData.append('shippingFullAddress', shippingFullAddress);
    formData.append('getOrderDetail', getOrderDetail);
    formData.append('totalAmount', totalAmount);
    formData.append('orderDate', formattedDate);
    formData.append('orderStatus', orderStatus);
    formData.append('byCash', byCash ? 1 : 0);
    if (paymentProofFile) {
        formData.append('paymentProof', paymentProofFile);
    }

    const url = "controller/order/addOrder";

    // Use a new AJAX function for FormData
    sendPostRequestWithFile(formData, url).then(function(data) {
        loadingClose();
        const status = data.status;
        const message = data.message;

        if (status == 200) {

            Swal.fire({
                title: "New Order",
                text: message,
                showCancelButton: false,
                icon: 'success',
                confirmButtonText: "Okay, continue",
            }).then((result) => {
                redirectPageWithTimer(0, 'orders');
            });

        } else {
            showDialogMessage("New Order", message, "error", true);
        }

    });
}

// Add this new function to handle file uploads
function sendPostRequestWithFile(formData, url) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false, // Tell jQuery not to process the data
            contentType: false, // Tell jQuery not to set contentType
            dataType: 'json',
            success: function(response) {
                resolve(response);
            },
            error: function(xhr, status, error) {
                showDialogMessage("API Error",
                    "An error occurred while communicating with the server. Please try again.",
                    "error", true);
                reject(error);
            }
        });
    });
}


// function renderNewDeviceRow() {

//     deviceCount = deviceCount + 1;

//     const contentStr = `<div class="row border-bottom-theme pt-4" id="deviceRow${deviceCount}">

//                             <div class="col-md-2">
//                                 <div class="mb-3">
//                                     <label class="form-label" class="control-label">Device
//                                         Type</label>
//                                     <select disabled class="form-control select2 selectEle" id="deviceType${deviceCount}" onchange="deviceTypeChange('${deviceCount}')">
//                                         <option>Select</option>
//                                         <option value="iBar">iBar</option>
//                                         <option value="iBracket-Basic">iBracket Classic</option>
//                                         <option value="iBracket-Basic">iBracket Smart</option>
//                                         <option value="iBracket-Premium">iBracket Premium</option>
//                                         <option value="iConnector">iConnector</option>
//                                         <option value="iTube">iTube</option>
//                                     </select>
//                                 </div>
//                             </div>

//                             <div class="col-md-2">
//                                 <div class="mb-3">
//                                     <label class="form-label" class="control-label">Colors</label>
//                                     <select class="form-control select2 selectEle" id="deviceColor${deviceCount}">
//                                         <option>Select</option>
//                                         <option value="White">White</option>
//                                         <option value="Grey">Grey</option>
//                                         <option value="Pink">Pink</option>
//                                     </select>
//                                 </div>
//                             </div>

//                             <div class="col-md-2">
//                                 <div class="mb-3">
//                                     <label class="form-label" class="control-label">Quantity</label>
//                                     <input id="deviceQuantity${deviceCount}" name="deviceQuantity${deviceCount}" type="number" class="form-control"
//                                         placeholder="0" onchange="recalculatePrice()">
//                                 </div>
//                             </div>

//                             <div class="col-md-2">
//                                 <div class="mb-3">
//                                     <label class="form-label" class="control-label">Price Per
//                                         Unit</label>
//                                     <input id="devicePrice${deviceCount}" name="devicePrice${deviceCount}" type="text" inputmode="decimal" class="form-control devicePrice"
//                                         placeholder="0.00" onchange="recalculatePrice()">
//                                 </div>
//                             </div>

//                             <div class="col-md-2">
//                                 <div class="mb-3">
//                                     <label class="form-label" class="control-label">Remark</label>
//                                     <input id="deviceRemark${deviceCount}" name="deviceRemark${deviceCount}" type="text" class="form-control"
//                                         placeholder="Remark">
//                                 </div>
//                             </div>

//                         </div>`;

//     $('#orderDeviceRow').append(contentStr);

//     $(`#deviceType${deviceCount}, #deviceColor${deviceCount}`).select2();
//     reinitRules();
// }

function renderNewDeviceRow() {

    deviceCount = deviceCount + 1;

    let removeButtonHtml = "";

    removeButtonHtml = `<button type="button"
                            class="btn btn-success waves-effect waves-light mb-3"
                            onclick="deviceRemove(${deviceCount})">
                            <i class="mdi mdi-minus-circle-outline me-1"></i>
                            Remove
                        </button>`;

    const contentStr = `<div class="row border-bottom-theme pt-4" id="deviceRow${deviceCount}">

                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label" class="control-label">Product
                                        Type</label>
                                    <select class="form-control select2 selectEle" id="deviceType${deviceCount}" onchange="deviceTypeChange('${deviceCount}')">
                                        <option>Select</option>
                                        <option value="iBracket-Basic">iBracket Classic 1.0</option>
                                        <option value="iBracket-Basic-2">iBracket Classic 2.0</option>
                                        <option value="iBracket-Basic-3">iBracket Classic 3.0</option>
                                        <option value="iBracket-Smart">iBracket Smart</option>
                                        <option value="iBar-2">iBar 2</option>
                                        <option value="iBar-3">iBar 3</option>
                                        <option value="iBar-4">iBar 4</option>
                                        <option value="iBar-5">iBar 5</option>
                                        <option value="iTube">iTube</option>
                                        <option value="Wall-Mount-Bracket">Wall Mount Bracket</option>
                                        <option value="Power-Socket">Power Socket</option>
                                        <option value="Installation">Installation</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2" id="deviceColor${deviceCount}Div">
                                <div class="mb-3">
                                    <label class="form-label" class="control-label">Colors</label>
                                    <select class="form-control select2 selectEle" id="deviceColor${deviceCount}">
                                        <option>Select</option>
                                        <option value="White">White</option>
                                        <option value="Grey">Grey</option>
                                        <option value="Pink">Pink</option>
                                        <option value="Black">Black</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label" class="control-label">Quantity</label>
                                    <input id="deviceQuantity${deviceCount}" name="deviceQuantity${deviceCount}" type="number" class="form-control"
                                        placeholder="0" onchange="recalculatePrice()">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label" class="control-label">Price Per
                                        Unit</label>
                                    <input disabled id="devicePrice${deviceCount}" name="devicePrice${deviceCount}" type="text" inputmode="decimal" class="form-control devicePrice"
                                        placeholder="0.00" onchange="recalculatePrice()">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label" class="control-label">Remark</label>
                                    <input id="deviceRemark${deviceCount}" name="deviceRemark${deviceCount}" type="text" class="form-control"
                                        placeholder="Remark">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label" class="control-label">Action</label>
                                    <div>${removeButtonHtml}
                                    </div>

                                </div>
                            </div>

                        </div>`;

    $('#orderDeviceRow').append(contentStr);

    $(`#deviceType${deviceCount}, #deviceColor${deviceCount}`).select2();
    reinitRules();
}

function deviceRemove(index) {
    $(`#deviceRow${index}`).remove();
    recalculatePrice();
}

function deviceTypeChange(index) {
    const deviceType = $(`#deviceType${index}`).val();
    const getPrice = priceList[deviceType];
    const $deviceColor = $(`#deviceColor${index}`);
    const $deviceColorDiv = $(`#deviceColor${index}Div`);

    if (getPrice != undefined && getPrice != NaN) {
        $(`#devicePrice${index}`).val(getPrice);
    } else {
        $(`#devicePrice${index}`).val('');
    }

    // (Price disabling logic - seems fine)
    if (deviceType == "iBar-2" || deviceType == "iBar-3" || deviceType == "iBar-4" || deviceType == "iBar-5" ||
        deviceType == "Installation" || deviceType == "Wall-Mount-Bracket" || deviceType == "iBracket-Basic-2" ||
        deviceType == "iBracket-Basic-3") {
        $(`#devicePrice${index}`).attr('disabled', false);
    } else {
        // Add an else to ensure it gets disabled for other devices if needed
        $(`#devicePrice${index}`).attr('disabled', true);
    }

    // --- Color Field Handling ---

    if (deviceType == "iTube" || deviceType == "Installation" || deviceType == "Wall-Mount-Bracket" || deviceType == "Power-Socket" ||
        deviceType == "Other") {

        // These devices require the color field to be hidden, and don't need Select2 re-initialization
        if ($deviceColor.data('select2')) {
            $deviceColor.select2("destroy");
        }
        $deviceColorDiv.hide();

        if (deviceType == "Installation") {
            $(`#deviceQuantity${index}`).val('1');
        }

    } else if (deviceType.startsWith("iBar-")) {

        // iBar devices: Disable and set value, then hide
        if ($deviceColor.data('select2')) {
            $deviceColor.select2("destroy");
        }
        $deviceColor.val('Black').attr('disabled', true);
        $deviceColorDiv.hide(); // <-- Hide the parent div

        // Re-initialize Select2 only if it's currently visible (to avoid future issues)
        // Since we are hiding it right away, we can skip the re-initialization here 
        // and rely on the iBracket section to re-init when needed.

    } else if (deviceType.startsWith("iBracket-Basic") || deviceType == "iBracket-Smart") {

        // iBracket devices: Enable, clear value, and show
        if ($deviceColor.data('select2')) {
            $deviceColor.select2("destroy");
        }

        $deviceColor.val('').attr('disabled', false); // Clear value and enable
        $deviceColorDiv.show(); // <-- Show the parent div FIRST

        // Re-initialize Select2 AFTER showing the element to ensure correct rendering
        $deviceColor.select2();

    } else {
        // Default case: Show color field and ensure Select2 is initialized
        $deviceColorDiv.show();
        if (!$deviceColor.data('select2')) {
            $deviceColor.select2();
        }
    }
}


function recalculatePrice() {

    var tempTotal = 0.00;

    for (i = 1; i <= deviceCount; i++) {
        const quantity = $(`#deviceQuantity${i}`).val();
        const devicePrice = $(`#devicePrice${i}`).val();

        if (quantity != '' && devicePrice != '' && quantity != undefined && devicePrice != undefined) {
            const total = parseFloat(quantity) * parseFloat(devicePrice);
            tempTotal = tempTotal + total;
        }
    }

    if (tempTotal == NaN || tempTotal == "NaN") {
        $('#totalAmount').text('-.--');
        totalAmount = -1;
    } else {
        totalAmount = tempTotal;
        $('#totalAmount').text(formatCurrency(totalAmount));
    }

}

function reinitOrderList() {

    var orderList = [];


    for (i = 1; i <= deviceCount; i++) {

        var allApprove = true;

        const quantity = $(`#deviceQuantity${i}`).val();
        const devicePrice = $(`#devicePrice${i}`).val();

        const deviceType = $(`#deviceType${i}`).val();
        const deviceColor = $(`#deviceColor${i}`).val();
        const deviceRemark = $(`#deviceRemark${i}`).val();


        if (deviceType == '' || deviceType == 'Select') {
            showDialogMessage("Device Alert", "Please check all select option", "error", true);
            allApprove = false;
            continue;
        }

        if (quantity == '' && devicePrice == '' || quantity == undefined || devicePrice == undefined) {
            showDialogMessage("Device Alert", "Please check all select option", "error", true);
            allApprove = false;
            continue;
        }

        const tempOrder = {
            "device_type": deviceType,
            "device_color": deviceColor,
            "device_price": devicePrice,
            "quantity": quantity,
            "device_remark": deviceRemark
        };

        if (allApprove) {
            orderList.push(tempOrder)
        }

    }

    return orderList;


}
</script>