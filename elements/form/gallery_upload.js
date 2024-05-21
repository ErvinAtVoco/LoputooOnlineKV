let galeriiImagesToUpload = [];
let uploadedFilesDiv = document.querySelector(".uploaded-files");
let input = document.querySelector("#galerii-input")
let uploadButton = document.querySelector("#galerii-button")

uploadButton.onclick = function(event) {
	event.preventDefault();
	input.click();
};

input.addEventListener("change", (event) => {
	event.preventDefault();
	const files = input.files;
	for(let i = 0; i < files.length; i++){
		galeriiImagesToUpload.push(files[i])
	}
	displayUploadedImages()
});

function displayUploadedImages() {
	let images = "";
	galeriiImagesToUpload.forEach((image, index) => {
		images += `<div class="uploaded-image">
						<img src="${URL.createObjectURL(image)}" alt="image">
						<span onclick="deleteQueuedImage(${index})">&times;</span>
					</div>`
	});
	uploadedFilesDiv.innerHTML = images;
};

function deleteQueuedImage(index) {
	galeriiImagesToUpload.splice(index, 1);
	displayUploadedImages();
};


jQuery(function ($) {
    $('#uus-kuulutus-form').on('submit', function (e) {
        e.preventDefault();

        var formData = new FormData(this);

		galeriiImagesToUpload.forEach((image, index) => {
			formData.append(`uploads[${index}]`, image)
		})

        $.ajax({
            type: 'post',
            url: myAjax.ajaxurl,
            data: formData,
            contentType: false,
            processData: false, 
            success: function (response) {
                console.log(response);
                alert('files was submitted');
            },
            error: function (xhr, status, error) {
				let errorMsg = xhr.responseText;
				document.getElementById('error-result').innerHtml = "";
				let textNode = document.createTextNode(errorMsg);
				errorContainer.appendChild(textNode);
            }
        });
    });
});

/*
// Define galerii array
let galerii = [];

const uploadedFiles = document.getElementById("uploaded-files");

let uploadButton = document.getElementById("gallery-upload");
let fileInput = document.getElementById("galerii-input");
let fileUpload = document.getElementById("galerii-files");

let imageFiles;
let fileNameDisplay = [];

uploadButton.onclick = function() {
	fileInput.click();
};

fileInput.addEventListener("change", function(event) {
	event.preventDefault();
	imageFiles = fileInput.files;
	
	const dataTransfer = new DataTransfer();

	dataTransfer.items.add(imageFiles);

	const fileList = dataTransfer.files;

	document.getElementById("galerii-files").files = fileList;
	fileUpload.files = fileList;
	/* for (let i = 0; i < files.files.length; i++) {
		let fileType = files[i].type;
		
		if (validExtention.includes(fileType)) {
			
		}
	} 
	
}) 


/* ction filesUploaded() {
	let filesList = [];
	// Fill galerii variable with form input
	let uploaded = document.getElementById("galerii");
	galerii = uploaded.files;

	if (galerii.length > 0) {
		
	}
} */
