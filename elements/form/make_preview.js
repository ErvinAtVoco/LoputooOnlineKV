
function createTitle(formData) {
    let identifingNumber = formData.get('maja-nr') && formData.get('korter') ? `${formData.get('maja-nr')}-${formData.get('korter')}` : `${formData.get('maja-nr')}`;
    return `${currentType}, ${formData.get('tubade-arv')} tuba - ${identifingNumber} ${formData.get('tänav')}, ${formData.get('asula')}, ${formData.get('maakond')}`
}

function loadPreview() {
    let formData = new FormData(document.getElementById('uus-kuulutus-form'));
    console.log(formData);
    // Realestate Type and marketing type
    const bigTitleContainer = document.getElementById('brxe-fxbzux');
    bigTitleContainer.innerHTML = "";
    bigTitleContainer.innerHTML = `${currentType}-${currentRealEstate}`;

    // Title
    const titleContainer = document.getElementById('brxe-gbjsip');
    titleContainer.innerHTML = "";
    titleContainer.innerHTML = createTitle(formData);

    // Sub location
    const location = document.getElementById('brxe-vkfvkf');
    location.innerHTML = "";
    location.innerHTML = `${formData.get('asula')}, ${formData.get('maakond')}`

    // Get Price
    const priceContainer = document.getElementsByClassName('brxe-heading deal-price')[0];
    priceContainer.innerHTML = "";
    priceContainer.innerHTML = `${formData.get('hind')}€`

    // Get document and place information
    const katastrinumber = document.getElementsByClassName("brxe-text-basic katastrinumber")[0];
    katastrinumber.innerHTML = "";
    katastrinumber.innerHTML = formData.get('katastrinumber') === "" ? '-'  : `${formData.get('katastrinumber')}`;

    const kinnistuNumber = document.getElementsByClassName("brxe-text-basic kinnistu-number")[0];
    kinnistuNumber.innerHTML = "";
    kinnistuNumber.innerHTML = formData.get('kinnistu-number') === "" ? '-'  : `${formData.get('kinnistu-number')}`;

    // Room around
    const tubadeArv = document.getElementsByClassName('tubade-arv')[0];
    tubadeArv.innerHTML = "";
    tubadeArv.innerHTML = `${formData.get('tubade-arv')}`;

    // Bedrooms
    const magamisToad = document.getElementsByClassName('brxe-text-basic specific-single__value magamistubade-arv')[0];
    magamisToad.innerHTML = "";
    magamisToad.innerHTML = formData.get('magamistubade-arv') === "" ? '-'  : `${formData.get('magamistubade-arv')}`;

    // Bathrooms
    const wcArv = document.getElementsByClassName('brxe-text-basic specific-single__value WC')[0];
    wcArv.innerHTML = "";
    wcArv.innerHTML = formData.get('wc-arv') === "" ? '-'  : `${formData.get('wc-arv')}`;

    // Floor
    const korrus = document.getElementsByClassName('brxe-text-basic specific-single__value korrus')[0];
    korrus.innerHTML = "";
    korrus.innerHTML = formData.get('korrus') === "" ? '-'  : `${formData.get('korrus')}`; 

    // Construction year
    const ehitusAasta = document.getElementsByClassName('brxe-text-basic specific-single__value ehitusaasta')[0];
    ehitusAasta.innerHTML = "";
    ehitusAasta.innerHTML = formData.get('ehitusaasta') === "" ? '-' : `${formData.get('ehitusaasta')}`;

    // Condition
    const seisukord = document.getElementsByClassName("brxe-text-basic specific-single__value seisukord")[0];
    seisukord.innerHTML = "";
    seisukord.innerHTML = formData.get('seisukord') === "" ? '-' :  `${formData.get('seisukord')}`;

    // Ownership
    const omandivorm = document.getElementsByClassName("brxe-text-basic specific-single__value omandivorm")[0];
    omandivorm.innerHTML = "";
    omandivorm.innerHTML = formData.get('omandivorm') === "" ? '-' :  `${formData.get('omandivorm')}`;

    // Size
    const pindala = document.getElementsByClassName("brxe-text-basic specific-single__value pindala")[0];
    pindala.innerHTML = "";
    pindala.innerHTML = formData.get('pindala') === "" ? '-' : `${formData.get('pindala')}`;

    // Energy class
    const energiaklass = document.getElementsByClassName("brxe-text-basic specific-single__value energiaklass")[0];
    energiaklass.innerHTML = "";
    energiaklass.innerHTML = formData.get("energiaklass") === "" ? '-' :  `${formData.get('energiaklass')}`;

    // Furniture
    const sisustus = document.getElementsByClassName("brxe-text-basic specific-single__value sisustus")[0];
    sisustus.innerHTML = "";
    sisustus.innerHTML = formData.get("sisustus") === "" ? "-" : `${formData.get('sisustus')}`;

    // Squaremeeter price

    let euro = new Intl.NumberFormat('eu', {
        style: 'currency',
        currency: 'EUR',
    });

    const squareMeeter = document.getElementsByClassName("brxe-text-basic specific-single__value ruutmeetrihind")[0];
    squareMeeter.innerHTML = "";
    squareMeeter.innerHTML = `${euro.format(parseInt(formData.get("hind")) /  parseInt(formData.get("pindala")))}`;

    // Parking
    const praking = document.getElementsByClassName("brxe-text-basic specific-single__value parkimine")[0];
    praking.innerHTML = "";
    praking.innerHTML = formData.get("parkimine") === null ? "-" : `${formData.get('parkimine')}`;

    // Road conditions                                                                                      
    const teedeSeisukord = document.getElementsByClassName("brxe-text-basic specific-single__value teedeseisukord");
    teedeSeisukord.innerHTML = "";
    teedeSeisukord.innerHTML = formData.get("teedeseisukord") === null ? "-" : `${formData.get('teedeseisukord')}`;

    // Water
    const veevarustus = document.getElementsByClassName("brxe-text-basic specific-single__value veevarustus")[0];
    veevarustus.innerHTML = "";
    veevarustus.innerHTML = formData.get("veevarustus") === null ? "-" : `${formData.get('veevarustus')}`;

    // Kanalisatsioon
    const kanalisatsioon = document.getElementsByClassName("brxe-text-basic specific-single__value kanalisatsioon")[0];
    kanalisatsioon.innerHTML = "";
    kanalisatsioon.innerHTML = formData.get("kanalisatsioon") === null ? "-" : `${formData.get('kanalisatsioon')}`;

    // Images
    const images = document.getElementById("brxe-ympclu");
    thumbnailImage.forEach((image, index) => {
		images.innerHTML = `<div class="preview-image-container"">
						<img src="${URL.createObjectURL(image)}" alt="image">
					</div>`
	});
};


