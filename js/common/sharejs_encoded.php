<script>
const alertTimer = 1500;
// Secret key (store securely, e.g., in an environment variable or secure config)
var SECRET_KEY = 'KDOg8HWSvwedpQbnawGFZTfwsnEUI5Zor';

$(document).ready(function() {

});

function initNewDatatable() {
    $(".datatable").DataTable({
        pageLength: 100
    });
    $(".dataTables_length select").addClass("form-select form-select-sm");
}

function initNewDatatableDesc() {
    $(".datatable").DataTable({
        pageLength: 100,
          order: [
            [0, 'desc']
        ] 
    });
    $(".dataTables_length select").addClass("form-select form-select-sm");
}

function initNewDatatableWithExcel(tableName) {

    // Initialize the new DataTable with the Buttons extension for export
     $(`#${tableName}`).DataTable({
        pageLength: 50,
        dom: 'Bfrtip', // Buttons + Filtering + Table + Pagination
        buttons: [{
            extend: 'excelHtml5',
            text: 'Export to Excel'
        }],
        responsive: true,
        lengthChange: false,
        searching: true,
        order: [
            [0, 'asc']
        ] // sort by first column descending
    });

}


function comingSoon() {
    Swal.fire({
        title: "Alert",
        text: "Coming soon, pleaes wait developer to update",
        icon: "info"
    });
}

function loadingStart() {
    $('#loadingSpinnerDiv').css('display', 'flex');
}

function loadingClose() {
    $('#loadingSpinnerDiv').css('display', 'none');
}

function showDialogMessage(title, text, icon, showCancelBtn) {

    // icon = info, error, success
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: showCancelBtn,
    });
}

function showDialogMessageWithTimer(title, text, icon, showCancelBtn, redirectPage) {
    let timerInterval;
    Swal.fire({
        title: title,
        html: `${text}<br><b>Redirecting in <span id="countdown">3</span> seconds...</b>`,
        icon: icon,
        showCancelButton: showCancelBtn,
        timer: 3000,
        didOpen: () => {
            const countdownElem = document.getElementById("countdown");
            let timeLeft = 3;
            timerInterval = setInterval(() => {
                timeLeft--;
                if (countdownElem) {
                    countdownElem.textContent = timeLeft;
                }
            }, 1000);
        },
        willClose: () => {
            clearInterval(timerInterval);
            window.location.href = redirectPage; // Change the URL to your actual dashboard page
        }
    });
}


function sendPostRequestWithCallback(data, url, debug) {

    var format = "json";
    if (debug) {
        format = 'text';
    }

    return new Promise((resolve, reject) => {

        $(document).ajaxStart(function() {
            loadingStart();
        });

        $.ajax({
            type: "POST",
            dataType: format,
            data: data,
            url: url,
            global: true,
            success: function(data) {

                loadingClose();
                if (debug) {
                    console.log(data);
                }
                resolve(data);
            },
            error: function(request, status, error) {
                loadingClose();
                showDialogMessage("Request Error", error, "error", true);
                reject(status);
            }
        });
    });
}

function sendPostRequestWithCallbackAwait(data, url, debug) {
    let format = debug ? "text" : "json";

    return new Promise((resolve, reject) => {
        $(document).ajaxStart(function () {
            loadingStart();
        });

        $.ajax({
            type: "POST",
            dataType: format,
            data: data,
            url: url,
            global: true,
            success: function (response) {
                loadingClose();
                if (debug) {
                    console.log(response);
                }
                resolve(response);
            },
            error: function (request, status, error) {
                loadingClose();
                showDialogMessage("Request Error", error, "error", true);
                reject(new Error(error || status));
            }
        });
    });
}


function sendPostRequestWithCallbackWithNoSpinner(data, url, debug) {

var format = "json";
if (debug) {
    format = 'text';
}

return new Promise((resolve, reject) => {

    $(document).ajaxStart(function() {
    });

    $.ajax({
        type: "POST",
        dataType: format,
        data: data,
        url: url,
        global: false,
        success: function(data) {

            loadingClose();
            if (debug) {
                console.log(data);
            }
            resolve(data);
        },
        error: function(request, status, error) {
            loadingClose();
            showDialogMessage("Request Error", error, "error", true);
            reject(status);
        }
    });
});
}


