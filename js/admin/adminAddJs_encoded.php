<script>
$(document).ready(function() {
    $('.needs-validation').parsley();

    // ONLY ALLOW NUMBER ON INPUT
    $('#userPhone').on('change', function(e) {
        $(e.target).val($(e.target).val().replace(/[^\d\.]/g, ''))
    })
    $('#userPhone').on('keypress', function(e) {
        keys = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9']
        return keys.indexOf(event.key) > -1
    })
    // ONLY ALLOW NUMBER ON INPUT


});


function addAdminFunc(event) {
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
        adminUsername: systemUsername,
        adminAccesstoken: systemAccesstoken,
        userName: userName,
        userUsername: userUsername,
        userPassword: userPassword,
        modules: moduleDict
    };

    const url = "controller/admin/addAdmin";
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