<script>
var imageList = [];
var imageListDict = [];
var updatingMode = true;


$(document).ready(function() {
    $('.needs-validation').parsley();
})

// var myDropzone = new Dropzone("#my-dropzone", {
//     url: "controller/upload/moveFiles", // Your upload URL
//     addRemoveLinks: true, // Add remove buttons
//     dictRemoveFile: "Remove", // Customize remove button text
//     acceptedFiles: "image/jpeg,image/png,image/jpg,application/pdf,video/mp4",
//     thumbnailWidth: 500, // Adjust thumbnail width (default: 120)
//     thumbnailHeight: 700, // Adjust thumbnail height (default: 120)
//     thumbnailMethod: "contain", // Options: "contain" (default), "crop"
//     renameFile: function(file) {
//         // Get file name without path
//         let originalName = file.name;

//         // Convert to lowercase and replace spaces with underscores
//         let sanitizedFileName = originalName.toLowerCase().replace(/\s+/g, "_");

//         // Extract name and extension
//         let fileExtension = "";
//         let fileBaseName = sanitizedFileName;

//         if (sanitizedFileName.includes(".")) {
//             fileExtension = sanitizedFileName.substring(sanitizedFileName.lastIndexOf(
//             ".")); // Extract extension
//             fileBaseName = sanitizedFileName.substring(0, sanitizedFileName.lastIndexOf(
//             ".")); // Extract base name
//         }

//         // Check if filename already exists in imageList
//         let newFileName = sanitizedFileName;
//         let counter = 1;

//         while (imageList.includes(newFileName)) {
//             newFileName = `${fileBaseName}_${counter}${fileExtension}`;
//             counter++;
//         }

//         return newFileName; // Return the unique file name
//     },
//     init: function() {

//         this.on("addedfile", function(file) {

//             const materialType = $('#materialType').val(); // Get selected material type

//             if (materialType === "") {
//                 showDialogMessage("Error", "Choose a material type first", "error", true);
//                 this.removeFile(file); // Remove the file from Dropzone
//                 return;
//             }

//             // Prevent Dropzone from trying to create a thumbnail for PDFs
//             if (file.type === "application/pdf") {
//                 this.emit("thumbnail", file,
//                     "/assets/images/pdf_placeholder.png"); // Use a placeholder thumbnail
//             }

//             // If materialType is selected, proceed with the upload
//             console.log(`Uploading file: ${file.name} for material type: ${materialType}`);
//         });

//         this.on("complete", function(file) {

//             console.log(`Uploaded file size: ${file.size} bytes`);

//             console.log('updatingMode')
//             // Retrieve all accepted files

//             if (!updatingMode) {
//                 const acceptedFiles = this.getAcceptedFiles();
//                 imageList = acceptedFiles.map((file) => file.upload ? file.upload
//                     .filename : file.name);

//                 // Log the uploaded filenames
//                 console.log("Uploaded filenames:", imageList);
//             }

//         });

//         this.on("removedfile", function(file) {
//             console.log(`File removed: ${file.name}`);

//             const acceptedFiles = this.getAcceptedFiles();
//             imageList = acceptedFiles.map((f) => f.upload ? f.upload.filename : f.name);

//             console.log("Updated image list after removal:", imageList);
//         });
//     },
//     transformFile: function(file, done) {

//         console.log('transformFile');
//         console.log(file);

//         // Check if the file is a PDF
//         if (file.type === "application/pdf") {
//             console.log("PDF detected, skipping compression.");
//             done(file); // Pass the original file without modification
//             return;
//         }


//         const fileReader = new FileReader();
//         fileReader.readAsDataURL(file);

//         fileReader.onload = function(event) {
//             const tempImg = new Image();
//             tempImg.src = event.target.result;

//             tempImg.onload = function() {
//                 const canvas = document.createElement("canvas");
//                 const MAX_WIDTH = 1200; // Maximum width of the compressed image
//                 const MAX_HEIGHT = 1200; // Maximum height of the compressed image
//                 let width = tempImg.width;
//                 let height = tempImg.height;

