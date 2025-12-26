<script>
$(document).ready(function() {
    $('.needs-validation').parsley();

    // ONLY ALLOW NUMBER ON INPUT
    $('#phone').on('change', function(e) {
        $(e.target).val($(e.target).val().replace(/[^\d\.]/g, ''))
    })
    $('#phone').on('keypress', function(e) {
        keys = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9']
        return keys.indexOf(event.key) > -1
    })
    // ONLY ALLOW NUMBER ON INPUT


});

function addSalesExecutiveFunc(event) {
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

    const name = $('#name').val().replace(/\b\w/g, l => l.toUpperCase());
    const salesCode = $('#salesCode').val();
    const state = $('#state').val();
    const remark = $('#remark').val();
    const phone = $('#phone').val();

    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken,
        name: name,
        salesCode: salesCode,
        state: state,
        remark: remark,
        phone: phone
    };

    const url = "controller/salesExecutive/addSalesExecutive";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;

        if (status == 200) {

            Swal.fire({
                title: "Sales Executive Account",
                text: message,
                showCancelButton: false,
                icon: 'success',
                confirmButtonText: "Okay, continue",
            }).then((result) => {
                redirectPageWithTimer(0, 'account_sales_executive');
            });


        } else {
            showDialogMessage("Sales Executive Account", message, "error", true);
        }

    });
}
</script>