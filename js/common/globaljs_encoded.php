<script>
var isCurrentEditBtnOff = false;

$(document).ready(function() {

    // const fileName = getCurrentPagePath();

    // console.log(`fileName: ${fileName}`);

    // if (fileName != "index" && fileName != "") {
    //     checkHasLoginSession();
    //     initGlobalProfileInfo();

    // }

        $('#side-menu').show();

});

function isWithinThreeMinutes(checktime) {
    // Convert checktime to a Date object
    const checkDate = new Date(checktime);
    
    // Get the current time
    const currentTime = new Date();
    
    // Calculate the difference in milliseconds
    const diffInMs = currentTime - checkDate;
    
    // Convert to minutes
    const diffInMinutes = diffInMs / (1000 * 60);
    
    // Return false if the difference exceeds 3 minutes, otherwise true
    return diffInMinutes <= 3;
}

function initGlobalNoticationBoard() {
    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken
    };

    const url = "controller/tools/getAllActivities";
    sendPostRequestWithCallbackWithNoSpinner(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;
        const dataList = data.data;


        if (status == 200) {
            if (dataList.length > 0) {
                $.each(dataList, function(index) {
                    renderNotificationList(dataList[index], index);
                });
            }

        }

    });
}

function renderNotificationList(data, index) {
    const message = data.description;
    const createdDatetime = data.created_datetime;

    const contentStr = `<a href="javascript:void(0);" class="text-dark notification-item">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-xs">
                            <span class="avatar-title bg-primary rounded-circle font-size-16">
                                <i class="uil-bell"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="font-size-12 text-muted">
                            <p class="mb-1">${message}</p>
                            <p class="mb-0"><i class="mdi mdi-clock-outline"></i> ${createdDatetime}</p>
                        </div>
                    </div>
                </div>
            </a>`;

    $('#globalNotificationList').append(contentStr);
}

function initGlobalProfileInfo() {
    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken
    };

    const url = "controller/admin/initProfileInfo";
    sendPostRequestWithCallbackWithNoSpinner(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;

        if (status == 200) {
            const userData = data.data;
            updateTopBarData(userData);
        }else{
            signOutFunc();
        }

    });

}

function updateTopBarData(data) {

    const name = data.name;
    $('#topBarProfileName').text(name);

    const salesCode = data.sales_code;
    saveToLocalEncrypted('salescode', salesCode);

    $('#side-menu').show();

}

function signOutFunc() {
    clearAllLocal();
    redirectPageWithTimer(0, 'index');
}
</script>