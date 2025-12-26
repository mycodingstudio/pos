<script>
$(document).ready(function() {
    
});

function addNewActivity() {

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
    const boardDescription = $('#boardDescription').val();

    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken,
        boardDescription: boardDescription
    };

    const url = "controller/tools/insertNewActivities";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;

        if (status == 200) {

            Swal.fire({
                title: "Added New Activity",
                text: message,
                showCancelButton: false,
                icon: 'success',
                confirmButtonText: "Okay, continue",
            }).then((result) => {
                refreshPage();
            });

        } else {
            showDialogMessage("Added New Activity", message, "error", true);
        }

    });

}

</script>