//Last Update: 26/01/2025
var ranges = [
    '\ud83c[\udf00-\udfff]', // U+1F300 to U+1F3FF
    '\ud83d[\udc00-\ude4f]', // U+1F400 to U+1F64F
    '\ud83d[\ude80-\udeff]', // U+1F680 to U+1F6FF
    '\u21b5',
];

function menuBarScrollToBottom() {
    $('#desktopMenuFlex').animate({
        scrollTop: $('#desktopMenuFlex').prop("scrollHeight")
    });
}

function removeInvalidChars(str) {
    str = str.replace(new RegExp(ranges.join('|'), 'g'), '');
    return str;
}

function transformDate(date) {
    var convertedMonth;
    var convertedDay;
    const castString = String(date);

    const res = castString.split('-');
    const year = res[0];
    const month = res[1];
    const day = res[2];

    if (month < 10) {
        convertedMonth = `0${month}`;
    } else {
        convertedMonth = month;
    }

    if (day < 10) {
        convertedDay = `0${day}`;
    } else {
        convertedDay = day;
    }

    return `${year}-${convertedMonth}-${convertedDay}`;
}

function transformDate2(date) {
    var convertedMonth;
    var convertedDay;
    const castString = String(date);

    const res = castString.split('/');

    const month = res[0];
    const day = res[1];
    const year = res[2];

    if (month < 10) {
        convertedMonth = `${month}`;
    } else {
        convertedMonth = month;
    }

    if (day < 10) {
        convertedDay = `${day}`;
    } else {
        convertedDay = day;
    }

    var returnDate = `${year}-${convertedMonth}-${convertedDay}`;
    returnDate = returnDate.trim().replace(' ', '');

    return returnDate;
}

function transformDate3(date) {
    var convertedMonth;
    var convertedDay;
    const castString = String(date);

    const res = castString.split('/');

    const day = res[0];
    const month = res[1];
    const year = res[2];

    if (month < 10) {
        convertedMonth = `${month}`;
    } else {
        convertedMonth = month;
    }

    if (day < 10) {
        convertedDay = `${day}`;
    } else {
        convertedDay = day;
    }

    var returnDate = `${year}-${convertedMonth}-${convertedDay}`;
    returnDate = returnDate.trim().replace(' ', '');

    return returnDate;
}

function transformDateToDealine(date1, date2) {
    const date1Split = date1.split('-');
    const date1year = date1Split[0];
    const date1month = date1Split[1];
    const date1day = date1Split[2];

    const fulldate1 = `${date1month}/${date1day}/${date1year}`;

    const date2Split = date2.split('-');
    const date2year = date2Split[0];
    const date2month = date2Split[1];
    const date2day = date2Split[2];

    const fulldate2 = `${date2month}/${date2day}/${date2year}`;

    return `${fulldate1} - ${fulldate2}`;
}

function transformDateForHomepage(date1, date2) {
    const date1Split = date1.split('-');
    const date1year = date1Split[0];
    const date1month = date1Split[1];
    const date1day = date1Split[2];

    const fulldate1 = `${date1day}/${date1month}/${date1year}`;

    const date2Split = date2.split('-');
    const date2year = date2Split[0];
    const date2month = date2Split[1];
    const date2day = date2Split[2];

    const fulldate2 = `${date2day}/${date2month}/${date2year}`;

    return `${fulldate1} - ${fulldate2}`;
}

function transformDate4(date1) {
    const date1Split = date1.split('-');
    const date1year = date1Split[0];
    const date1month = date1Split[1];
    const date1day = date1Split[2];

    const fulldate1 = `${date1day}/${date1month}/${date1year}`;

    return `${fulldate1}`;
}

function getTodayFull() {
    var today = new Date();
    var todayDate = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    return transformDate(todayDate);
}

function getDateDay(date) {
    var getDate = new Date(date);
    var day = getDate.getDay();
    var weekday = ['Sun', 'Mon', 'Tues', 'Wed', 'Thur', 'Fri', 'Sat'];

    return weekday[day];
}

function checkIfDuplicateExists(w) {
    return new Set(w).size !== w.length;
}

function isMobile() {
    // credit to Timothy Huang for this regex test:
    // https://dev.to/timhuang/a-simple-way-to-detect-if-browser-is-on-a-mobile-device-with-javascript-44j3
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        return true
    } else {
        return false
    };
};

function find_duplicate_in_array(arra1) {
    var object = {};
    var result = [];

    arra1.forEach(function(item) {
        if (!object[item]) object[item] = 0;
        object[item] += 1;
    });

    for (var prop in object) {
        if (object[prop] >= 2) {
            result.push(prop);
        }
    }
    return result;
}

