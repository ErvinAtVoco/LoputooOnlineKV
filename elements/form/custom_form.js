// Get all segments of the form and set their display to none by default
const formElements = ["first-container", "second-container", "third-container", "forth-container"];
const formSteps = ["1-samm", "2-samm", "3-samm", "4-samm"];
for (let i = 1; i < formElements.length; i++) {
	document.getElementById(formElements[i]).classList.add('hidden');
}

// Calculate ruutmeetri hind

let euro = new Intl.NumberFormat('eu', {
	style: 'currency',
	currency: 'EUR',
});

document.getElementById('hind').addEventListener("input", (event) => calculateSquareMeeterPrice(event));
document.getElementById('pindala').addEventListener("input", (event) => calculateSquareMeeterPrice(event));

function calculateSquareMeeterPrice(event) {
	event.preventDefault();
	let pindala = document.getElementById('pindala').value;
	let hind = document.getElementById('hind').value;
	if (hind / pindala == NaN) {
		document.getElementById('ruutmeetri-hind').innerHTML = "";
		document.getElementById('ruutmeetri-hind').innerHTML = "Ruutmeetri arvutus vigane";
		return;
	}
	document.getElementById('ruutmeetri-hind').innerHTML = "";
	document.getElementById('ruutmeetri-hind').innerHTML = `${euro.format(hind / pindala)}`;
}

// Change realestate on click
function changeRealEstate(id) {
	if (currentRealEstate != id && currentRealEstate != null) {
		let element = document.getElementById(currentRealEstate);
		element.classList.remove("realestate-button-selected");
		element.classList.add("realestate-button");
	}

	let currentButton = document.getElementById(id);
	currentButton.classList.remove("realestate-button");
	currentButton.classList.add("realestate-button-selected");

	currentRealEstate = id;
};

// Change marketing type on click
function changeType(id) {
	if (currentType != id && currentType != null) {
		let element = document.getElementById(currentType);
		element.classList.remove("marketing-button-selected");
		element.classList.add("marketing-button");
	}

	let currentButton = document.getElementById(id);
	currentButton.classList.remove("marketing-button");
	currentButton.classList.add("marketing-button-selected");

	currentType = id;
};

// On click function that runs when the user presses the next button under the form..
function nextForm() {
	if (defaultForm > formElements.length) {
		return;
	}
	if (checkFormErrors()) {
		document.getElementById(formElements[defaultForm]).classList.add('hidden');
		defaultForm += 1;
		document.getElementById(formElements[defaultForm]).classList.remove('hidden');
		let indicator = document.getElementById(formSteps[defaultForm]);
		if (indicator.classList.contains('step-indicator')) {
			indicator.classList.remove('step-indicator');
			indicator.classList.add('step-indicator-current')
		}
	}
}

// Go to previous form
function previousForm() {
	if (defaultForm < 0) {
		return;
	}
	document.getElementById('error-result').innerHTML = "";
	document.getElementById(formElements[defaultForm]).classList.add('hidden');
	defaultForm -= 1;
	document.getElementById(formElements[defaultForm]).classList.remove('hidden');
	let indicator = document.getElementById(formSteps[defaultForm]);
	if (indicator.classList.contains('step-indicator')) {
		indicator.classList.remove('step-indicator');
		indicator.classList.add('step-indicator-current')
	}
}

// Thumbnail image upload
let thumbnailImage = [];
let uploadedThumbnailDiv = document.querySelector("#thumbnail-uploaded");
let thumbnailInput = document.querySelector("#thumbnail-input");
let thumbnailInputDiv = document.querySelector("#thumbnail-input-div")
let thumbnailUploadButton = document.querySelector("#thumbnail-button");

thumbnailUploadButton.onclick = function (event) {
	event.preventDefault();
	thumbnailInput.click();
}

thumbnailInput.addEventListener("change", (event) => {
	event.preventDefault();
	if (thumbnailImage.length > 0) {
		deleteThumbnailImage(0);
	}
	const file = thumbnailInput.files;
	thumbnailImage.push(file[0]);
	displayThumbnail();
	thumbnailInput.value = "";
});

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

function deleteThumbnailImage(index) {
	thumbnailImage.splice(index, 1);
	displayThumbnail();
};

// Gallery images upload
let galeriiImagesToUpload = [];
let uploadedFilesDiv = document.querySelector("#galerii-uploaded");
let input = document.querySelector("#galerii-input")
let uploadButton = document.querySelector("#galerii-button")

uploadButton.onclick = function (event) {
	event.preventDefault();
	input.click();
};

input.addEventListener("change", (event) => {
	event.preventDefault();
	const files = input.files;
	for (let i = 0; i < files.length; i++) {
		galeriiImagesToUpload.push(files[i])
	}
	displayUploadedImages()
	input.value = "";
});

function displayUploadedImages() {
	let images = "";
	galeriiImagesToUpload.forEach((image, index) => {
		images += `<div class="uploaded-image">
						<img src="${URL.createObjectURL(image)}" alt="image">
						<span class="delete-image-button" onclick="deleteQueuedImage(${index})"><i class="fa-solid fa-trash-can text-red"></i></span>
					</div>`
	});
	uploadedFilesDiv.innerHTML = images;
};

function deleteQueuedImage(index) {
	galeriiImagesToUpload.splice(index, 1);
	displayUploadedImages();
};

function submitCurrentForm(segment) {

	var form = document.querySelector('#uus-kuulutus-form');

	// Create a FormData object to handle files and form data
	var formData = new FormData(form);

	// Append other data if necessary
	formData.append('action', 'create-post');
	formData.append('realEstateType', currentRealEstate);
	formData.append('salesType', currentType);
	formData.append('segment', segment);

	thumbnailImage.forEach((image, index) => {
		formData.append(`thumbnail[]`, image);
	});

	galeriiImagesToUpload.forEach((image, index) => {
		formData.append(`uploads[]`, image);
	});

	// Perform the AJAX request
	jQuery(function ($) {
		$.ajax({
			type: 'post',
			url: myAjax.ajaxurl,
			data: formData,
			contentType: false,
			processData: false,
			error: function (xhr, status, error) {
				let errorMsg = `${JSON.parse(xhr.responseText).data}`;
				errorContainer.innerHtml = "";
				let textNode = document.createTextNode(errorMsg);
				errorContainer.appendChild(textNode);
			}
		})
	});
}