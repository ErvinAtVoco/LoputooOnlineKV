// Function to render posts with html using json encoded data from jquery ajax
function render_posts(response, type) {
    let container = document.getElementById(type + '-posts');
    document.getElementById(`count-${type}`).innerHTML = '';
    document.getElementById(`count-${type}`).innerHTML = `(${response.length})`;
    
    container.innerHTML = "";
    if(response.length === 0) {
        return;
    }
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