//                 // Maintain aspect ratio
//                 if (width > height) {
//                     if (width > MAX_WIDTH) {
//                         height = (height * MAX_WIDTH) / width;
//                         width = MAX_WIDTH;
//                     }
//                 } else {
//                     if (height > MAX_HEIGHT) {
//                         width = (width * MAX_HEIGHT) / height;
//                         height = MAX_HEIGHT;
//                     }
//                 }

//                 canvas.width = width;
//                 canvas.height = height;

//                 const ctx = canvas.getContext("2d");
//                 ctx.drawImage(tempImg, 0, 0, width, height);

//                 // Convert the canvas back to a Blob
//                 canvas.toBlob(function(blob) {
//                     done(blob);
//                 }, file.type, 0.8); // Compression quality (0.0 - 1.0)
//             };
//         };
//     }
// });

var myDropzone = new Dropzone("#my-dropzone", {
    url: "controller/upload/moveFiles", // Your upload URL
    addRemoveLinks: true, // Add remove buttons
    dictRemoveFile: "Remove", // Customize remove button text
    acceptedFiles: "image/jpeg,image/png,image/jpg,application/pdf,video/mp4,video/webm,video/quicktime", // Updated to accept video formats
    thumbnailWidth: 500, // Adjust thumbnail width (default: 120)
    thumbnailHeight: 700, // Adjust thumbnail height (default: 120)
    thumbnailMethod: "contain", // Options: "contain" (default), "crop"
    renameFile: function(file) {
        // Get file name without path
        let originalName = file.name;

        // Convert to lowercase and replace spaces with underscores
        let sanitizedFileName = originalName.toLowerCase().replace(/\s+/g, "_");

        // Extract name and extension
        let fileExtension = "";
        let fileBaseName = sanitizedFileName;

        if (sanitizedFileName.includes(".")) {
            fileExtension = sanitizedFileName.substring(sanitizedFileName.lastIndexOf(
            ".")); // Extract extension
            fileBaseName = sanitizedFileName.substring(0, sanitizedFileName.lastIndexOf(
            ".")); // Extract base name
        }

        // Check if filename already exists in imageList
        let newFileName = sanitizedFileName;
        let counter = 1;

        while (imageList.includes(newFileName)) {
            newFileName = `${fileBaseName}_${counter}${fileExtension}`;
            counter++;
        }

        return newFileName; // Return the unique file name
    },
    init: function() {

        this.on("addedfile", function(file) {

            const materialType = $('#materialType').val(); // Get selected material type

            if (materialType === "") {
                showDialogMessage("Error", "Choose a material type first", "error", true);
                this.removeFile(file); // Remove the file from Dropzone
                return;
            }

            // Prevent Dropzone from trying to create a thumbnail for PDFs and videos
            if (file.type === "application/pdf") {
                this.emit("thumbnail", file,
                    "/assets/images/pdf_placeholder.png"); // Use a placeholder thumbnail for PDFs
            } else if (file.type.startsWith("video/")) {
                this.emit("thumbnail", file, "/assets/images/video_placeholder.png"); // Use a placeholder thumbnail for videos
            }


            // If materialType is selected, proceed with the upload
            console.log(`Uploading file: ${file.name} for material type: ${materialType}`);
        });

        this.on("complete", function(file) {

            console.log(`Uploaded file size: ${file.size} bytes`);

            console.log('updatingMode')
            // Retrieve all accepted files

            if (!updatingMode) {
                const acceptedFiles = this.getAcceptedFiles();
                imageList = acceptedFiles.map((file) => file.upload ? file.upload
                    .filename : file.name);

                // Log the uploaded filenames
                console.log("Uploaded filenames:", imageList);
            }

        });

        this.on("removedfile", function(file) {
            console.log(`File removed: ${file.name}`);

            const acceptedFiles = this.getAcceptedFiles();
            imageList = acceptedFiles.map((f) => f.upload ? f.upload.filename : f.name);

            console.log("Updated image list after removal:", imageList);
        });
    },
    transformFile: function(file, done) {

        console.log('transformFile');
        console.log(file);

        // Check if the file is a PDF or a video and skip compression
        if (file.type === "application/pdf" || file.type.startsWith("video/")) {
            console.log("PDF or video detected, skipping compression.");
            done(file); // Pass the original file without modification
            return;
        }


        const fileReader = new FileReader();
        fileReader.readAsDataURL(file);

        fileReader.onload = function(event) {
            const tempImg = new Image();
            tempImg.src = event.target.result;

            tempImg.onload = function() {
                const canvas = document.createElement("canvas");
                const MAX_WIDTH = 1200; // Maximum width of the compressed image
                const MAX_HEIGHT = 1200; // Maximum height of the compressed image
                let width = tempImg.width;
                let height = tempImg.height;

                // Maintain aspect ratio
                if (width > height) {
                    if (width > MAX_WIDTH) {
                        height = (height * MAX_WIDTH) / width;
                        width = MAX_WIDTH;
                    }
                } else {
                    if (height > MAX_HEIGHT) {
                        width = (width * MAX_HEIGHT) / height;
                        height = MAX_HEIGHT;
                    }
                }

                canvas.width = width;
                canvas.height = height;

                const ctx = canvas.getContext("2d");
                ctx.drawImage(tempImg, 0, 0, width, height);

                // Convert the canvas back to a Blob
                canvas.toBlob(function(blob) {
                    done(blob);
                }, file.type, 0.8); // Compression quality (0.0 - 1.0)
            };
        };
    }
});


