<script>
$(document).ready(function() {
    changeMaterialList('cert');

    $(".nav-tabs .nav-link").click(function() {
        // Remove "active" class from all nav links
        $(".nav-tabs .nav-link").removeClass("active");

        // Add "active" class only to the clicked element
        $(this).addClass("active");

        console.log(this);
    });
});

function changeMaterialList(type){

    $('#currentType').text(type.replace(/\b\w/g, l => l.toUpperCase()));

    $('#materialListRow').empty();

    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken,
        materialType: type
    };

    const url = "controller/material/getAllMaterial";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;
        const dataList = data.data;

        if (dataList.length > 0) {
            $.each(dataList, function(index) {
                renderDataList(dataList[index], index);
            });
        }

    });
}

function renderDataList(data, index){

    const mediaLink = `upload/${data}`;  
    var thumbnailUrl = "";

    if(data.includes(".pdf")){
        thumbnailUrl = "https://portal.multihome.tech/assets/images/pdf_placeholder.png";
    }else{
        thumbnailUrl = mediaLink;
    }

    const filename = formatFileName(data);

    const contentStr = `<div class="col-xl-3 col-sm-6">
                            <div class="product-box">
                                <div class="product-img pt-4 px-4">
                                    <div class="product-wishlist">
                                        <a href="${mediaLink}"
                                            download>
                                            <i class="mdi mdi-download"></i>
                                        </a>
                                    </div>
                                    <img src="https://portal.multihome.tech/${thumbnailUrl}"
                                        alt="" class="img-fluid mx-auto d-block marketing-certs">
                                </div>

                                <div class="text-center product-content p-4">
                                    <h5 class="mb-1">
                                        <a href="#" class="text-reset font-size-15">${filename}</a>
                                    </h5>
                                </div>
                            </div>
                        </div>`;

    $('#materialListRow').append(contentStr);
}


function formatFileName(fileName) {
    return fileName
        .replace(/\.[^/.]+$/, '') // Remove the file extension
        .replace(/_/g, ' ') // Replace underscores with spaces
        .replace(/\b\w/g, char => char.toUpperCase()); // Capitalize each word
}

</script>