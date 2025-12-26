<script>
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

var code = "";

if (urlParams.get('code')) {
    code = urlParams.get('code');
}

$(document).ready(function() {
    $('.needs-validation').parsley();

    initAdminInfo();


});

function initAdminInfo() {
    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken,
        code: code
    };

    const url = "controller/admin/loadAdminInfo";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;

        if (status == 200) {
            const userData = data.data;
            updateAdminData(userData);
        }

    });

}

function updateAdminData(data) {
    const name = data.name;
    const username = data.username;
    const modules = data.modules;
    const status = data.status;

    $('#adminName').val(name);
    $('#adminUsername').val(username);

    status == 'unblocked' ? $('#adminStatus').prop('checked', true) : $('#adminStatus').prop('checked', false);


    for (var key in modules) {

        const flagName = key;
        const value = modules[key];
        value == true ? $(`#${key}`).prop('checked', true) : $(`#${key}`).prop('checked', false);
    }
}


function editAdminFunc(event) {
    // Get the form element
    const form = document.querySelector('.needs-validation');

    // Check if form is valid
    if (!form.checkValidity()) {
        event.preventDefault(); // Prevent submission only if validation fails
        event.stopPropagation(); // Stop the event from bubbling up
        form.classList.add('was-validated'); // Add Bootstrap validation styling
        return;
    }

    // Prevent default form submission after validation success
    event.preventDefault();

    const userName = $('#adminName').val().replace(/\b\w/g, l => l.toUpperCase());
    const userUsername = $('#adminUsername').val();
    const userPassword = $('#adminPassword').val();
    const accountStatus = $('#adminStatus').prop('checked');

    const systemUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const systemAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);


    const modulesList = ['order', 'event', 'report', 'warranty', 'device', 'product', 'market-material',
        'account-management', 'tools', 'setting', 'barcode'
    ];

    var moduleDict = {};
    moduleDict['dashboard-view'] = $('#dashboard-view').prop('checked');

    for (i = 0; i < modulesList.length; i++) {

        const flagName = modulesList[i];
        const viewFlag = $(`#${flagName}-view`).prop('checked');
        const editFlag = $(`#${flagName}-edit`).prop('checked');

        moduleDict[`${flagName}-view`] = viewFlag;
        moduleDict[`${flagName}-edit`] = editFlag;
    }

    moduleDict = JSON.stringify(moduleDict);

    const params = {
        code: code,
        adminUsername: systemUsername,
        adminAccesstoken: systemAccesstoken,
        userName: userName,
        userUsername: userUsername,
        userPassword: userPassword,
        modules: moduleDict,
        accountStatus: accountStatus
    };

    const url = "controller/admin/updateAdmin";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;

        if (status == 200) {

            Swal.fire({
                title: "Admin Account",
                text: message,
                showCancelButton: false,
                icon: 'success',
                confirmButtonText: "Okay, continue",
            }).then((result) => {
                redirectPageWithTimer(0, 'account_admin');
            });


        } else {
            showDialogMessage("Admin Account", message, "error", true);
        }

    });
}
</script>