function checkLetter(inputtxt) {
    var withNoDigits = inputtxt.replace(/[0-9]/g, '');
    withNoDigits = withNoDigits.replace(/[-!#*$%^&()_+|~=`{}\[\]:";'<>?,.\/]/, '');
    var letters = /^[A-Za-z]+$/;
    if (inputtxt.match(letters)) {
        return true;
    } else {
        return false;
    }
}

function delAllSymbol(inputtxt) {
    return inputtxt.replace(/[-!#*$%^&()_+|~=`{}\[\]:";'<>?,.\/]/, '');
}

function checkSymbol(inputtxt) {
    var symbols = /[-!$%^&()_+|~=`{}\[\]:";'<>?,\/]/;
    if (inputtxt.match(symbols)) {
        return true;
    } else {
        return false;
    }
}

function previewImageInBase64(inputID, uploadImg) {
    const file = document.getElementById(inputID).files[0];
    const reader = new FileReader();

    reader.addEventListener(
        'load',
        function() {
            // convert image file to base64 string

            $(`#${uploadImg}`).attr('src', reader.result);
            $(`#${uploadImg}`).show();
            $(`#${uploadImg}`).css('display', 'block');
        },
        false
    );

    if (file) {
        reader.readAsDataURL(file);
    }
}

async function compressImageBase64(base64String, maxWidth, quality, imageType) {
    return new Promise((resolve, reject) => {
        const img = new Image();

        img.onload = function() {
            let width = img.width;
            let height = img.height;

            // Calculate the new dimensions while maintaining aspect ratio based on maxWidth
            if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
            }

            // Create a canvas element
            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;

            // Draw the image on the canvas
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);

            // Get the compressed base64 data from the canvas
            const compressedBase64 = canvas.toDataURL(imageType, quality);

            resolve(compressedBase64);
        };

        img.onerror = function() {
            reject('Failed to load the image.');
        };

        img.src = base64String;
    });
}

async function compressImageBase64(base64String, maxWidth, quality, imageType) {
    return new Promise((resolve, reject) => {
        const img = new Image();

        img.onload = function() {
            let width = img.width;
            let height = img.height;

            // Calculate the new dimensions while maintaining aspect ratio based on maxWidth
            if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
            }

            // Create a canvas element
            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;

            // Draw the image on the canvas
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);

            // Get the compressed base64 data from the canvas
            const compressedBase64 = canvas.toDataURL(imageType, quality);

            resolve(compressedBase64);
        };

        img.onerror = function() {
            reject('Failed to load the image.');
        };

        img.src = base64String;
    });
}

function minifyImg2(dataUrl, newWidth, imageType = 'image/jpeg', imageArguments = 0.8) {
    var image, oldWidth, oldHeight, newHeight, canvas, ctx, newDataUrl;
    image = new Image();
    image.src = dataUrl;

    oldWidth = image.width;
    oldHeight = image.height;
    newHeight = Math.floor((oldHeight / oldWidth) * newWidth);

    canvas = document.createElement('canvas');
    canvas.width = newWidth;
    canvas.height = newHeight;

    ctx = canvas.getContext('2d');
    ctx.drawImage(image, 0, 0, newWidth, newHeight);
    //log(ctx);
    newDataUrl = canvas.toDataURL(imageType, imageArguments);
    return newDataUrl;
}

function refreshPage() {
    location.reload();
}

function refreshWithTimer(timerValue) {
    setTimeout(function() {
        location.reload();
    }, timerValue);
}

function redirectPage(pageurl) {
    window.location.replace(pageurl);
}

function redirectPageWithTimer(timerValue, pageurl) {
    setTimeout(function() {
        window.location.replace(pageurl);
    }, timerValue);
}

function capitalizeFirstLetter(string) {
    return string[0].toUpperCase() + string.slice(1);
}

function initActiveMenu(page) {
    $(`#${page}`).addClass('activeMenu');
    $(`#${page} img`).attr('src', `images/icons/${page}_white.png`).fadeIn();
}

function initDatatable(tableID, length, fileName) {

    $(`#${tableID}`).DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excel',
            title: fileName
        }],
        "order": [
            [0, 'asc']
        ],
        "displayLength": length,
        "drawCallback": function(settings) {

        }
    });

    $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass(
        'btn btn-data-download mr-1');

    $('#lang_file').DataTable({
        "language": {
            "url": "../../dist/js/pages/datatable/German.json"
        }
    });

}

