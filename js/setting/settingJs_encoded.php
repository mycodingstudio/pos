<script>
$(document).ready(function() {
    initSettingInfo();
});

function initSettingInfo(){
    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken,
    };

    const url = "controller/setting/loadSettingInfo";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;

        if (status == 200) {
            const settingInfo = data.data;

            $.each(settingInfo, function(index) {
                updateSettingData(settingInfo[index], index);
            });
        }

    });
}

function updateSettingData(data, index){
    if(data.name == 'SUPPORT_EMAIL'){
        const value = data.value;
        $('#supportEmail').val(value);
    }
}

function updateSettingFunc(event){
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

    const supportEmail = $('#supportEmail').val();
    
    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken,
        email: supportEmail,

    };

    const url = "controller/setting/updateEmailSetting";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;

        if (status == 200) {

            Swal.fire({
                title: "Update Support Email",
                text: message,
                showCancelButton: false,
                icon: 'success',
                confirmButtonText: "Okay, continue",
            }).then((result) => {
                refreshPage();
            });


        } else {
            showDialogMessage("Update Support Email", message, "error", true);
        }

    });

}

</script>