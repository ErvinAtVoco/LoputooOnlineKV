<?php

// Tanav maja korter

// Handles form creation
function uus_kuulutus_form()
{
	ob_start(); ?>
	<script src="https://kit.fontawesome.com/e1ff197604.js" crossorigin="anonymous"></script>
	<script>
		window.onload = function() {
			let data = <?php get_json(); ?>

			console.log(data);

			let maakonnaSelect = document.getElementById('maakond');

			// set maakonnad
			for (let i = 0; i < data.length; i++) {
				let option = document.createElement('option');
				option.value = data[i]["Maakond"];
				option.textContent = data[i]["Maakond"];
				maakonnaSelect.appendChild(option);
			}

			// Disable other fields until maakond is picked
			const linnVald = document.getElementById('linn-vald');
			const asula = document.getElementById('asula');
			linnVald.disabled = true;
			linnVald.classList.add("disabled");
			asula.disabled = true;
			asula.classList.add("disabled");

			let linnadVallad;

			// Check if maakond selected
			maakonnaSelect.addEventListener("change", function(e) {
				linnVald.innerHTML = '<option value="" disabled selected>Vali</option>';
				asula.innerHTML = '<option value="" disabled selected>Vali</option>';

				linnadVallad = data.filter(obj => {
					return obj.Maakond === this.value;
				});

				let temp = Object.keys(linnadVallad[0]["linn/vald"]);

				for (let i = 0; i < temp.length; i++) {
					let option = document.createElement('option');
					option.value = temp[i];
					option.textContent = temp[i];
					linnVald.appendChild(option);
				}
				linnVald.disabled = false;
				linnVald.classList.remove("disabled");
			})

			// Check if vald/linn selected
			linnVald.addEventListener("input", function(e) {
				asula.innerHTML = '<option value="" disabled selected>Vali Asula</option>';

				let temp = Object.values(linnadVallad[0]["linn/vald"][this.value]);

				for (let i = 0; i < temp.length; i++) {
					let option = document.createElement('option');
					option.value = temp[i];
					option.textContent = temp[i];
					asula.appendChild(option);
				}
				asula.disabled = false;
				asula.classList.remove("disabled");
			})
		}
	</script>
	<!--------------------------->
	<!----User-register-pop-up--->
	<!--------------------------->
	<div id="kasutaja-loomine" class="kasutaja-loomine">
		<form id="uus-kasutaja-form">
			<?php wp_nonce_field('user_nonce_action', 'user_nonce'); ?>
			<label for="email">Email<span class="text-red">*</span></label>
			<input type="text" id="email" name="email" required />
			<label for="telefon">Telefon<span class="text-red">*</span></label>
			<input type="text" id="telefon" name="telefon" required />
			<label for="nimi">Nimi<span class="text-red">*</span></label>
			<input type="text" id="nimi" name="nimi" required />
			<input type="submit" name="submit" />
		</form>
	</div>

	<!--------------------------->
	<!---Steps to compplete form-->
	<!--------------------------->
	<div class="steps">
		<div class="step">
			<h3 id="1-samm" class="step-indicator-current">
				1
			</h3>
			<h4 class="step-title">
				Üldine
			</h4>
		</div>
		<div class="step">
			<h3 id="2-samm" class="step-indicator">
				2
			</h3>
			<h4 class="step-title">
				Detailid
			</h4>
		</div>
		<div class="step">
			<h3 id="3-samm" class="step-indicator">
				3
			</h3>
			<h4 class="step-title">
				Kirjeldus/Pildid
			</h4>
		</div>
		<div class="step">
			<h3 id="4-samm" class="step-indicator">
				4
			</h3>
			<h4 class="step-title">
				Eelvaade
			</h4>
		</div>
	</div>

	<!--------------------------->
	<!---Start of form-->
	<!--------------------------->
	<form id="uus-kuulutus-form" method="post" enctype="multipart/form-data">

		<div class="form-container" id="first-container">
			<!--------------------------->
			<!---Section for picking sales type -->
			<!--------------------------->
			<div class="form-section">
				<h3>
					Vali tehingu tüüp
				</h3>
				<div class="select-type-container">
					<div class="type">
						<button type="button" onclick="changeType('Üür')" class="marketing-button" id="Üür">
							<h3 class="button-text">Üür</h3>
						</button>
						<button type="button" onclick="changeType('Müük')" class="marketing-button" id="Müük">
							<h3 class="button-text">Müük</h3>
						</button>
						<button type="button" onclick="changeType('Lühiajaline üür')" class="marketing-button" id="Lühiajaline üür">
							<h3 class="button-text">Lühiajaline üür</h3>
						</button>
					</div>
				</div>
			</div>

			<div class="form-section">
				<!--------------------------->
				<!---Section for picking real estate type -->
				<!--------------------------->
				<h3>
					Vali kinnisvara tüüp
				</h3>
				<div class="select-type-container">
					<div class="type">
						<button type="button" onclick="changeRealEstate('Korter')" class="realestate-button" id="Korter">
							<div>
								<i class="fa-solid fa-building"></i>
								<h5>Korter</h5>
							</div>
						</button>
						<button type="button" onclick="changeRealEstate('Maja')" class="realestate-button" id="Maja">
							<div>
								<i class="fa-solid fa-house"></i>
								<h5>Maja</h5>
							</div>
						</button>
						<button type="button" onclick="changeRealEstate('Majaosa')" class="realestate-button" id="Majaosa">
							<div>
								<i class="fa-solid fa-house-crack"></i>
								<h5>Majaosa</h5>
							</div>
						</button>
						<button type="button" onclick="changeRealEstate('Äripind')" class="realestate-button" id="Äripind">
							<div>
								<i class="fa-solid fa-house-laptop"></i>
								<h5>Äripind</h5>
							</div>
						</button>
						<button type="button" onclick="changeRealEstate('Suvila')" class="realestate-button" id="Suvila">
							<div>
								<i class="fa-solid fa-campground"></i>
								<h5>Suvila</h5>
							</div>
						</button>
						<button type="button" onclick="changeRealEstate('Peopind')" class="realestate-button" id="Suvila">
							<div>
								<i class="fa-solid fa-champagne-glasses"></i>
								<h5>Peopind</h5>
							</div>
						</button>
					</div>
				</div>
			</div>

			<!--------------------------->
			<!---Kinnisvara asukoht-->
			<!--------------------------->
			<div class="form-section">
				<h3>
					Kinnisvara asukoht
				</h3>
				<div class="container">
					<div class="left-container">
						<?php wp_nonce_field('my_nonce_action', 'my_nonce_name'); ?>
						<div class="form-object">
							<label for="maakond">Maakond<span class="text-red">*</span></label>
							<select id="maakond" name="maakond" placeholder="" required>
								<option value="" disabled selected>Vali</option>
							</select>
						</div>
						<div class="form-object">
							<label for="linn-vald">Linn/Vald<span class="text-red">&#42;</span></label>
							<select id="linn-vald" name="linn-vald" required>
								<option value="" disabled selected>Vali</option>
							</select>
						</div>
						<div class="form-object">
							<label for="asula">Asula/Linnaosa<span class="text-red">*</span></label>
							<select id="asula" name="asula" required>
								<option value="" disabled selected>Vali</option>
							</select>
						</div>
						<div class="form-object">
							<label for="tänav">Tänav<span class="text-red">*</span></label>
							<input id="tänav" type="text" for="tänav" name="tänav" required />
						</div>
						<div class="form-object-checkbox">
							<input type="checkbox" id="otse-omanikult" name="otse-omanikult[]" value="Jah">
							<label for="otse-omanikult">Otse omanikult</label>
						</div>
					</div>
					<div class="right-container">
						<div class="form-object">
							<label for="maja-nr">Maja nr<span class="text-red">*</span></label>
							<input id="maja-nr" type="number" for="maja-nr" name="maja-nr" maxlength="6" required />
						</div>
						<div class="form-object">
							<label for="korter">Korter nr<span class="text-red">*</span></label>
							<input id="korter" type="number" for="korter" name="korter" maxlength="6" required />
						</div>
						<div class="form-object">
							<label for="postiindeks">Postiindeks</label>
							<input type="number" id="postiindeks" name="postiindeks" maxlength="6" />
						</div>
						<div class="form-object">
							<label for="katastrinumber">Katastrinumber</label>
							<input id="katastrinumber" type="text" for="katastrinumber" name="katastrinumber" />
						</div>
						<div class="form-object">
							<label for="kinnistu-number">Kinnistu nr</label>
							<input id="kinnistu-number" type="text" for="kinnistu-number" name="kinnistu-number" />
						</div>
					</div>
				</div>
			</div>

			<!--------------------------->
			<!---Basic informatsion-->
			<!--------------------------->
			<div class="form-section" style="margin-top: 60px">
				<h3>
					Üldandmed
				</h3>
				<div class="container">
					<div class="left-container">
						<div class="form-object">
							<label for="omandivorm">Omandivorm<span class="text-red">*</span></label>
							<select name="omandivorm" id="omandivorm" required>
								<option value="" selected disabled hidden>Vali</option>
								<option value="Kinnistu">Kinnistu</option>
								<option value="Vallasasi">Vallasasi</option>
								<option value="Kaasomand">Kaasomand</option>
								<option value="Korteriomand">Korteriomand</option>
								<option value="Hoonestusõigus">Hoonestusõigus</option>
								<option value="Üürileping">Üürileping</option>
								<option value="Mõtteline osa">Mõtteline osa</option>
							</select>
						</div>
						<div class="form-object">
							<label for="ehitusaasta">Ehitusaasta</label>
							<input type="number" id="ehitusaasta" name="ehitusaasta" maxlength="4" />
						</div>
						<div class="form-object">
							<label for="seisukord">Seisukord<span class="text-red">*</span></label>
							<select name="seisukord" id="seisukord" required>
								<option value="" selected disabled hidden>Vali</option>
								<option value="Renoveeritud">Renoveeritud</option>
								<option value="Renoveerimata">Renoveerimata</option>
								<option value="Uus">Uus</option>
							</select>
						</div>
						<div class="form-object">
							<label for="pindala">Pindala<span class="text-red">*</span></label>
							<input type="number" id="pindala" name="pindala" required />
						</div>
						<div class="form-object">
							<label for="hind">Hind<span class="text-red">*</span></label>
							<input type="number" id="hind" name="hind" />
						</div>
					</div>
					<div class="right-container">
						<div class="form-object">
							<label for="energiaklass">Energiaklass<span class="text-red">*</span></label>
							<select name="energiaklass" id="energiaklass" required>
								<option value="" selected disabled hidden>Vali</option>
								<option value="Määramata">Määramata</option>
								<option value="A">A</option>
								<option value="B">B</option>
								<option value="C">C</option>
								<option value="D">D</option>
								<option value="E">E</option>
								<option value="F">F</option>
								<option value="G">G</option>
								<option value="H">H</option>
							</select>
						</div>
						<div class="form-object">
							<label for="tubade_arv">Tubade arv<span class="text-red">*</span></label>
							<input type="number" id="tubade-arv" name="tubade-arv" maxlength="2" required />
						</div>
						<div class="form-object">
							<label for="korrus">Korrus<span class="text-red">*</span></label>
							<input type="number" id="korrus" name="korrus" maxlength="2" required />
						</div>
						<div class="form-object">
							<label for="korruseid_kokku">Korruseid kokku</label>
							<input type="number" id="korruseid-kokku" name="korruseid-kokku" maxlength="2" />
						</div>
						<div class="form-object">
							<label style="margin-right: auto" for="ruutmeetri-hind">Ruutmeetri hind</label>
							<p id="ruutmeetri-hind"></p>
						</div>
					</div>
				</div>
			</div>
			<button class="next-button" type="button" onclick="nextForm(); submitCurrentForm(0)">
				Jätka →
			</button>
		</div>

		<!--------------------------->
		<!---Detailed informatsion-->
		<!--------------------------->

		<div id="second-container" class="form-container">
			<div class="form-section">
				<h3>
					Täpsustused
				</h3>
				<div class="container">
					<div class="left-container">
						<h3>Toad</h3>
						<div class="form-object">
							<label for="magamistubade-arv">Magamistubade arv</label>
							<input type="number" id="magamistubade-arv" name="magamistubade-arv" maxlength="2" />
						</div>
						<div class="form-object">
							<label for="wc-arv">WC-de arv</label>
							<input type="number" id="wc-arv" name="wc-arv" maxlength="2" />
						</div>
						<div class="form-object">
							<label for="vannitubade-arv">Vannitubade arv</label>
							<input type="number" id="vannitubade-arv" name="vannitubade-arv" maxlength="2" />
						</div>
						<div class="form-object">
							<label for="sisustus">Sisustus</label>
							<select name="sisustus" id="sisustus">
								<option value="" selected disabled hidden>Vali</option>
								<option value="Sisustatud">Sisustatud</option>
								<option value="Sisustus puudu">Sisustus puudu</option>
							</select>
						</div>
						<h3>Sanitaarruum<h3>
								<div class="form-object-checkbox">
									<input type="checkbox" id="Vann" name="sanitaarruum[]" value="Vann" />
									<label for="Vann">Vann</label>
								</div>
								<div class="form-object-checkbox">
									<input type="checkbox" id="Dušš" name="sanitaarruum[]" value="Dušš" />
									<label for="Dušš">Dušš</label>
								</div>
								<div class="form-object-checkbox">
									<input type="checkbox" id="Pesumasin" name="sanitaarruum[]" value="Pesumasin" />
									<label for="Pesumasin">Pesumasin</label>
								</div>
								<div class="form-object-checkbox">
									<input type="checkbox" id="Saun" name="sanitaarruum[]" value="Saun" />
									<label for="Saun">Saun</label>
								</div>
								<div class="form-object-checkbox">
									<input type="checkbox" id="WC-vannituba-koos" name="WC-vannituba-koos[]" value="Jah" />
									<label for="WC-vannituba-koos">WC ja vannituba koos</label>
								</div>
								<div class="form-object">
									<label for="muu-sanitaarruum">Muu sanitaarruum</label>
									<input type="text" id="muu-sanitaarruum" name="muu-sanitaarruum" />
								</div>
					</div>
					<div class="right-container">
						<h3>Naabruskond</h3>
						<div class="form-object-checkbox">
							<input type="checkbox" id="Väike" name="naabruskond[]" value="Väike" />
							<label for="Väike">Väike</label>
						</div>
						<div class="form-object-checkbox">
							<input type="checkbox" id="Suur" name="naabruskond[]" value="Suur" />
							<label for="Suur">Suur</label>
						</div>
						<div class="form-object-checkbox">
							<input type="checkbox" id="Rahulik" name="naabruskond[]" value="Rahulik" />
							<label for="Rahulik">Rahulik</label>
						</div>
						<div class="form-object-checkbox">
							<input type="checkbox" id="Palju tegevusi" name="naabruskond[]" value="Palju tegevusi" />
							<label for="Palju tegevusi">Palju tegevusi</label>
						</div>
						<div class="form-object">
							<label for="muu-naabruskond">Muu naabruskond</label>
							<input type="text" id="muu-naabruskond" name="muu-naabruskond" />
						</div>
						<h3>Köök</h3>
						<div class="form-object">
							<label for="kook">Köök</label>
							<select name="kook" id="kook">
								<option value="" selected disabled hidden>Vali</option>
								<option value="Koos">Koos</option>
								<option value="Eraldi">Eraldi</option>
								<option value="Puudu">Puudu</option>
							</select>
						</div>
						<div class="form-object">
							<label for="koogi-pindala">Köögi pindala</label>
							<input type="number" id="koogi-pindala" name="koogi-pindala" />
						</div>
					</div>
				</div>
			</div>
			<div class="double-section" style="margin-top: 60px">
				<div class="form-section">
					<h3>
						Lisapinnad ja parkimine
					</h3>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Rõdu" name="lisapinnad[]" value="Rõdu" />
						<label for="Rõdu">Rõdu</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Terrass" name="lisapinnad[]" value="Terrass" />
						<label for="Terrass">Terrass</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Garaaž" name="lisapinnad[]" value="Garaaž" />
						<label for="Garaaž">Garaaž</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Eraldi panipaik" name="lisapinnad[]" value="Eraldi panipaik" />
						<label for="Eraldi panipaik">Eraldi panipaik</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Majaalune kelder" name="lisapinnad[]" value="Majaalune kelder" />
						<label for="Majaalune kelder">Majaalune kelder</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="kõrvalhoone" name="lisapinnad[]" value="Kõrvalhoone" />
						<label for="Kõrvalhoone">Kõrvalhoone</label>
					</div>
					<div class="form-object">
						<label for="muu-lisapind">Muu lisapind</label>
						<input type="text" id="muu-lisapind" name="muu-lisapind" />
					</div>
					<div class="form-object">
						<label for="parkimine">Parkimine</label>
						<select name="parkimine" id="parkimine">
							<option value="" selected disabled hidden>Vali</option>
							<option value="Tastua">Tastua</option>
							<option value="Tasuline">Tasuline</option>
							<option value="Puudub">Puudub</option>
						</select>
					</div>
					<div class="form-object">
						<label for="parkimiskoht">Parkimiskoht</label>
						<input type="text" id="parkimiskoht" name="parkimiskoht" />
					</div>
					<h3>
						Teed
					</h3>
					<div class="form-object">
						<label for="teedeseisukord">Teedeseisukord</label>
						<select name="teedeseisukord" id="teedeseisukord">
							<option value="Täpsustamata">Täpsustamata</option>
							<option value="Hea">Hea</option>
							<option value="Halb">Halb</option>
						</select>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Kõnnitee" name="olemasolevad-teed[]" value="Kõnnitee" />
						<label for="Kõnnitee">Kõnnitee</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Kergliiklustee" name="olemasolevad-teed[]" value="Kergliiklustee" />
						<label for="Kergliiklustee">Kergliiklustee</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Sissesõit" name="olemasolevad-teed[]" value="Sissesõit" />
						<label for="Sissesõit">Sissesõit</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Asfalttee" name="olemasolevad-teed[]" value="Asfalttee" />
						<label for="Asfalttee">Asfalttee</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Kruusatee" name="olemasolevad-teed[]" value="Kruusatee" />
						<label for="Kruusatee">Kruusatee</label>
					</div>
					<div class="form-object">
						<label for="muud-olemasolevad-teed">Muud olemasolevad teed</label>
						<input type="text" id="muud-olemasolevad-teed" name="muud-olemasolevad-teed" />
					</div>
					<h3>
						Lisad
					</h3>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Bassein" name="lisad[]" value="Bassein" />
						<label for="Bassein">Bassein</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Mullivann" name="lisad[]" value="Mullivann" />
						<label for="Mullivann">Mullivann</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Garderoob" name="lisad[]" value="Garderoob" />
						<label for="Garderoob">Garderoob</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Kamin" name="lisad[]" value="Kamin" />
						<label for="Kamin">Kamin</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Lift" name="lisad[]" value="Lift" />
						<label for="Lift">Lift</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Jõusaal" name="lisad[]" value="Jõusaal" />
						<label for="Jõusaal">Jõusaal</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Lemmikloom lubatud" name="lisad[]" value="Lemmikloom lubatud" />
						<label for="Lemmikloom lubatud">Lemmikloomad lubatud</label>
					</div>
					<div class="form-object">
						<label for="muu-lisad">Muud lisad</label>
						<input type="text" id="muu-lisad" name="muu-lisad" />
					</div>
				</div>
				<div class="form-section">
					<h3>
						Tehnosüsteemid
					</h3>
					<h3>
						Soe vesi
					</h3>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Boiler" name="soe-vesi[]" value="Boiler" />
						<label for="Boiler">Boiler</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Soojuspump" name="soe-vesi[]" value="Soojuspump" />
						<label for="Soojuspump">Soojuspump</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Tsentraalne soe vesi" name="soe-vesi[]" value="Tsentraalne soe vesi" />
						<label for="Tsentraalne soe vesi">Tsentraalne soe vesi</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Päiksepaneel" name="soe-vesi[]" value="Päiksepaneel" />
						<label for="Päiksepaneel">Päiksepaneel</label>
					</div>
					<div class="form-object">
						<label for="muu-soevesi">Muu soojaveesüsteem</label>
						<input type="text" id="muu-soevesi" name="muu-soevesi" />
					</div>
					<div class="form-object">
						<label for="veevarustus">Veevarustus</label>
						<select name="veevarustus" id="veevarustus">
							<option value="" selected disabled hidden>Vali</option>
							<option value="tsentraalne vesi">Tsentraalne vesi</option>
							<option value="puurkaev">Puurkaev</option>
							<option value="Salvkaev">Salvkaev</option>
						</select>
					</div>
					<div class="form-object">
						<label for="muu-veevarustus">Muu veevarustus</label>
						<input type="text" id="muu-veevarustus" name="muu-veevarustus" />
					</div>
					<div class="form-object">
						<label for="kanalisatsioon">Kanalisatsioon</label>
						<select name="kanalisatsioon" id="kanalisatsioon">
							<option value="" selected disabled hidden>Vali</option>
							<option value="Tsentraalne kanalisatsioon">Tsentraalne kanalisatsioon</option>
							<option value="Lokaalne">Lokaalne</option>
							<option value="Imbväljak">Imbväljak</option>
							<option value="Mahuti">Mahuti</option>
							<option value="Septik">Septik</option>
							<option value="Biopuhasti">Biopuhasti</option>
						</select>
					</div>
					<div class="form-object">
						<label for="muu-kanalisatsioon">Muu kanalisatsioon</label>
						<input type="text" id="muu-kanalisatsioon" name="muu-kanalisatsioon" />
					</div>
					<h3>
						Side
					</h3>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Internet" name="side[]" value="Internet" />
						<label for="Internet">Internet</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Kaabel" name="side[]" value="Kaabel" />
						<label for="Kaabel">Kaabel</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Telefon" name="side[]" value="Telefon" />
						<label for="Telefon">Telefon</label>
					</div>
					<div class="form-object">
						<label for="muu-side">Muu side</label>
						<input type="text" id="muu-side" name="muu-side" />
					</div>
					<h3>
						Turvalisus
					</h3>
					<div class="form-object-checkbox">
						<input type="checkbox" id="naabrivalve" name="turvalisus[]" value="Naabrivalve" />
						<label for="naabrivalve">Naabrivalve</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="videovalve" name="turvalisus[]" value="Videovalve" />
						<label for="videovalve">Videovalve</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="trepikoda-lukus" name="turvalisus[]" value="Trepikoda lukus" />
						<label for="trepikoda-lukus">Trepikoda lukus</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="turvauks" name="turvalisus[]" value="Turvauks" />
						<label for="turvauks">Turvauks</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="valvur" name="turvalisus[]" value="Valvur" />
						<label for="valvur">Valvur</label>
					</div>
					<div class="form-object">
						<label for="muu-turvalisus">Muu turvalisus</label>
						<input type="text" id="muu-turvalisus" name="muu-turvalisus" />
					</div>
					<h3>
						Küttesüsteemid
					</h3>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Keskküte" name="kuttesusteem[]" value="Keskküte" />
						<label for="Keskküte">Keskküte</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Ahjuküte" name="kuttesusteem[]" value="Ahjuküte" />
						<label for="Ahjuküte">Ahjuküte</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Elektriküte" name="kuttesusteem[]" value="Elektriküte" />
						<label for="Elektriküte">Elektriküte</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Gaasiküte" name="kuttesusteem[]" value="Gaasiküte" />
						<label for="Gaasiküte">Gaasiküte</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Põrandaküte" name="kuttesusteem[]" value="Põrandaküte" />
						<label for="Põrandaküte">Põrandaküte</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Maaküte" name="kuttesusteem[]" value="Maaküte" />
						<label for="maaküte">Maaküte</label>
					</div>
					<div class="form-object-checkbox">
						<input type="checkbox" id="Konditsioneer" name="kuttesusteem[]" value="Konditsioneer" />
						<label for="Konditsioneer">Konditsioneer</label>
					</div>
					<div class="form-object">
						<label for="muu-kuttesusteem">Muu küttesüsteem</label>
						<input type="text" id="muu-kuttesusteem" name="muu-kuttesusteem" />
					</div>
				</div>
			</div>
			<div class="form-section">
				<h3>
					Kommunaalkulud
				</h3>
				<div class="container">
					<div class="left-container">
						<div class="form-object">
							<label for="kommunaal-suvi">Suvel keskmiselt</label>
							<input type="number" id="kommunaal-suvi" name="kommunaal-suvi" />
						</div>
					</div>
					<div class="right-container">
						<div class="form-object">
							<label for="kommunaal-talv">Talvel keskmiselt</label>
							<input type="number" id="kommunaal-talv" name="kommunaal-talv" />
						</div>
					</div>
				</div>
			</div>
			<button class="next-button" type="button" onclick="nextForm(); submitCurrentForm(1)">
				Jätka →
			</button>
			<button type="button" onclick="previousForm()">
				Back
			</button>
		</div>

		<div id="third-container" class="form-container">
			<div class="form-section">
				<h3>
					Kuulutuse sisu
				</h3>
				<div class="form-object-description">
					<textarea class="description-input" name="content" id="content"></textarea>
				</div>
			</div>
			<div class="form-section">
				<h3>
					Reklaamfoto
				</h3>
				<div class="form-object-files">
					<div id="thumbnail-uploaded" class="uploaded-files">
					</div>
					<div id="thumbnail-input-div">
						<input type="file" id="thumbnail-input" name="thumbnail" accept="image/png, image/jpeg" hidden />
					</div>
					<button type="button" id="thumbnail-button" class="upload-button">
						Vali fail
					</button>
				</div>
				<h3>
					Galerii
				</h3>
				<div class="form-object-files">
					<div id="galerii-uploaded" class="uploaded-files">
					</div>
					<div class="galerii-input-div">
						<input type="file" id="galerii-input" name="galerii[]" accept="image/png, image/jpeg" multiple hidden />
					</div>
					<button type="button" id="galerii-button" class="upload-button">
						Vali failid
					</button>
				</div>
				<button class="next-button" type="button" onclick="nextForm(); loadPreview(); submitCurrentForm(2)">
					Jätka →
				</button>
				<button class="next-button" type="button" onclick="previousForm()">
					Back
				</button>
			</div>
		</div>
		<div id="forth-container" class="form-container">
			<?php
			echo do_shortcode('[bricks_template id="1593"]');
			?>
			<button class="next-button" type="button" onclick="previousForm()">
				Back
			</button>
		</div>
	</form>
	<h3 id="error-result" style="color: red; display: none"></h3>

<?php return ob_get_clean();

	if (is_page('test-post-preview')) {
		echo do_shortcode('[uus_kuulutus_form]');
	}
}
add_shortcode("uus_kuulutus_form", "uus_kuulutus_form");
?>