function initDatatableWidthExport(tableID, length, fileName, columnList) {

    window.pdfMake.fonts = {
        chinese: {
            normal: 'chinese.ttf',
            bold: 'chinese.ttf',
            italics: 'chinese.ttf',
            bolditalics: 'chinese.ttf',
        }
    };

    $(`#${tableID}`).DataTable({
        dom: 'Bfrtip',
        buttons: [
            // 'copy', 'excel', 'print'
            {
                extend: 'excel',
                title: fileName,
                exportOptions: {
                    columns: columnList
                },
                action: function(e, dt, button, config) {
                    // Call the default action method to perform the actual export
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);

                }
            },
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'a4',
                title: fileName,
                footer: false,
                exportOptions: {
                    columns: columnList
                },
                customize: function(doc) {
                    doc.defaultStyle.width = 30;
                    doc.defaultStyle.fontSize = 10;
                    doc.defaultStyle.font = 'chinese';

                    // Find the DataTables table node in the content array
                    var tableNode = doc.content.find(function(node) {
                        return node.table !== undefined;
                    });

                    if (tableNode) {
                        tableNode.table.heights = function(row) {
                            return row % 2 === 0 ? 15 : 15;
                        }; // Set all row heights to 20
                    }

                    doc.content.splice(1, 0, {

                        text: fileName, // add your content here
                        margin: [0, 0, 0, 12],
                        alignment: 'left',
                    });
                }
            }
        ],
        "order": [
            [0, 'asc']
        ],
        "paging": true,
        "info": true,
        "searching": true,
        "displayLength": length,
        "drawCallback": function(settings) {}
    });

    $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass(
        'btn btn-data-download mr-1');

    $('#lang_file').DataTable({
        "language": {
            "url": "../../dist/js/pages/datatable/German.json"
        }
    });

}

function initDatatableWithNoDownload(tableID, length) {

    $(`#${tableID}`).DataTable({
        dom: 'Bfrtip',
        buttons: [

        ],
        "order": [
            [0, 'asc']
        ],
        "displayLength": length,
        "drawCallback": function(settings) {

        }
    });

    $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass(
        'btn btn-data-download mr-1');

    $('#lang_file').DataTable({
        "language": {
            "url": "../../dist/js/pages/datatable/German.json"
        }
    });

}


function initDatatableWithCallback(tableID, length, callbackFunc, withExport, fileName) {

    if (withExport == true) {
        $(`#${tableID}`).DataTable({
            dom: 'Bfrtip',
            buttons: [
                // 'copy', 'excel', 'print'
                {
                    extend: 'excel',
                    title: fileName
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    title: fileName,
                    footer: true,
                    charset: 'UTF-8',
                }
            ],
            "order": [
                [0, 'asc']
            ],
            "paging": false,
            "info": false,
            "displayLength": length,
            "drawCallback": function(settings) {
                callbackFunc();
            }
        });
        //after export generate then empty data
        $(`#${tableID}`).empty();
    } else {
        $(`#${tableID}`).DataTable({
            dom: 'Bfrtip',
            "order": [
                [0, 'asc']
            ],
            buttons: [],
            "displayLength": length,
            "drawCallback": function(settings) {
                callbackFunc();
            }
        });

    }


    // $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass(
    $('.buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass(
        'btn btn-data-download mr-1');

    $('#lang_file').DataTable({
        "language": {
            "url": "../../dist/js/pages/datatable/German.json"
        }
    });

}


function smartTrim(str, length, delim, appendix) {
    if (str.length <= length) return str;

    var trimmedStr = str.substr(0, length + delim.length);

    var lastDelimIndex = trimmedStr.lastIndexOf(delim);
    if (lastDelimIndex >= 0) trimmedStr = trimmedStr.substr(0, lastDelimIndex);

    if (trimmedStr) trimmedStr += appendix;
    return trimmedStr;
}

function copyToClipboard(igCopyVal) {
    // Create a "hidden" input
    var aux = document.createElement("input");
    // Assign it the value of the specified element
    aux.setAttribute("value", igCopyVal);
    // Append it to the body
    document.body.appendChild(aux);
    // Highlight its content
    aux.select();
    // Copy the highlighted text
    document.execCommand("copy");
    // Remove it from the body
    document.body.removeChild(aux);
}


