<script>
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

var code = "";

if (urlParams.get('code')) {
    code = urlParams.get('code');
}

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

    initSalesExecutiveInfo();

});

function initSalesExecutiveInfo(){
    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken,
        code: code
    };

    const url = "controller/salesExecutive/loadSalesExecutiveInfo";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;
    
        if(status == 200){
            const infoData = data.data;
            updateData(infoData);
        }

    });
}

function updateData(data){
    const name = data.name.replace(/\b\w/g, l => l.toUpperCase());
    const salesCode = data.sales_code;
    const phone = data.phone;
    const state = data.state;
    const remark = data.remark;
    const status = data.status;

    $('#name').val(name);
    $('#salesCode').val(salesCode);
    $('#phone').val(phone);
    $('#state').val(state);
    $('#remark').val(remark);
    $('#salesExecutiveStatus').prop('checked', status == "available" ? true : false);

}

function updateSalesExecutiveFunc(event) {
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
    const salesExecutiveStatus = $('#salesExecutiveStatus').prop('checked');

    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken,
        code: code,
        name: name,
        salesCode: salesCode,
        state: state,
        remark: remark,
        phone: phone,
        salesExecutiveStatus: salesExecutiveStatus
    };


    const url = "controller/salesExecutive/updateSalesExecutive";
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