function materialTypeChange() {
    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);
    const materialType = $('#materialType').val();

    updatingMode = true;

    // Clear Dropzone images
    myDropzone.removeAllFiles(true);
    $('.dz-preview').remove(); // Ensure UI is fully cleared

    // Clear imageList
    imageList = [];

    const params = {
        materialType: materialType,
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken
    };

    const url = "controller/material/getAllMaterial";
    sendPostRequestWithCallback(params, url).then(function(data) {
        if (data.status == 200) {
            const mediaListTemp = data.data; // Get filenames

            imageList = mediaListTemp;

            $.each(mediaListTemp, function(index) {
                const filename = mediaListTemp[index];

                console.log(`filename: ${filename}`);
                const mockFile = {
                    name: filename,
                    size: 0, // Fake size (adjust if known)
                    accepted: true // Mark as accepted by Dropzone
                };

                var thumbnailUrl = `/upload/${filename}`; // Replace with actual URL
                if (filename.includes(".pdf")) {
                    thumbnailUrl = `/assets/images/pdf_placeholder.png`; // Replace with actual URL
                }

                myDropzone.files.push(mockFile); // Add to Dropzone internal array
                myDropzone.emit("addedfile", mockFile);
                myDropzone.emit("thumbnail", mockFile, thumbnailUrl);
                myDropzone.emit("complete", mockFile);

            });

            updatingMode = false;

        } else {
            showDialogMessage("Load Materials", data.message, "error", true);
        }
    });
}


function addMaterialFunc(event) {

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

    const materialType = $('#materialType').val();
    const materialType2 = materialType.replace(/\b\w/g, l => l.toUpperCase());
    const adminUsername = readFromLocalDecrypted('username', SECRET_KEY);
    const adminAccesstoken = readFromLocalDecrypted('access_token', SECRET_KEY);

    const uploadedImgList = JSON.stringify(imageList);

    const params = {
        adminUsername: adminUsername,
        adminAccesstoken: adminAccesstoken,
        materialType: materialType,
        imageList: uploadedImgList
    };

    const url = "controller/material/updateImageList";
    sendPostRequestWithCallback(params, url).then(function(data) {

        const status = data.status;
        const message = data.message;

        if (status == 200) {
            showDialogMessage(`${materialType2} Materials`, message, "success", true);
        } else {
            showDialogMessage(`${materialType2} Materials`, message, "error", true);
        }

    });
}
</script>