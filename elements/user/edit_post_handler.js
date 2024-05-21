// Define variables to recieve gallery info
let galleryIds = [];
let galleryUrls = [];

// Define variables to upload new gallery images
let galeriiImgsToUpload = [];

// Variable to store HTML elements
let galleryDisplay = "";

// Define query selectors
let uploadedFilesDiv = document.querySelector("#galerii-uploaded");
let input = document.querySelector("#galerii-input")
let uploadButton = document.querySelector("#galerii-button")

// Function to recieve gallery info from php on element load
function transferGalleryInfo(galIds, galUrls) {
    galleryIds = galIds;
    galleryUrls = galUrls;
    showExistingGallery();
};

// Function to display the images already uploaded with the post
function showExistingGallery() {
    for (i = 0; i < galleryIds.length; i++) {
        galleryDisplay +=   `<div class="uploaded-image">
                                <img src="${galleryUrls[i]}" alt="image">
                                <span class="delete-image-button" onclick="deleteExistingGallery(${i})"><i class="fa-solid fa-trash-can text-red"></i></span>
                            </div>`
    }
    uploadedFilesDiv.innerHTML = galleryDisplay;
}

// Function to delete already uploaded gallery images
function deleteExistingGallery(index) {
    galleryDisplay = "";
	galleryIds.splice(index, 1);
    galleryUrls.splice(index, 1);
    showExistingGallery();
    if (galeriiImgsToUpload.length !== 0) {
        displayUploadedImages();
    }
}

// Event listener to handle file input click
uploadButton.onclick = function(event) {
	event.preventDefault();
	input.click();
};

// Event listener to handle image upload
input.addEventListener("change", (event) => {
	event.preventDefault();
	const files = input.files;
	for(let i = 0; i < files.length; i++){
		galeriiImgsToUpload.push(files[i])
	}
	displayUploadedImages()
	input.value = "";
});

// Function to display newly uploaded images
function displayUploadedImages() {
	galeriiImgsToUpload.forEach((image, index) => {
		galleryDisplay +=   `<div class="uploaded-image">
						        <img src="${URL.createObjectURL(image)}" alt="image">
						        <span class="delete-image-button" onclick="deleteUploadedImage(${index})"><i class="fa-solid fa-trash-can text-red"></i></span>
					        </div>`
	});
	uploadedFilesDiv.innerHTML = galleryDisplay;
};

// Function to delete uploaded images
function deleteUploadedImage(index) {
    galleryDisplay = "";
	galeriiImgsToUpload.splice(index, 1);
    if (galleryIds.length !== 0) {
        showExistingGallery();
    }
	displayUploadedImages();
};
