<script>
$(document).ready(function() {
    initAllSalesExecutiveList();

});


function initAllSalesExecutiveList() {

    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken
    };

    const url = "controller/salesExecutive/getAllSalesExecutiveList";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;
        const listData = data.data;

        if (listData.length > 0) {


            $.each(listData, function(index) {
                renderList(listData[index], index);
            });
        }

        initNewDatatable();


    });
}

function renderList(data, index) {

    const indexNo = index + 1;
    const name = data.name.replace(/\b\w/g, l => l.toUpperCase());
    const executiveDbCode = data.executive_db_code;
    const salesCode = data.sales_code;
    const phone = data.phone;
    const wallet = data.wallet;
    const state = data.state;
    const remark = data.remark;
    const rating = data.rating;
    const status = data.status;

    var statusStr = "";
    var statusClass = "";

    if (status == 'available') {
        statusStr = 'Available';
        statusClass = "success";
    } else {
        statusStr = 'Unavailable';
        statusClass = "danger";
    }

    var actionBtnStr = "";
    if(!isCurrentEditBtnOff){
        actionBtnStr = `  <a href="account_sales_executive_edit?code=${executiveDbCode}" class="px-3 text-primary"><i
                                            class="uil uil-pen font-size-18"></i></a>`;
    }


    const contentStr = ` <tr id="tr-${executiveDbCode}">
                                <td>${indexNo}</td>
                                <td>${name}</td>
                                <td>${salesCode}</td>     
                                <td>${phone}</td>
                                <td>${state}</td>
                                <td>${rating}</td>
                                <td>${remark}</td>
                                <td><div
                                        class="badge bg-pill bg-${statusClass}-subtle text-${statusClass} font-size-12">${statusStr}</div></td>
                                <td>
                                ${actionBtnStr}
                                </td>
                            </tr>`;

    $('#pageTable1Body').append(contentStr);
}

function deleteResellerUser(code) {
    Swal.fire({
        title: "Account Deletion",
        text: "Are you sure want to delete this reseller?",
        showCancelButton: true,
        icon: 'info',
        confirmButtonText: "Okay, continue",
    }).then((result) => {

        if(!result.isConfirmed){
            return;
        }

        const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
        const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

        const params = {
            adminUsername: adminUsername,
            adminAccesstoken: adminAccesstoken,
            code: code
        };

        const url = "controller/reseller/deleteReseller";
        sendPostRequestWithCallback(params, url).then(function(data) {

            const status = data.status;
            const message = data.message;

            if (status == 200) {

                var table1 = $('#pageTable1').DataTable();
                table1.destroy();

                $(`#tr-${code}`).remove();
                initNewDatatable();
                
                showDialogMessage("Account Deletion", message, "success", false);
            }else{
                showDialogMessage("Account Deletion", message, "error", false);
            }

        });
    });

}

function accountStatusFilterChange(){
    const status = $('#accountStatusFilter').val();
    $('#pageTable1_filter input').val(status).change();
    $('#pageTable1').DataTable().search(status).draw();
}
</script>