//////////////////////////
// DELETING POST
/////////////////////////

// Open the popup for deleting a post accepts id, type, image, title, price, data so it can render the needed
// post inside of the popup, id and type are passed to the deletePost() funciton
function deleteNotification(id, type, image, title, price, date) {
  console.log("Notification!");
  let container = document.getElementById("popup-container");
  container.innerHTML = `
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
  let container = document.getElementById("popup-container");
  jQuery(function ($) {
    $.ajax({
      type: "POST",
      url: myAjax.ajaxurl,
      data: {
        action: "delete-selected-post",
        nonce: myAjax.ajaxNonce,
        id: id,
        type: type,
      },
      success: function (response) {
        response = JSON.parse(response);
        console.log(response);
        console.log(response.length);
        console.log(type)
        render_posts(response, type);
        container.classList.remove("open");
        container.innerHTML = "";
      },
      error: function (error) {
        console.log(error);
      },
    });
  });
}