function saveToLocalEncrypted(title, value) {
    try {
        const encryptedValue = CryptoJS.AES.encrypt(value, SECRET_KEY).toString();
        localStorage.setItem(title, encryptedValue);
        console.log("Token saved successfully");
    } catch (error) {
        console.error("Error encrypting token:", error);
    }
}

function readFromLocalDecrypted(title, secretKey) {
    try {
        const encryptedValue = localStorage.getItem(title);
        if (!encryptedValue) {
            console.warn("No token found");
            return null;
        }

        const bytes = CryptoJS.AES.decrypt(encryptedValue, secretKey);
        const decryptedValue = bytes.toString(CryptoJS.enc.Utf8);

        if (!decryptedValue) {
            console.warn("Invalid decryption key or corrupted data");
            return null;
        }

        return decryptedValue;
    } catch (error) {
        console.error("Error decrypting token:", error);
        return null;
    }
}

function clearAllLocal() {
    localStorage.clear();
    console.log("Local storage cleared");
}


function checkHasLoginSession() {
    const hasLogin = readFromLocalDecrypted('has_login', SECRET_KEY);

    if (hasLogin != "true") {
        clearAllLocal();
        redirectPageWithTimer(0, 'index');
    }
}

function checkIsQullFillIn(checkVal, quillsID, quillsSec) {

    var returnCheck = true;

    if (checkVal == "") {
        $(`#${quillsID}`).addClass('quillInvalid');
        $(`#${quillsSec} .ql-toolbar`).addClass('toolsInvalid');
        alertMessage('Please fill in some descriptions.');

        returnCheck = false;
    } else {
        $(`#${quillsID}`).removeClass('quillInvalid');
        $(`#${quillsSec} .ql-toolbar`).removeClass('toolsInvalid');

        returnCheck = true;
    }

    return returnCheck;
}

function validateEmail(inputText) {
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (inputText.match(mailformat)) {
        return true;

    } else {
        return false;
    }
}

function closeContent() {
    const splitMsg = currentPage.split("_")[1];
    window.location.href = splitMsg;
}

function renderFormContent(e) {
    const btnId = e.target.id;
    const btnText = $(`#${btnId}`).text();

    if (btnText == "Show More") {
        $(`#${btnId}`).text('Hide');
        $(`#${btnId}Sec`).fadeIn().css('display', 'flex');
    } else {
        $(`#${btnId}`).text('Show More');
        $(`#${btnId}Sec`).hide();
    }
}

function previewImageInBase64WithDel(inputID, uploadImg) {
    const file = document.getElementById(inputID).files[0];
    const reader = new FileReader();

    reader.addEventListener(
        'load',
        function() {
            // convert image file to base64 string

            $(`#${uploadImg}`).attr('src', reader.result);
            $(`#${uploadImg}`).show();
            $(`#${uploadImg}`).css('display', 'block');

            $(`#${inputID}DelBtn`).css('display', 'flex');
        },
        false
    );

    if (file) {
        reader.readAsDataURL(file);
    }
}


function checkIsQullFillIn(checkVal, quillsID, quillsSec) {

    var returnCheck = true;

    if (checkVal == "") {
        $(`#${quillsID}`).addClass('quillInvalid');
        $(`#${quillsSec} .ql-toolbar`).addClass('toolsInvalid');
        alertMessage('Please fill in some descriptions.');

        returnCheck = false;
    } else {
        $(`#${quillsID}`).removeClass('quillInvalid');
        $(`#${quillsSec} .ql-toolbar`).removeClass('toolsInvalid');

        returnCheck = true;
    }

    return returnCheck;
}

function decodeHtml(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}

function randomCode(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() *
            charactersLength));
    }
    return result;
}

function isInvalidSelect2Input(ele) {
    $(`#select2-${ele}-container`).addClass('is-invalid-border');
}

function validSelect2Input(ele) {
    $(`#select2-${ele}-container`).removeClass('is-invalid-border');
}

function trimString(string, length) {
    return string.length > length ?
        string.substring(0, length) + '...' :
        string;
}

function arrayToString(arr) {
    return arr.join(', ');
}

function getCurrentPagePath(){
    return location.pathname.split("/").slice(-1)[0];
}

function formatNumber(num) {
    return num >= 1000 ? num.toLocaleString() : num.toString();
}

function formatCurrency(num) {
    return num >= 1000 ? num.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : num.toFixed(2);
}

function padNumber(num) {
    return String(num).padStart(2, '0');
}

</script>