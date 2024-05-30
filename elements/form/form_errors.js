// Variables
let errorContainer = document.getElementById('error-result');

// Checking if user has a valid cookie/is logged in
if (isUserLoggedIn()) {
	userConfirmed = true;
}

function checkFormErrors() {
	switch (defaultForm) {

		// 1st form section
		case 0:
			if (currentRealEstate === null) {
				displayError("Edasi liikumiseks palun valige hoone tüüp!");
				return false;
			}
			if (currentType === null) {
				displayError("Edasi liikumiseks palun valige tehingu tüüp!");
				return false;
			}
			if (!userConfirmed) {
				displayError("Edasi liikumiseks peate looma kasutaja!");
				return false;
			}

			var maakond = document.getElementById("maakond");
			var linnVald = document.getElementById("linn-vald");
			var asula = document.getElementById("asula");
			var tanav = document.getElementById("tänav");
			var majaNmr = document.getElementById("maja-nr");
			var korter = document.getElementById("korter");
			var postiindeks = document.getElementById("postiindeks");
			var katastrinumber = document.getElementById("katastrinumber");
			var kinnistunumber = document.getElementById("kinnistu-number");

			var omandivorm = document.getElementById("omandivorm");
			var ehitusaasta = document.getElementById("ehitusaasta");
			var seisukord = document.getElementById("seisukord");
			var pindala = document.getElementById("pindala");
			var hind = document.getElementById("hind");
			var energiaklass = document.getElementById("energiaklass");
			var tubadeArv = document.getElementById("tubade-arv");
			var korrus = document.getElementById("korrus");
			var korruseidKokku = document.getElementById("korruseid-kokku");


			if(!checkFieldsRegex([maakond, linnVald, asula, tanav, majaNmr, korter, postiindeks, katastrinumber, kinnistunumber, omandivorm,ehitusaasta, seisukord, pindala, hind, energiaklass, tubadeArv, korrus, korruseidKokku])) {
				return false;
			}

			errorContainer.innerHTML = ""
			return true;

		// 2nd form section
		case 1:

			var magamistubadeArv = document.getElementById("magamistubade-arv");
			var wcArv = document.getElementById("wc-arv");
			var vannitubadeArv = document.getElementById("vannitubade-arv");
			var sisustus = document.getElementById("sisustus");
			var muuSanitaarruum = document.getElementById("muu-sanitaarruum");
			var muuNaabruskond = document.getElementById("muu-naabruskond");
			var kook = document.getElementById("kook");
			var koogiPindala = document.getElementById("koogi-pindala");

			if(!checkFieldsRegex([magamistubadeArv, wcArv, vannitubadeArv, sisustus, muuSanitaarruum, muuNaabruskond, kook, koogiPindala])){
				return false;
			};

			errorContainer.innerHTML = "";
			// If all check pass then return true
			return true;

		// 3rd form section
		case 2:
			var muuLisapind = document.getElementById("muu-lisapind");
			var parkimine = document.getElementById("parkimine");
			var parkimiskoht = document.getElementById("parkimiskoht");
			var muudOlemasolevadTeed = document.getElementById("muud-olemasolevad-teed");
			var muuLisad = document.getElementById("muu-lisad");
			var muuSoevesi = document.getElementById("muu-soevesi");
			var veevarustus = document.getElementById("veevarustus");
			var muuVeevarustus = document.getElementById("muu-veevarustus");
			var kanalisatsioon = document.getElementById("kanalisatsioon");
			var muuKanalisatsioon = document.getElementById("muu-kanalisatsioon");
			var muuSide = document.getElementById("muu-side");
			var muuTurvalisus = document.getElementById("muu-turvalisus");
			var muuKuttesusteem = document.getElementById("muu-kuttesusteem");
			var suveKommunaal = document.getElementById("kommunaal-suvi");
			var talveKommunaal = document.getElementById("kommunaal-talv");


			if(!checkFieldsRegex([muuLisapind, parkimine, parkimiskoht, muudOlemasolevadTeed, muuLisad, muuSoevesi, veevarustus, muuVeevarustus, kanalisatsioon, muuKanalisatsioon, muuSide, muuTurvalisus, muuKuttesusteem, suveKommunaal, talveKommunaal])){
				return false;
			};

			errorContainer.innerHTML = "";

			// If all check pass return true
			return true;
	}
}

function checkFieldsRegex(fieldArray) {
	for (let i = 0; i < fieldArray.length; i++) {

		let field = fieldArray[i];
		if (field.value === "" && !field.hasAttribute('required') || field.value === null && !field.hasAttribute('required')) {
			continue;
		}

		if (field.hasAttribute('required')) {
			if (field.value === "" || field.value === null) {
				highlightError(field);
				return false;
			}
		}

		switch (field.type) {
			case "text":
				if (!freeTextPattern.test(field.value)) {
					highlightError(field);
					return false;
				}
				break;
			case "number":
				if (!indexPattern.test(field.value)) {
					highlightError(field);
					return false;
				}
				break;
			case "select-one":
				if(field.value === "") {
					highlightError(field);
					return false;
				}
				break;
			default:
	}
		field.style.border = "initial";
	}
	return true;
}

function highlightError(field) {
	field.style.border = "1px solid red";
}

function displayError(errorMsg) {
	errorContainer.innerHTML = "";
	document.getElementById('error-result').style.display = "inline";
	let textNode = document.createTextNode(errorMsg);
	errorContainer.appendChild(textNode);
}
