// Data object we will pass with ajax
let userData = {};
let acfData = {};
let avatarImgData = {};

// set variables
let userFirstname;
let userLastname;
let userEmail;
let isikukood;
let telefon;

// set variables for avatar
let avatarImg = [];

// Add event listeners
document.getElementById('nimi').addEventListener("change", (event) => {
    userFirstname = event.target.value;
});
document.getElementById('perekonnanimi').addEventListener("change", (event) => {
    userLastname = event.target.value;
});
document.getElementById('email').addEventListener("change", (event) => {
    userEmail = event.target.value;
});
document.getElementById('isikukood').addEventListener("change", (event) => {
    isikukood = event.target.value;
});
document.getElementById('telefon').addEventListener("change", (event) => {
    telefon = event.target.value;
});

// Add query selectors for avatar
let avatarInput = document.querySelector("#avatar-input");
let avatarDiv = document.querySelector(".avatar-div");
let avatarPreviewDiv;

// Add variables for updating avatar
let avatarUpdated = false;
let newAvi = [];
let previousAvi = "";

// Open pop up for avatar editing
function updateAvatar (currentAvi) {
    console.log('Notification!');
    let container = document.getElementById('popup-container');
    container.innerHTML = '';
    if (previousAvi === null ||previousAvi === "") {
        container.innerHTML = 
            `<div class="notification-container">
                <h3 style="margin-bottom: 15px;">Uuenda profiili pilti</h3>
                <div class="post-container">
                    <div class="post-seperator">
                        <div class="avatar-preview">
                            <img style="border-radius: 100%; width: 100%; height: 100%;" class="post-image" src="${currentAvi}" />
                        </div>
                    </div>
                </div>
                <div class="post-container">
                    <button onclick="uploadAvi()" id="post-button">Vali fail</button>
                    <button onclick="closePopup()" id="post-button">Katkesta</button>
                    <button onclick="confirmUpload('${currentAvi}')" id="post-button">Uuenda</button>
                </div>
            </div>`;
    } else if (previousAvi !== null || previousAvi !== "") {
        container.innerHTML = 
            `<div class="notification-container">
                <h3 style="margin-bottom: 15px;">Uuenda profiili pilti</h3>
                <div class="post-container">
                    <div class="post-seperator">
                        <div class="avatar-preview">
                            <img style="border-radius: 100%; width: 100%; height: 100%;" class="post-image" src="${currentAvi}" />
                        </div>
                    </div>
                </div>
                <div class="post-container">
                    <button onclick="uploadAvi()" id="post-button">Vali fail</button>
                    <button onclick="resetAvi('${previousAvi}')" id="post-button">Eemalda</button>
                    <button onclick="closePopup()" id="post-button">Katkesta</button>
                    <button onclick="confirmUpload('${currentAvi}')" id="post-button">Uuenda</button>
                </div>
            </div>`;
    };
    container.classList.add("open");
    avatarPreviewDiv = document.querySelector(".avatar-preview");
}

// Upload avatar function 
function uploadAvi() {
    avatarInput.click();
} 

// Add event listner for input
avatarInput.addEventListener("change", (event) => {
    if (newAvi.length > 0) {
        newAvi = [];
    };
    event.preventDefault();
	const file = avatarInput.files;
	newAvi.push(file[0]);
	displayAviPreview();
	avatarInput.value = "";
});

// Display new avatar in popup
function displayAviPreview() {
	let preview = "";
    avatarPreviewDiv.innerHTML = "";
	newAvi.forEach((image) => {
		preview += `<img style="border-radius: 100%; width: 100%; height: 100%;" src="${URL.createObjectURL(image)}" alt="image">`
	});
	avatarPreviewDiv.innerHTML = preview;
};

// Confirm avatar upload
function confirmUpload(currentAvi) {
    if (previousAvi === null || previousAvi === "") {
        previousAvi = currentAvi;
    };
    avatarUpdated = true;
    closePopup();
    avatarDiv.innerHTML = "";
    let newUploaded = "";
    newAvi.forEach((image) => {
		newUploaded += `<div class="avatar-image-div">
                            <img style="border-radius: 100%; width: 100%; height: 100%;" src="${URL.createObjectURL(image)}"/>
                        </div>
                        <button type="button" class="edit-avatar-button" onclick="updateAvatar('${URL.createObjectURL(image)}')"><i class="fa-solid fa-pencil"></i></button>`
	});
    avatarDiv.innerHTML = newUploaded;
};

// Reset avatar upload
function resetAvi(prevAvi) {
    avatarUpdated = false;
    previousAvi = "";
    closePopup();
    avatarDiv.innerHTML = "";
    avatarDiv.innerHTML = `<div class="avatar-image-div">
                                <img style="border-radius: 100%; width: 100%; height: 100%;" src="${prevAvi}"/>
                            </div>
                            <button type="button" class="edit-avatar-button" onclick="updateAvatar('${prevAvi}')"><i class="fa-solid fa-pencil"></i></button>`
};

// Handle data and send
function updateUser() {

    let tempData = [];
    let tempNames = [];

    // temp arrays needed for loop
    tempData = [userFirstname, userLastname, userEmail];
    tempNames = ['first_name', 'last_name', 'user_email'];

    // get wp_user_data into one object
    for (let i = 0; i < tempData.length; i++) {
        if  (tempData[i] === undefined) {
            continue;
        }
        if (tempData[i] !== undefined || tempData[i] !== '') {
            userData[tempNames[i]] = tempData[i];
        }
    }

    tempData = [isikukood, telefon];
    tempNames = ['isikukood', 'telefon'];

    for (let i = 0; i < tempData.length; i++) {
        console.log(tempData[i]);
        if  (tempData[i] === undefined) {
            continue;
        }
        if (tempData[i] !== undefined) {
            acfData[tempNames[i]] = tempData[i];
        }
    }

    // when new avatar is uploaded set wp data
    if (avatarUpdated === true) {
        avatarImgData = newAvi[0];
    }

/*    jQuery(function ($) {
        $.ajax({
            type: 'POST',
            url: myAjax.ajaxurl,
            data: {
                action: 'update-user-info',
                nonce: myAjax.ajaxNonce,
                wp_data: JSON.stringify(userData),
                acf_data: JSON.stringify(acfData),
                avi_data: JSON.stringify(avatarImgData)
            },
            success: function (response) {
                console.log(response);
            },
            error: function (error) {
                console.log(error);
            }
        })
    }); */

    jQuery(function ($) {
        var formData = new FormData();
    
        formData.append('action', 'update-user-info');
        formData.append('nonce', myAjax.ajaxNonce);
        formData.append('wp_data', JSON.stringify(userData));
        formData.append('acf_data', JSON.stringify(acfData));
    
        newAvi.forEach((image, index) => {
			formData.append(`avi_data[]`, image);
		});
    
        $.ajax({
            type: 'POST',
            url: myAjax.ajaxurl,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
}
