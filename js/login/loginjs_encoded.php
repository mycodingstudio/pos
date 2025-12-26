<script>
$(document).ready(function() {
    const hasLogin = readFromLocalDecrypted('has_login', SECRET_KEY);
    const isRemember = readFromLocalDecrypted('is_remember', SECRET_KEY);
    console.log(`hasLogin: ${hasLogin}`);

    if(hasLogin == "true" && isRemember == "true"){
        console.log('redirect');
        redirectPageWithTimer(0, 'dashboard');
    }else if(hasLogin == "true" && isRemember == "false"){
        clearAllLocal();
    }else if(hasLogin == "false"){
        clearAllLocal();
    }
});

function loginFunc() {

    const username = $('#username').val().toUpperCase();
    const userpassword = $('#userpassword').val();

    if(username == "" || userpassword == ""){
        showDialogMessage("Login Alert", "Please check username and passowrd", "error");
        return;
    }

    const params = {
        loginUsername: username,
        loginPassword: userpassword,
    };

    const url = "controller/admin/verifyAccount";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;

        if (status == 200) {
            const isRememberCheck = $('#auth-remember-check').prop('checked');
            const isRememberCheckStr = isRememberCheck ? 'true' : 'false';
            const accesstoken = data.data.access_token;
            saveToLocalEncrypted('username', username);
            saveToLocalEncrypted('access_token', accesstoken);
            saveToLocalEncrypted('has_login', "true");
            saveToLocalEncrypted('is_remember', isRememberCheckStr);
            showDialogMessageWithTimer("Login", message, "success", false, 'dashboard');

        } else {
            showDialogMessage("Login Alert", message, "error", true);
        }

    });

}

function signUpFunc() {
    Swal.fire({
        title: "Registration",
        text: "Please contract admin for account registration.",
        icon: "info"
    });
}

function forgotPassFunc() {
    comingSoon();
}
</script>