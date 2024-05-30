// Define variables to recieve images info
let galleryIds = [];
let galleryUrls = [];
let thumbnailId = [];
let thumbnailUrl = [];

// Define variables to upload new images
let thumbnailImage = [];
let galeriiImgsToUpload = [];

// Variable to store HTML elements
let thImage = "";
let galleryDisplay = "";

// Define query selectors for gallery
let uploadedFilesDiv = document.querySelector("#galerii-uploaded");
let input = document.querySelector("#galerii-input")
let uploadButton = document.querySelector("#galerii-button")

//Define query selectors for thumbnail
let uploadedThumbnailDiv = document.querySelector("#thumbnail-uploaded");
let thumbnailInput = document.querySelector("#thumbnail-input");
let thumbnailInputDiv = document.querySelector("#thumbnail-input-div")
let thumbnailUploadButton = document.querySelector("#thumbnail-button");

// Function to recieve images info from php on element load
function transferAttachmentInfo(galIds, galUrls, thumbId, thumbUrl) {
    galleryIds = galIds;
	console.log("galIds: " + galleryIds);
    galleryUrls = galUrls;
	console.log("galUrls: " + galleryUrls);
    thumbnailId = thumbId;
	console.log("thumbId: " + thumbnailId);
    thumbnailUrl = thumbUrl;
	console.log("thumbUrl " + thumbnailUrl);
    if (thumbnailUrl.length !== 0) {
        showExistingThumbnail(thumbnailId, thumbnailUrl);
    }
    if (galleryUrls.length !== 0) {
        showExistingGallery();
    }
};

// Function to display the thumbnail already uploaded with the post 
function showExistingThumbnail(thumbId, thumbUrl) {
		thImage += `<div class="uploaded-image">
						<img src="${thumbUrl}" alt="image">
						<span class="delete-image-button" onclick="deleteExistingThumbnail(0)"><i class="fa-solid fa-trash-can text-red"></i></span>
					</div>`
	uploadedThumbnailDiv.innerHTML = thImage;
};

// Function to delete already uploaded thumbnail
function deleteExistingThumbnail(index) {
    thImage = "";
	thumbnailId = "";
    thumbnailUrl= "";
	console.log("this is the id after splice: " + thumbnailId);
	console.log("this is url after splice: " + thumbnailUrl);
    uploadedThumbnailDiv.innerHTML = thImage;
}

// onclick handler to handle upload button click
thumbnailUploadButton.onclick = function (event) {
	event.preventDefault();
	thumbnailInput.click();
}

// Eventlistener to handle input change
thumbnailInput.addEventListener("change", (event) => {
	event.preventDefault();
	if (thumbnailImage.length > 0) {
		deleteThumbnailImage(0);
	}
    if (thumbnailId !== "") {
        deleteExistingThumbnail(0);
		console.log("Deleted thumbnail that was old!");
    }
	const file = thumbnailInput.files;
	thumbnailImage.push(file[0]);
	displayThumbnail();
	thumbnailInput.value = "";
});

// Function to display the thumbnail image
function displayThumbnail() {
	let thImage = "";
	thumbnailImage.forEach((image, index) => {
		thImage += `<div class="uploaded-image">
						<img src="${URL.createObjectURL(image)}" alt="image">
						<span class="delete-image-button" onclick="deleteThumbnailImage(${index})"><i class="fa-solid fa-trash-can text-red"></i></span>
					</div>`
	});
	uploadedThumbnailDiv.innerHTML = thImage;
};

// Function to delete the thumbnail image
function deleteThumbnailImage(index) {
	thumbnailImage.splice(index, 1);
	displayThumbnail();
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

// Function to submit form data
function submitEdits(id) {

	var form = document.querySelector('#edit-listing-form');

	// Create a FormData object to handle files and form data
	var formData = new FormData(form);

	// Append action, nonce and post id
	formData.append('action', 'submit-edit-post');
	formData.append('nonce', myAjax.ajaxNonce);
    formData.append('id', id);

	// apppend image data
	formData.append('thumbnail-old', thumbnailId);

	galleryIds.forEach((galleryId) => {
        formData.append('gallery-old[]', galleryId);
    });
	
	thumbnailImage.forEach((image, index) => {
		formData.append(`thumbnail[]`, image);
	});

	galeriiImgsToUpload.forEach((image, index) => {
		formData.append(`uploads[]`, image);
	});


	// Perform the AJAX request
	jQuery(function ($) {
		$.ajax({
			type: 'post',
			url: myAjax.ajaxurl,
			data: formData,
			processData: false,
			contentType: false,
			error: function (xhr, status, error) {
				console.log(error);
			}
		})
	});
}
