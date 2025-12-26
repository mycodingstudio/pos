<script>

$(document).ready(function() {
    initAllReviewList();
});

function initAllReviewList() {
    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);
    const salesCode = readFromLocalDecrypted('salescode', SECRET_KEY);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken,
        salesCode: salesCode
    };

    const url = "controller/review/getAllReviewList";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;
        const dataList = data.data;

        if (dataList.length > 0) {
            $.each(dataList, function(index) {
                renderList(dataList[index], index);
            });
        }

        initNewDatatable();

    });
}

function renderList(data, index) {

    const indexNo = index + 1;
    const rating = data.rating;
    const phone = data.phone;
    const comment = data.comment;
    const orderId = data.order_id;
    const createdDatetime = data.created_at;

    const contentStr = `     <tr>
                                <td>${indexNo}</td>
                                <td>${phone}</td>
                                <td>${rating}</td>
                                <td>${comment}</td>
                                <td>${orderId == null ? "-"  : orderId}</td>
                                <td>${createdDatetime}</td>
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

    $('#pageTable1_filter input').val(status).change();
    $('#pageTable1').DataTable().search(status).draw();
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