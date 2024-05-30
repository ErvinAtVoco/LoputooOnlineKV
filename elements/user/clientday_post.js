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
            <form>
                <h4 style="margin-bottom: 15px;">Valige kuupäev ja kellaaeg<h4>

                <h5 style="margin-bottom: 15px;">Kuupäev</h5>
                <input id="kuupaev" type="date" style="margin-bottom: 15px;">

                <h5 style="margin-bottom: 15px;">Algus</h5>
                <input id="algus" timeformat="24h" type="time" style="margin-bottom: 15px;">

                <h5 style="margin-bottom: 15px;">Lõpp</h5>
                <input id="lopp"  timeformat="24h" type="time" style="margin-bottom: 15px;">

                <h4 style="margin-bottom: 15px;">Kas soovite eelregistreerimist?</h4>
                <div style="margin-top: 15px; display: flex;">
                    <label style="font-size: 24px; margin-right: 10px" for="jah">Jah</label>
                    <input style="margin-right: 15px" type="radio" id="jah" name="kliendipaev" value="jah">
                    <label style="font-size: 24px; margin-right: 10px" for="ei">Ei</label>
                    <input type="radio" id="ei" name="kliendipaev" value="ei">
                </div>
            </form>
        </div>
        <div style="margin-top: 15px; display: flex;" class="post-container">
            <button onclick="closePopup()" id="post-button">Sulge</button>
            <button onclick="clientDay(${id})" id="post-button">Salvesta (50 krediiti)</button>
         </div>
    </div>
    `
    container.classList.add("open");
}


// Deletes the post that is inside of the popup, after deleting the post it will
// refresh create list of posts and reset the popup containers html
function clientDay(id) {
    let date = document.getElementById('kuupaev').value;
    let startTime = document.getElementById('algus').value;
    let endTime = document.getElementById('lopp').value;
    let container = document.getElementById('popup-container');
    jQuery(function ($) {
        $.ajax({
            type: 'POST',
            url: myAjax.ajaxurl,
            data: {
                action: 'create-client-day',
                nonce: myAjax.ajaxNonce,
                id: id,
                date: date,
                startTime: startTime,
                endTime: endTime
            },
            success: function (response) {
                container.classList.remove("open");
                container.innerHTML = '';
                console.log(response);
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
};