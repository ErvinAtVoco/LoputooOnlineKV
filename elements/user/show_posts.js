jQuery(function ($) {
    $.ajax({
        type: 'POST',
        url: myAjax.ajaxurl,
        data: {
            action: 'show-default-muuk',
            nonce: myAjax.ajaxNonce,
        },
        success: function (response) {
            render_posts(response, 'muuk');
        },
        error: function (error) {
            console.log(error);
        }
    });
    $.ajax({
        type: 'POST',
        url: myAjax.ajaxurl,
        data: {
            action: 'show-default-uur',
            nonce: myAjax.ajaxNonce,
        },
        success: function (response) {
            render_posts(response, 'uur');
        },
        error: function (error) {
            console.log(error);
        }
    })
});

function nextPosts(type) {
    jQuery(function ($) {
        $.ajax({
            type: 'POST',
            url: myAjax.ajaxurl,
            data: {
                action: 'show-next-posts',
                nonce: myAjax.ajaxNonce,
                type: type
            },
            success: function (response) {
                console.log(response);
                render_posts(response, type);
            },
            error: function (error) {
                console.log(error);
            }
        })
    });
};

function prevPosts(type) {
    jQuery(function ($) {
        $.ajax({
            type: 'POST',
            url: myAjax.ajaxurl,
            data: {
                action: 'show-prev-posts',
                nonce: myAjax.ajaxNonce,
                type: type
            },
            success: function (response) {
                console.log(type);
                render_posts(response, type);
            },
            error: function (error) {
                console.log(error);
            }
        })
    });
}

function render_posts(response, type) {
    let container = document.getElementById(type + '-posts');
    container.innerHTML = "";

    for (let i = 0; i < response.length; i++) {
        container.innerHTML +=
            `<div class="post-container">
                    <div class="post-seperator">
                        <img class="post-image" src="${response[i].image}" />
                    </div>
                    <div class="post-seperator">
                        <h5 class="post-title">${response[i].post_title}</h5>
                        <p class="post-id">${response[i].ID}</p>
                    </div>
                    <div class="post-seperator">
                        <h5 class="post-price">${response[i].price}€</h5>
                    </div>
                    <div class="post-seperator">
                            <div>
                                <p class="post-date-subtitle">Sisestatud:</p>
                                <p class="post-date">${response[i].post_date}</p>
                            </div>
                    </div>
                </div>
                <div class="post-container">
                    <button onclick="createClientDay(${response[i].ID}, '${response[i].type}', '${response[i].image}', '${response[i].post_title}', ${response[i].price}, '${response[i].post_date}')" id="post-button">Telli kliendipäev</button>
                    <button id="post-button" onclick="editPost(${response[i].ID})">Muuda</button>
                    <button onclick="deleteNotification(${response[i].ID}, '${response[i].type}', '${response[i].image}', '${response[i].post_title}', ${response[i].price}, '${response[i].post_date}')" id="post-button">Kustuta</button>
                </div>`
    }
}

//////////////////////////
// CLOSE POPUP
/////////////////////////

function closePopup() {
    let container = document.getElementById('popup-container');
    container.classList.remove("open");
    container.innerHTML = '';
}

//////////////////////////
// KLIENDIPÄEV
/////////////////////////

function createClientDay(id, type, image, title, price, date) {
    console.log('Notification!');
    let container = document.getElementById('popup-container');
    container.innerHTML = '';
    container.innerHTML = 
    `
    <div class="notification-container">
        <div class="notification-container">
                <h3 style="margin-bottom: 15px;">Kliendipäev</h3>
                <div class="post-container">
                    <div class="post-seperator">
                        <img class="post-image" src="${image}" />
                    </div>
                    <div class="post-seperator">
                        <h5 class="post-title">${title}</h5>
                        <p class="post-id">${id}</p>
                    </div>
                    <div class="post-seperator">
                        <h5 class="post-price">${price}€</h5>
                    </div>
                    <div class="post-seperator">
                            <div>
                                <p class="post-date-subtitle">Sisestatud:</p>
                                <p class="post-date">${date}</p>
                            </div>
                    </div>
                </div>
            </div>
        <div class="kliendipaevad">
            <h4 style="margin-bottom: 15px;">Valige kuupäev ja kellaaeg<h4>
            <h5 style="margin-bottom: 15px;">Algus</h5>
            <input id="algus" type="datetime-local" style="margin-bottom: 15px;">
            <h5 style="margin-bottom: 15px;">Lõpp</h5>
            <input id="lõpp" type="datetime-local" style="margin-bottom: 15px;">
            <h4 style="margin-bottom: 15px;">Kas soovite eelregistreerimist?</h4>
            <div style="margin-top: 15px; display: flex;">
                <label style="font-size: 24px; margin-right: 10px" for="jah">Jah</label>
                <input style="margin-right: 15px" type="radio" id="jah" name="kliendipaev" value="jah">
                <label style="font-size: 24px; margin-right: 10px" for="ei">Ei</label>
                <input type="radio" id="ei" name="kliendipaev" value="ei">
            </div>
        </div>
        <div style="margin-top: 15px; display: flex;" class="post-container">
            <button onclick="closePopup()" id="post-button">Sulge</button>
            <button onclick="" id="post-button">Salvesta</button>
         </div>
    </div>
    `
    container.classList.add("open");
}

function sumbitClientDay() {
    jQuery(function ($) {
        $.ajax({
    
        });
    })
    
}

//////////////////////////
// DELETING POST
/////////////////////////

// Open the popup for deleting a post accepts id, type, image, title, price, data so it can render the needed
// post inside of the popup, id and type are passed to the deletePost() funciton
function deleteNotification(id, type, image, title, price, date) {
    console.log('Notification!');
    let container = document.getElementById('popup-container');
    container.innerHTML =
        `
            <div class="notification-container">
                <h3 style="margin-bottom: 15px;">Kas soovite kustutada kuulutust</h3>
                <div class="post-container">
                    <div class="post-seperator">
                        <img class="post-image" src="${image}" />
                    </div>
                    <div class="post-seperator">
                        <h5 class="post-title">${title}</h5>
                        <p class="post-id">${id}</p>
                    </div>
                    <div class="post-seperator">
                        <h5 class="post-price">${price}€</h5>
                    </div>
                    <div class="post-seperator">
                            <div>
                                <p class="post-date-subtitle">Sisestatud:</p>
                                <p class="post-date">${date}</p>
                            </div>
                    </div>
                </div>
                <div class="post-container">
                    <button onclick="closePopup()" id="post-button">Ei</button>
                    <button onclick="deletePost(${id}, '${type}')" id="post-button">Jah</button>
                </div>
            </div>`;
    container.classList.add("open");
    console.log(container);
}

// Deletes the post that is inside of the popup, after deleting the post it will
// refresh the list of posts and reset the popup containers html
function deletePost(id, type) {
    let container = document.getElementById('popup-container');
    jQuery(function ($) {
        $.ajax({
            type: 'POST',
            url: myAjax.ajaxurl,
            data: {
                action: 'delete-selected-post',
                nonce: myAjax.ajaxNonce,
                id: id,
                type: type,
            },
            success: function (response) {
                console.log(response);
                render_posts(response, type);
                container.classList.remove("open");
                container.innerHTML = '';
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
};