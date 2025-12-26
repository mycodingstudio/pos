<script>
$(document).ready(function() {

    $('.needs-validation').parsley();

    initProfileInfo();

});

function initProfileInfo() {
    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken
    };

    const url = "controller/admin/initProfileInfo";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;

        if (status == 200) {
            const userData = data.data;
            updateData(userData);
        }

    });

}

function updateData(data) {
    const username = data.username;
    $('#profileUsername').text(username);
    $('#salesCode').text(data.sales_code);
    $('#name').val(data.name);
    $('#phone').val(data.phone);
    $('#email').val(data.email == null ? "" : data.email);
}


function editProfileFunc(event) {
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

    const profileCurrentPassword = $('#profileCurrentPassword').val();
    const profileNewPassword = $('#profileNewPassword').val();
    const profileConfirmPassword = $('#profileConfirmPassword').val();

    if (profileNewPassword != profileConfirmPassword) {
        $('#profileConfirmPasswordError').show();
        return;
    }
    $('#profileConfirmPasswordError').hide();

    const systemUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const systemAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const params = {
        adminUsername: systemUsername,
        adminAccesstoken: systemAccesstoken,
        currentPassword: profileCurrentPassword,
        newPassword: profileNewPassword,
        name: $('#name').val(),
        phone: $('#phone').val(),
        email: $('#email').val()
    };

    const url = "controller/admin/updateProfile";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;

        if (status == 200) {

            Swal.fire({
                title: "Update Profile",
                text: message,
                showCancelButton: false,
                icon: 'success',
                confirmButtonText: "Okay, continue",
            }).then((result) => {
                clearAllLocal();
                redirectPageWithTimer(0, 'index');
            });


        } else {
            showDialogMessage("Update Profile", message, "error", true);
        }

    });
}
</script>