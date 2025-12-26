<script>
var totalPendingCount = 0;
var totalProcessingCount = 0;
var totalShippedCount = 0;
var totalCompletedCount = 0;
var totalCancelledCount = 0;
var totalConfirmedOrderCount = 0;
var totalOnLoanCount = 0;

$(document).ready(function() {
    initAllOrderList();
});

function initAllOrderList() {
    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);
    const salesCode = readFromLocalDecrypted('salescode', SECRET_KEY);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken,
        salesCode: salesCode
    };

    const url = "controller/order/getAllOrderList";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;
        const orderList = data.data;

        if (orderList.length > 0) {
            $.each(orderList, function(index) {
                renderOrderList(orderList[index], index);
            });
        }

        initNewDatatableDesc();

        $('#totalPendingCount').text(formatNumber(totalPendingCount));
        $('#totalShippedCount').text(formatNumber(totalShippedCount));
        $('#totalCompletedCount').text(formatNumber(totalCompletedCount));
        $('#totalCancelledCount').text(formatNumber(totalCancelledCount));
        $('#totalProcessingCount').text(formatNumber(totalProcessingCount));
        $('#totalConfirmedOrderCount').text(formatNumber(totalConfirmedOrderCount));
        $('#totalOnLoanCount').text(formatNumber(totalOnLoanCount));
        
    });
}

function renderOrderList(data, index) {

    const indexNo = index + 1;
    const id = data.id;
    const orderDbCode = data.order_db_code;
    const createdDatetime = data.created_datetime;
    const clientEmail = data.client_email;
    const clientName = data.client_name.replace(/\b\w/g, l => l.toUpperCase());
    const clientPhone = data.client_phone;
    const totalAmount = formatCurrency(parseFloat(data.total_amount));

    const remark = data.remark;

    const status = data.status;
    const orderDetails = data.order_details;
    var statusStr = "";

    var orderListStr = "";

    const seCode = data.se_code;
    const seName = data.se_name;
    var seInfoStr = "";

    const byCash = data.by_cash;
    var byCashStr = "";

    if(byCash){
        byCashStr = `<div class="badge bg-pill bg-success-subtle text-success font-size-12">By Cash</div>`;
    }

    if(seCode != ""){
        seInfoStr = `<p class="mb-0">Name: <strong>${seName}</strong></p><p class="mb-0">Code: <strong>${seCode}</strong></p>`;
    }


    $.each(orderDetails, function(index) {
        const quantity = orderDetails[index].quantity;
        const deviceType = orderDetails[index].device_type;
        const deviceColor = orderDetails[index].device_color;

        if(orderListStr == ""){
            orderListStr = `${deviceType} (${deviceColor}) x ${quantity}`

        }else{
             orderListStr = orderListStr + `, ${deviceType} (${deviceColor}) x ${quantity}`
        }

        orderListStr = orderListStr.replaceAll('(Select)', "");
    });

    if(status == "on_loan"){
        totalOnLoanCount++;
        statusStr = `<div class="badge bg-pill bg-warning-subtle text-warning font-size-12">On Loan</div>`;
    }else if(status == "pending"){
        totalPendingCount++;
        statusStr = `<div class="badge bg-pill bg-warning-subtle text-warning font-size-12">Pending</div>`;
    }else if(status == "order_confirmed"){
        totalConfirmedOrderCount++;
        statusStr = `<div class="badge bg-pill bg-warning-subtle text-warning font-size-12">Order Confirmed</div>`;
    }else if(status == "shipped"){
        totalShippedCount++;
        statusStr = `<div class="badge bg-pill bg-shipped-subtle text-shipped font-size-12">Shipped</div>`;
    }else if(status == "processing"){
        totalProcessingCount++;
        statusStr = `<div class="badge bg-pill bg-dark-subtle text-dark font-size-12">Processing</div>
        <strong><p>All Day SDN BHD<br>BANK NAME : CIMB BANK BERHAD <br>BANK AC NO. 8011331480<br>Payment Mode: Bank Transfer</p></strong>
        
        `;
    }else if(status == "completed"){
        totalCompletedCount++;
        statusStr = `<div class="badge bg-pill bg-success-subtle text-success font-size-12">Completed</div>`;
    }else if(status == "cancelled"){
        totalCancelledCount++;
        statusStr = `<div class="badge bg-pill bg-danger-subtle text-danger font-size-12">Cancelled</div>`;
    }else{
        statusStr = `<div class="badge bg-pill bg-dark-subtle text-dark font-size-12">${status}</div>`;
    }


    var actionBtnStr = "";
    actionBtnStr = `<a href="order_edit?code=${orderDbCode}" class="mb-2 px-3 text-white  btn btn-success btn-sm  w-100"><i
                                        class="uil uil-pen font-size-14"></i>  Edit</a> <a href="order_reorder?code=${orderDbCode}" class="px-3 text-white  btn btn-success btn-sm w-100"><i
                                        class="uil uil-exchange-alt font-size-14"></i>  Reorder</a>
                    `;

    var proofLink = data.payment_proof;

    if(proofLink != "" && proofLink != null){
        actionBtnStr = actionBtnStr + `<a target="_blank" href="${proofLink}" class="px-3 text-white  btn btn-success btn-sm mt-1"><i
                                        class="uil uil-eye font-size-14"></i> Payment</a> `;
    }


    const contentStr = `     <tr id="tr-${orderDbCode}">
                                <td>#${id}</td>
                                <td style="max-width: 200px; white-space: normal; word-wrap: break-word;">${clientName} (${clientEmail})</td>
                                <td style="max-width: 300px; white-space: normal; word-wrap: break-word;">${orderListStr}<br><strong>Remark:</strong> ${remark}<p></p></td>
                                <td>RM${totalAmount}</td>
                                <td>${createdDatetime}</td>
                                <td>${clientPhone}</td>
                                <td>${byCashStr}<br>${statusStr}</td>
                                <td >${actionBtnStr}</td>
                            </tr>`;

    $('#pageTable1Body').append(contentStr);

}

function deleteOrder(code, clientEmail, id, totalAmount) {
    Swal.fire({
        title: "Order Deletion",
        text: "Are you sure want to delete this order?",
        showCancelButton: true,
        icon: 'info',
        confirmButtonText: "Okay, continue",
    }).then((result) => {

        if (!result.isConfirmed) {
            return;
        }

        const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
        const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

        const params = {
            adminUsername: adminUsername,
            adminAccesstoken: adminAccesstoken,
            code: code,
            clientEmail: clientEmail,
            orderId: id,
            totalAmount: totalAmount,
        };

        const url = "controller/order/deleteOrder";
        sendPostRequestWithCallback(params, url).then(function(data) {

            const status = data.status;
            const message = data.message;

            if (status == 200) {

                var table1 = $('#pageTable1').DataTable();
                table1.destroy();

                $(`#tr-${code}`).remove();
                initNewDatatable();
                
                showDialogMessage("Order Deletion", message, "success", false);
            } else {
                showDialogMessage("Order Deletion", message, "error", false);
            }

        });
    });
}

function accountStatusFilterUpdate(status){

    $('#order-selectinput').val(status).change();

    // No longer need to touch the global filter input
    // $('#pageTable1_filter input').val(status).change(); 

    // Get the DataTable instance and search only column 6 (Status)
    $('#pageTable1').DataTable().column(6).search(status).draw();
}

function accountStatusFilterChange(){
    var status = $('#order-selectinput').val();

    if(status == "All"){
        status = "";
    }

    $('#pageTable1_filter input').val(status).change();
    $('#pageTable1').DataTable().search(status).draw();
}
</script>