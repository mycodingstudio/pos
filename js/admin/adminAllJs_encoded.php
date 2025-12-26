<script>
$(document).ready(function() {
    initAllAdminList();

});


function initAllAdminList() {

    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken
    };

    const url = "controller/admin/getAllAdminList";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;
        const adminListData = data.data;

        if (adminListData.length > 0) {

            $.each(adminListData, function(index) {
                renderAdminList(adminListData[index], index);
            });
        }

        initNewDatatable();


    });
}

function renderAdminList(data, index) {
    const indexNo = index + 1;
    const name = data.name.replace(/\b\w/g, l => l.toUpperCase());;
    const username = data.username;
    const createdAt = data.created_timestamp;
    const lastLogin = data.last_login;
    const status = data.status;
    const accDbCode = data.admin_db_code;

    var statusStr = "";
    var statusClass = "";

    if (status == 'unblocked') {
        statusStr = 'Actived';
        statusClass = "success";
    } else {
        statusStr = 'Blocked';
        statusClass = "danger";
    }

    var actionBtnStr = "";
    if(!isCurrentEditBtnOff){
        actionBtnStr = ` <a href="account_admin_edit?code=${accDbCode}" class="px-3 text-primary"><i
                                        class="uil uil-pen font-size-18"></i></a>
                                <button onclick="deleteAdminUser('${accDbCode}', '${name}')" class="btn btn-theme px-3 text-danger deleteBtn"><i
                                        class="uil uil-trash-alt font-size-18"></i></button>`;
    }

    const contentStr = `<tr id="tr-${accDbCode}">
                            <td>${indexNo}</td>
                            <td>${name}</td>
                            <td>${username}</td>
                            <td>${createdAt}</td>
                            <td>${lastLogin}</td>
                            <td>
                                    <div
                                        class="badge bg-pill bg-${statusClass}-subtle text-${statusClass} font-size-12">${statusStr}</div>
                                </td>
                            <td>
                                ${actionBtnStr}
                            </td>
                        </tr>`;

    $('#pageTable1Body').append(contentStr);
}

function deleteAdminUser(code, adminName) {
    Swal.fire({
        title: "Account Deletion",
        text: "Are you sure want to delete this admin?",
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
            code: code,
            adminName: adminName
        };

        const url = "controller/admin/deleteAdmin";
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