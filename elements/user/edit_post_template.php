<?php

function render_edit_post_template()
{
    $id = $_GET['id'];
    if (get_current_user_id() !== intval(get_post($id)->post_author)) {
        echo ("<script>location.href = 'https://easyweb.ee/kv/'</script>");
        die();
    }
    $title_content = get_post($id);
    $data = get_post_custom($id);
    $maakond = get_the_terms($id, "maakond");
    $linn_vald = get_the_terms($id, "linn");
    $asula_linn = get_the_terms($id, "asulalinnaosa");
    $sanitaarruum = unserialize($data['sanitaarruum'][0]);

    error_log(print_r($data, true));

    $gallery_urls = [];
    $gallery_ids = get_field('galerii', $id);
    foreach ($gallery_ids as $gallery_id) {
        $gallery_urls[] = wp_get_attachment_url($gallery_id);
    };

    ob_start(); ?>
    <script src="https://kit.fontawesome.com/e1ff197604.js" crossorigin="anonymous"></script>
    <script>
        // Places default values into select elements
        function getDefaults(name, location, array) {
            for (let i = 0; i < array.length; i++) {
                let option = document.createElement('option');
                option.value = array[i];
                option.textContent = array[i];
                if (option.value === name) {
                    option.selected = true;
                }
                location.appendChild(option);
            }
        }
        // Appends new children to next select
        function appendChildren(array, location) {
            for (let i = 0; i < array.length; i++) {
                let option = document.createElement('option');
                option.value = array[i];
                option.textContent = array[i];
                location.appendChild(option);
            }
        };
        // Creates the temporary 'vali' option
        function createTempSelectOption(location) {
            let vali = document.createElement('option');
            vali.value = '';
            vali.textContent = 'Vali';
            vali.selected = true;
            vali.disabled = true;
            vali.hidden = true;

            location.appendChild(vali);
        };

        window.onload = function() {
            // Get Json Data
            let data = <?php get_json(); ?>

            // Get maakonna info
            let maakonnaContainer = document.getElementById('maakond');
            let defaultMaakond = '<?php echo $maakond[0]->name ?>';
            let selectdMaakond;

            // Get valla info
            let linnValdContainer = document.getElementById('linn-vald');
            let defaultLinnVald = '<?php echo $linn_vald[0]->name ?>';
            let linnVald;

            // Get asula/linna info
            let asulaContainer = document.getElementById('asula');
            let defaultAsula = '<?php echo $asula_linn[0]->name ?>';

            let innerData;
            let temp;

            // Get gallery info
            let galleryIds = '<?php echo json_encode($gallery_ids) ?>';
            let galleryUrls = '<?php echo json_encode($gallery_urls) ?>';

            // Clear unexpected results from json_encoding
            galleryIds = JSON.parse(galleryIds);
            galleryUrls = JSON.parse(galleryUrls);

            // set maakonnad
            for (let i = 0; i < data.length; i++) {
                let option = document.createElement('option');
                option.value = data[i]["Maakond"];
                option.textContent = data[i]["Maakond"];
                if (option.value === defaultMaakond) {
                    option.selected = true;
                }
                maakonnaContainer.appendChild(option);
            }

            // Filter by maakond
            innerData = data.filter(obj => {
                return obj.Maakond === defaultMaakond;
            });

            // set vald
            temp = Object.keys(innerData[0]["linn/vald"]);
            getDefaults(defaultLinnVald, linnValdContainer, temp);

            // set asula
            temp = Object.values(innerData[0]["linn/vald"][defaultLinnVald]);
            getDefaults(defaultAsula, asulaContainer, temp);

            // Check if maakond selected
            maakonnaContainer.addEventListener("change", function(e) {
                linnValdContainer.innerHTML = '';
                asulaContainer.innerHTML = '';
                createTempSelectOption(linnValdContainer);
                innerData = data.filter(obj => {
                    return obj.Maakond === this.value;
                });
                let temp = Object.keys(innerData[0]["linn/vald"]);
                appendChildren(temp, linnValdContainer)
            })

            // Check if vald/linn selected
            linnValdContainer.addEventListener("input", function(e) {
                asulaContainer.innerHTML = '';
                createTempSelectOption(asulaContainer);
                let temp = Object.values(innerData[0]["linn/vald"][this.value]);
                appendChildren(temp, asulaContainer)
            })

            // Send gallery data to js
            transferGalleryInfo(galleryIds, galleryUrls);
        }
    </script>

    <form id="uus-kuulutus-form" method="post" enctype="multipart/form-data">
        <div class="form-section">
            <h3>
                Kinnisvara asukoht
            </h3>
            <div class="container">
                <div class="left-container">
                    <div class="form-object">
                        <label for="maakond">Maakond<span class="text-red">*</span></label>
                        <select id="maakond" name="maakond" placeholder="" required>
                        </select>
                    </div>
                    <div class="form-object">
                        <label for="linn-vald">Linn/Vald<span class="text-red">&#42;</span></label>
                        <select id="linn-vald" name="linn-vald" required>
                        </select>
                    </div>
                    <div class="form-object">
                        <label for="asula">Asula/Linnaosa<span class="text-red">*</span></label>
                        <select id="asula" name="asula" required>
                        </select>
                    </div>
                    <div class="form-object">
                        <label for="tänav">Tänav<span class="text-red">*</span></label>
                        <input value='<?php $data['tanav'][0] ?>' id="tänav" type="text" for="tänav" name="tänav" required />
                    </div>
                    <div class="form-object-checkbox">
                        <input type="checkbox" id="otse-omanikult" name="otse-omanikult[]" value="Jah" <?php echo unserialize($data['otse_omanikult'][0])[0] === 'Jah' ? 'checked' : '' ?>>
                        <label for="otse-omanikult">Otse omanikult</label>
                    </div>
                </div>
                <div class="right-container">
                    <div class="form-object">
                        <label for="maja-nr">Maja nr<span class="text-red">*</span></label>
                        <input value='<?php echo $data['maja_nr'][0] ?>' id="maja-nr" type="number" for="maja-nr" name="maja-nr" maxlength="6" required />
                    </div>
                    <div class="form-object">
                        <label for="korter">Korter nr<span class="text-red">*</span></label>
                        <input value='<?php echo $data['korter'][0] ?>' id="korter" type="number" for="korter" name="korter" maxlength="6" required />
                    </div>
                    <div class="form-object">
                        <label for="postiindeks">Postiindeks</label>
                        <input value='<?php echo $data['postiindeks'][0] ?>' type="number" id="postiindeks" name="postiindeks" maxlength="6" />
                    </div>
                    <div class="form-object">
                        <label for="katastrinumber">Katastrinumber</label>
                        <input value='<?php echo $data['katastrinumber'][0] ?>' id="katastrinumber" type="text" for="katastrinumber" name="katastrinumber" />
                    </div>
                    <div class="form-object">
                        <label for="kinnistu-number">Kinnistu nr</label>
                        <input value='<?php echo $data['kinnistu_number'][0] ?>' id="kinnistu-number" type="text" for="kinnistu-number" name="kinnistu-number" />
                    </div>
                </div>
            </div>
        </div>
        <!---Üldine informatsioon---->
        <div class="form-section" style="margin-top: 60px">
            <h3>
                Üldandmed
            </h3>
            <div class="container">
                <div class="left-container">
                    <div class="form-object">
                        <label for="omandivorm">Omandivorm<span class="text-red">*</span></label>
                        <select name="omandivorm" id="omandivorm" required>
                            <option <?php echo $data['omandivorm'][0] === "Kinnistu" ? 'selected' : ''; ?> value="Kinnistu">Kinnistu</option>
                            <option <?php echo $data['omandivorm'][0] === "Vallasasi" ? 'selected' : ''; ?> value="Vallasasi">Vallasasi</option>
                            <option <?php echo $data['omandivorm'][0] === "Kaasomand" ? 'selected' : ''; ?> value="Kaasomand">Kaasomand</option>
                            <option <?php echo $data['omandivorm'][0] === "Korteriomand" ? 'selected' : ''; ?> value="Korteriomand">Korteriomand</option>
                            <option <?php echo $data['omandivorm'][0] === "Hoonestusõigus" ? 'selected' : ''; ?> value="Hoonestusõigus">Hoonestusõigus</option>
                            <option <?php echo $data['omandivorm'][0] === "Üürileping" ? 'selected' : ''; ?> value="Üürileping">Üürileping</option>
                            <option <?php echo $data['omandivorm'][0] === "Mõtteline osa" ? 'selected' : ''; ?> value="Mõtteline osa">Mõtteline osa</option>
                        </select>
                    </div>
                    <div class="form-object">
                        <label for="ehitusaasta">Ehitusaasta</label>
                        <input value='<?php echo $data['ehitusaasta'][0] ?>' type="number" id="ehitusaasta" name="ehitusaasta" maxlength="4" />
                    </div>
                    <div class="form-object">
                        <label for="seisukord">Seisukord<span class="text-red">*</span></label>
                        <select name="seisukord" id="seisukord" required>
                            <option <?php echo $data['seisukord'][0] === "Renoveeritud" ? 'selected' : ''; ?> value="Renoveeritud">Renoveeritud</option>
                            <option <?php echo $data['energiaklass'][0] === "Renoveerimata" ? 'selected' : ''; ?> value="Renoveerimata">Renoveerimata</option>
                            <option <?php echo $data['energiaklass'][0] === "Uus" ? 'selected' : ''; ?> value="Uus">Uus</option>
                        </select>
                    </div>
                    <div class="form-object">
                        <label for="pindala">Pindala<span class="text-red">*</span></label>
                        <input value='<?php echo $data['pindala'][0] ?>' type="number" id="pindala" name="pindala" required />
                    </div>
                    <div class="form-object">
                        <label for="hind">Hind<span class="text-red">*</span></label>
                        <input value='<?php echo $data['hind'][0] ?>' type="number" id="hind" name="hind" />
                    </div>
                </div>
                <div class="right-container">
                    <div class="form-object">
                        <label for="energiaklass">Energiaklass<span class="text-red">*</span></label>
                        <select name="energiaklass" id="energiaklass" required>
                            <option <?php echo $data['energiaklass'][0] === "Määramata" ? 'selected' : ''; ?> value="Määramata">Määramata</option>
                            <option <?php echo $data['energiaklass'][0] === "A" ? 'selected' : ''; ?> value="A">A</option>
                            <option <?php echo $data['energiaklass'][0] === "B" ? 'selected' : ''; ?> value="B">B</option>
                            <option <?php echo $data['energiaklass'][0] === "C" ? 'selected' : ''; ?> value="C">C</option>
                            <option <?php echo $data['energiaklass'][0] === "D" ? 'selected' : ''; ?> value="D">D</option>
                            <option <?php echo $data['energiaklass'][0] === "E" ? 'selected' : ''; ?> value="E">E</option>
                            <option <?php echo $data['energiaklass'][0] === "F" ? 'selected' : ''; ?> value="F">F</option>
                            <option <?php echo $data['energiaklass'][0] === "G" ? 'selected' : ''; ?> value="G">G</option>
                            <option <?php echo $data['energiaklass'][0] === "H" ? 'selected' : ''; ?> value="H">H</option>
                        </select>
                    </div>
                    <div class="form-object">
                        <label for="tubade_arv">Tubade arv<span class="text-red">*</span></label>
                        <input value='<?php echo $data['tubade_arv'][0] ?>' type="number" id="tubade-arv" name="tubade-arv" maxlength="2" required />
                    </div>
                    <div class="form-object">
                        <label for="korrus">Korrus<span class="text-red">*</span></label>
                        <input value='<?php echo $data['korrus'][0] ?>' type="number" id="korrus" name="korrus" maxlength="2" required />
                    </div>
                    <div class="form-object">
                        <label for="korruseid_kokku">Korruseid kokku</label>
                        <input value='<?php echo $data['korruseid_kokku'][0] ?>' type="number" id="korruseid-kokku" name="korruseid-kokku" maxlength="2" />
                    </div>
                    <div class="form-object">
                        <label style="margin-right: auto" for="ruutmeetri-hind">Ruutmeetri hind</label>
                        <p id="ruutmeetri-hind"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>
                Täpsustused
            </h3>
            <div class="container">
                <div class="left-container">
                    <h3>Toad</h3>
                    <div class="form-object">
                        <label for="magamistubade-arv">Magamistubade arv</label>
                        <input value='<?php echo $data['magamistubade_arv'][0] ?>' type="number" id="magamistubade-arv" name="magamistubade-arv" maxlength="2" />
                    </div>
                    <div class="form-object">
                        <label for="wc-arv">WC-de arv</label>
                        <input value='<?php echo $data['wc_arv'][0] ?>' type="number" id="wc-arv" name="wc-arv" maxlength="2" />
                    </div>
                    <div class="form-object">
                        <label for="vannitubade-arv">Vannitubade arv</label>
                        <input value='<?php echo $data['vannitubade_arv'][0] ?>' type="number" id="vannitubade-arv" name="vannitubade-arv" maxlength="2" />
                    </div>
                    <div class="form-object">
                        <label for="sisustus">Sisustus</label>
                        <select name="sisustus" id="sisustus">
                            <option <?php echo $data['sisustus'][0] === "Sisustatud" ? 'selected' : ''; ?> value="Sisustatud">Sisustatud</option>
                            <option <?php echo $data['sisustus'][0] === "Sisustus puudu" ? 'selected' : ''; ?> value="Sisustus puudu">Sisustus puudu</option>
                        </select>
                    </div>

                    <h3>Sanitaarruum</h3>
                    <div class="form-object-checkbox">
                        <input type="checkbox" id="Vann" name="sanitaarruum[]" value="Vann" <?php echo in_array("Vann", $sanitaarruum) ? 'checked' : ''; ?> />
                        <label for="Vann">Vann</label>
                    </div>
                    <div class="form-object-checkbox">
                        <input type="checkbox" id="Dušš" name="sanitaarruum[]" value="Dušš" <?php echo in_array("Dušš", $sanitaarruum) ? 'checked' : ''; ?> />
                        <label for="Dušš">Dušš</label>
                    </div>
                    <div class="form-object-checkbox">
                        <input type="checkbox" id="Pesumasin" name="sanitaarruum[]" value="Pesumasin" <?php echo in_array("Pesumasin", $sanitaarruum) ? 'checked' : ''; ?> />
                        <label for="Pesumasin">Pesumasin</label>
                    </div>
                    <div class="form-object-checkbox">
                        <input type="checkbox" id="Saun" name="sanitaarruum[]" value="Saun" <?php echo in_array("Saun", $sanitaarruum) ? 'checked' : ''; ?> />
                        <label for="Saun">Saun</label>
                    </div>
                    <div class="form-object-checkbox">
                        <input type="checkbox" id="WC-vannituba-koos" name="WC-vannituba-koos[]" value="Jah" <?php echo unserialize($data['wc_ja_vannituba_koos'][0])[0] === 'Jah' ? 'checked' : '' ?> />
                        <label for="WC-vannituba-koos">WC ja vannituba koos</label>
                    </div>
                    <div class="form-object">
                        <label for="muu-sanitaarruum">Muu sanitaarruum</label>
                        <input type="text" id="muu-sanitaarruum" name="muu-sanitaarruum" value='<?php echo $data['muu_sanitaarruum'][0] ?>' />
                    </div>
                </div>

                <div class="right-container">
                    <h3>Naabruskond</h3>
                    <div class="form-object-checkbox">
                        <input type="checkbox" id="Väike" name="naabruskond[]" value="Väike" <?php echo in_array("Väike", unserialize($data['naabruskond'][0])) ? 'checked' : ''; ?> />
                        <label for="Väike">Väike</label>
                    </div>
                    <div class="form-object-checkbox">
                        <input type="checkbox" id="Suur" name="naabruskond[]" value="Suur" <?php echo in_array("Suur", unserialize($data['naabruskond'][0])) ? 'checked' : ''; ?> />
                        <label for="Suur">Suur</label>
                    </div>
                    <div class="form-object-checkbox">
                        <input type="checkbox" id="Rahulik" name="naabruskond[]" value="Rahulik" <?php echo in_array("Rahulik", unserialize($data['naabruskond'][0])) ? 'checked' : ''; ?> />
                        <label for="Rahulik">Rahulik</label>
                    </div>
                    <div class="form-object-checkbox">
                        <input type="checkbox" id="Palju tegevusi" name="naabruskond[]" value="Palju tegevusi" <?php echo in_array("Palju tegevusi", unserialize($data['naabruskond'][0])) ? 'checked' : ''; ?> />
                        <label for="Palju tegevusi">Palju tegevusi</label>
                    </div>
                    <div class="form-object">
                        <label for="muu-naabruskond">Muu naabruskond</label>
                        <input type="text" id="muu-naabruskond" name="muu-naabruskond" value='<?php echo $data['naabruskond_muu'][0] ?>'>
                    </div>
                    <h3>Köök</h3>
                    <div class="form-object">
                        <label for="kook">Köök</label>
                        <select name="kook" id="kook">
                            <option <?php echo $data['kook'][0] === "Koos" ? 'selected' : ''; ?> value="Koos">Koos</option>
                            <option <?php echo $data['kook'][0] === "Eraldi" ? 'selected' : ''; ?> value="Eraldi">Eraldi</option>
                            <option <?php echo $data['kook'][0] === "Puudu" ? 'selected' : ''; ?> value="Puudu">Puudu</option>
                        </select>
                    </div>
                    <div class="form-object">
                        <label for="koogi-pindala">Köögi pindala</label>
                        <input value='<?php echo $data['koogi_pindala'][0] ?>' type="number" id="koogi-pindala" name="koogi-pindala" />
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
                    <input type="checkbox" id="rõdu" name="lisapinnad[]" value="rõdu" <?php echo in_array("rõdu", unserialize($data['lisapinnad'][0])) ? 'checked' : ''; ?> />
                    <label for="rõdu">Rõdu</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="terrass" name="lisapinnad[]" value="terrass" <?php echo in_array("terrass", unserialize($data['lisapinnad'][0])) ? 'checked' : ''; ?> />
                    <label for="terrass">Terrass</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="garaaž" name="lisapinnad[]" value="garaaž" <?php echo in_array("garaaž", unserialize($data['lisapinnad'][0])) ? 'checked' : ''; ?> />
                    <label for="garaaž">Garaaž</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="eraldi panipaik" name="lisapinnad[]" value="eraldi panipaik" <?php echo in_array("eraldi panipaik", unserialize($data['lisapinnad'][0])) ? 'checked' : ''; ?> />
                    <label for="eraldi panipaik">Eraldi panipaik</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="majaalune kelder" name="lisapinnad[]" value="majaalune kelder" <?php echo in_array("majaalune kelder", unserialize($data['lisapinnad'][0])) ? 'checked' : ''; ?> />
                    <label for="majaalune kelder">Majaalune kelder</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="kõrvalhoone" name="lisapinnad[]" value="kõrvalhoone" <?php echo in_array("kõrvalhoone", unserialize($data['lisapinnad'][0])) ? 'checked' : ''; ?> />
                    <label for="kõrvalhoone">Kõrvalhoone</label>
                </div>
                <div class="form-object">
                    <label for="muu-lisapind">Muu lisapind</label>
                    <input type="text" id="muu-lisapind" name="muu-lisapind" value='<?php echo $data['muu_lisapind'][0] ?>' />
                </div>
                <div class="form-object">
                    <label for="parkimine">Parkimine</label>
                    <select name="parkimine" id="parkimine">
                        <option <?php echo $data['parkimine'][0] === "Tastua" ? 'selected' : ''; ?> value="Tastua">Tastua</option>
                        <option <?php echo $data['teedeseisukord'][0] === "Tasuline" ? 'selected' : ''; ?> value="Tasuline">Tasuline</option>
                        <option <?php echo $data['teedeseisukord'][0] === "Puudub" ? 'selected' : ''; ?> value="Puudub">Puudub</option>
                    </select>
                </div>
                <div class="form-object">
                    <label for="parkimiskoht">Parkimiskoht</label>
                    <input type="text" id="parkimiskoht" name="parkimiskoht" value='<?php echo $data['parkimiskoht'][0] ?>' />
                </div>
                <h3>
                    Teed
                </h3>
                <div class="form-object">
                    <label for="teedeseisukord">Teedeseisukord</label>
                    <select name="teedeseisukord" id="teedeseisukord">
                        <option <?php echo $data['teedeseisukord'][0] === "Täpsustamata" ? 'selected' : ''; ?> value="Täpsustamata">Täpsustamata</option>
                        <option <?php echo $data['teedeseisukord'][0] === "Hea" ? 'selected' : ''; ?> value="Hea">Hea</option>
                        <option <?php echo $data['teedeseisukord'][0] === "Halb" ? 'selected' : ''; ?> value="Halb">Halb</option>
                    </select>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="Kõnnitee" name="olemasolevad-teed[]" value="Kõnnitee" <?php echo in_array("Kõnnitee", unserialize($data['olemasolevad_teed'][0])) ? 'checked' : ''; ?> />
                    <label for="Kõnnitee">Kõnnitee</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="Kergliiklustee" name="olemasolevad-teed[]" value="Kergliiklustee" <?php echo in_array("Kergliiklustee", unserialize($data['olemasolevad_teed'][0])) ? 'checked' : ''; ?> />
                    <label for="Kergliiklustee">Kergliiklustee</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="Sissesõit" name="olemasolevad-teed[]" value="Sissesõit" <?php echo in_array("Sissesõit", unserialize($data['olemasolevad_teed'][0])) ? 'checked' : ''; ?> />
                    <label for="Sissesõit">Sissesõit</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="Asfalttee" name="olemasolevad-teed[]" value="Asfalttee" <?php echo in_array("Asfalttee", unserialize($data['olemasolevad_teed'][0])) ? 'checked' : ''; ?> />
                    <label for="Asfalttee">Asfalttee</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="Kruusatee" name="olemasolevad-teed[]" value="Kruusatee" <?php echo in_array("Kruusatee", unserialize($data['olemasolevad_teed'][0])) ? 'checked' : ''; ?> />
                    <label for="Kruusatee">Kruusatee</label>
                </div>
                <div class="form-object">
                    <label for="muud-olemasolevad-teed">Muud olemasolevad teed</label>
                    <input type="text" id="muud-olemasolevad-teed" name="muud-olemasolevad-teed" value='<?php echo $data['muud_olemasolevad_teed'][0] ?>' />
                </div>
                <h3>
                    Lisad
                </h3>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="bassein" name="lisad[]" value="Bassein" <?php echo in_array("Bassein", unserialize($data['lisad'][0])) ? 'checked' : ''; ?> />
                    <label for="bassein">Bassein</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="lift" name="lisad[]" value="Lift" <?php echo in_array("Lift", unserialize($data['lisad'][0])) ? 'checked' : ''; ?> />
                    <label for="lift">Lift</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="mullivann" name="lisad[]" value="Mullivann" <?php echo in_array("Mullivann", unserialize($data['lisad'][0])) ? 'checked' : ''; ?> />
                    <label for="mullivann">Mullivann</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="garderoob" name="lisad[]" value="Garderoob" <?php echo in_array("Garderoob", unserialize($data['lisad'][0])) ? 'checked' : ''; ?> />
                    <label for="garderoob">Garderoob</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="kamin" name="lisad[]" value="Kamin" <?php echo in_array("Kamin", unserialize($data['lisad'][0])) ? 'checked' : ''; ?> />
                    <label for="kamin">Kamin</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="jõusaal" name="lisad[]" value="Jõusaal" <?php echo in_array("Jõusaal", unserialize($data['lisad'][0])) ? 'checked' : ''; ?> />
                    <label for="jõusaal">Jõusaal</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="lemmikloom" name="lisad[]" value="Lemmikloom lubatud" <?php echo in_array("Lemmikloom lubatud", unserialize($data['lisad'][0])) ? 'checked' : ''; ?> />
                    <label for="Lemmikloom">Lemmikloomad lubatud</label>
                </div>
                <div class="form-object">
                    <label for="muu-lisad">Muud lisad</label>
                    <input type="text" id="muu-lisad" name="muu-lisad" value='<?php echo $data['muu_lisad'][0] ?>' />
                </div>
            </div>
            <div class="form-section">
                <h3>
                    Tehnosüsteemid
                </h3>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="Tsentraalne soe vesi" name="soe-vesi[]" value="Tsentraalne soe vesi" <?php echo in_array("Tsentraalne soe vesi", unserialize($data['soe_vesi'][0])) ? 'checked' : ''; ?> />
                    <label for="tsentraalne soe vesi">Tsentraalne soe vesi</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="boiler" name="soe-vesi[]" value="Boiler" <?php echo in_array("Boiler", unserialize($data['soe_vesi'][0])) ? 'checked' : ''; ?> />
                    <label for="boiler">Boiler</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="soojuspump" name="soe-vesi[]" value="Soojuspump" <?php echo in_array("Soojuspump", unserialize($data['soe_vesi'][0])) ? 'checked' : ''; ?> />
                    <label for="soojuspump">Soojuspump</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="päiksepaneel" name="soe-vesi[]" value="Päiksepaneel" <?php echo in_array("Päiksepaneel", unserialize($data['soe_vesi'][0])) ? 'checked' : ''; ?> />
                    <label for="päiksepaneel">Päiksepaneel</label>
                </div>
                <div class="form-object">
                    <label for="muu-soevesi">Muu soojaveesüsteem</label>
                    <input type="text" id="muu-soevesi" name="muu-soevesi" value='<?php echo $data['muu_soevesi'][0] ?>' />
                </div>
                <div class="form-object">
                    <label for="veevarustus">Veevarustus</label>
                    <select name="veevarustus" id="veevarustus">
                        <option <?php echo $data['veevarustus'][0] === "Tsentraalne vesi" ? 'selected' : ''; ?> value="Tsentraalne vesi">Tsentraalne vesi</option>
                        <option <?php echo $data['veevarustus'][0] === "Puurkaev" ? 'selected' : ''; ?> value="Puurkaev">Puurkaev</option>
                        <option <?php echo $data['veevarustus'][0] === "Salvkaev" ? 'selected' : ''; ?> value="Salvkaev">Salvkaev</option>
                    </select>
                </div>
                <div class="form-object">
                    <label for="muu-veevarustus">Muu veevarustus</label>
                    <input type="text" id="muu-veevarustus" name="muu-veevarustus" value='<?php echo $data['muu_veevarustus'][0] ?>' />
                </div>
                <div class="form-object">
                    <label for="kanalisatsioon">Kanalisatsioon</label>
                    <select name="kanalisatsioon" id="kanalisatsioon">
                        <option <?php echo $data['kanalisatsioon'][0] === "Tsentraalne kanalisatsioon" ? 'selected' : ''; ?> value="Tsentraalne kanalisatsioon">Tsentraalne kanalisatsioon</option>
                        <option <?php echo $data['kanalisatsioon'][0] === "Lokaalne" ? 'selected' : ''; ?> value="Lokaalne">Lokaalne</option>
                        <option <?php echo $data['kanalisatsioon'][0] === "Imbväljak" ? 'selected' : ''; ?> value="Imbväljak">Imbväljak</option>
                        <option <?php echo $data['kanalisatsioon'][0] === "Mahuti" ? 'selected' : ''; ?> value="Mahuti">Mahuti</option>
                        <option <?php echo $data['kanalisatsioon'][0] === "Septik" ? 'selected' : ''; ?> value="Septik">Septik</option>
                        <option <?php echo $data['kanalisatsioon'][0] === "Biopuhasti" ? 'selected' : ''; ?> value="Biopuhasti">Biopuhasti</option>
                    </select>
                </div>
                <div class="form-object">
                    <label for="muu-kanalisatsioon">Muu kanalisatsioon</label>
                    <input type="text" id="muu-kanalisatsioon" name="muu-kanalisatsioon" value='<?php echo $data['muu-kanalisatsioon'][0] ?>' />
                </div>
                <h3>
                    Side
                </h3>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="Internet" name="side[]" value="Internet" <?php echo in_array("Internet", unserialize($data['side'][0])) ? 'checked' : ''; ?> />
                    <label for="Internet">Internet</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="Kaabel" name="side[]" value="Kaabel" <?php echo in_array("Kaabel", unserialize($data['side'][0])) ? 'checked' : ''; ?> />
                    <label for="Kaabel">Kaabel</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="Telefon" name="side[]" value="Telefon" <?php echo in_array("Telefon", unserialize($data['side'][0])) ? 'checked' : ''; ?> />
                    <label for="Telefon">Telefon</label>
                </div>
                <div class="form-object">
                    <label for="muu-side">Muu side</label>
                    <input type="text" id="muu-side" name="muu-side" value='<?php echo $data['muu_side'][0] ?>' />
                </div>
                <h3>
                    Turvalisus
                </h3>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="naabrivalve" name="turvalisus[]" value="Naabrivalve" <?php echo in_array("Naabrivalve", unserialize($data['turvalisus'][0])) ? 'checked' : ''; ?> />
                    <label for="naabrivalve">Naabrivalve</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="videovalve" name="turvalisus[]" value="Videovalve" <?php echo in_array("Videovalve", unserialize($data['turvalisus'][0])) ? 'checked' : ''; ?> />
                    <label for="videovalve">Videovalve</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="trepikoda-lukus" name="turvalisus[]" value="Trepikoda lukus" <?php echo in_array("Trepikoda lukus", unserialize($data['turvalisus'][0])) ? 'checked' : ''; ?> />
                    <label for="trepikoda-lukus">Trepikoda lukus</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="turvauks" name="turvalisus[]" value="Turvauks" <?php echo in_array("Turvauks", unserialize($data['turvalisus'][0])) ? 'checked' : ''; ?> />
                    <label for="turvauks">Turvauks</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="valvur" name="turvalisus[]" value="Valvur" <?php echo in_array("Valvur", unserialize($data['turvalisus'][0])) ? 'checked' : ''; ?> />
                    <label for="valvur">Valvur</label>
                </div>
                <div class="form-object">
                    <label for="muu-turvalisus">Muu turvalisus</label>
                    <input type="text" id="muu-turvalisus" name="muu-turvalisus" value='<?php echo $data['muu_turvalisus'][0] ?>' />
                </div>
                <h3>
                    Küttesüsteemid
                </h3>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="keskküte" name="kuttesusteem[]" value="Keskküte" <?php echo in_array("Keskküte", unserialize($data['kuttesusteem'][0])) ? 'checked' : ''; ?> />
                    <label for="keskküte">Keskküte</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="ahjuküte" name="kuttesusteem[]" value="Ahjuküte" <?php echo in_array("Ahjuküte", unserialize($data['kuttesusteem'][0])) ? 'checked' : ''; ?> />
                    <label for="ahjuküte">Ahjuküte</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="elektriküte" name="kuttesusteem[]" value="Elektriküte" <?php echo in_array("Elektriküte", unserialize($data['kuttesusteem'][0])) ? 'checked' : ''; ?> />
                    <label for="elektriküte">Elektriküte</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="gaasiküte" name="kuttesusteem[]" value="Gaasiküte" <?php echo in_array("Gaasiküte", unserialize($data['kuttesusteem'][0])) ? 'checked' : ''; ?> />
                    <label for="gaasiküte">Gaasiküte</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="põrandaküte" name="kuttesusteem[]" value="Põrandaküte" <?php echo in_array("Põrandaküte", unserialize($data['kuttesusteem'][0])) ? 'checked' : ''; ?> />
                    <label for="põrandaküte">Põrandaküte</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="maaküte" name="kuttesusteem[]" value="Maaküte" <?php echo in_array("Maaküte", unserialize($data['kuttesusteem'][0])) ? 'checked' : ''; ?> />
                    <label for="maaküte">Maaküte</label>
                </div>
                <div class="form-object-checkbox">
                    <input type="checkbox" id="konditsioneer" name="kuttesusteem[]" value="Konditsioneer" <?php echo in_array("Konditsioneer", unserialize($data['kuttesusteem'][0])) ? 'checked' : ''; ?> />
                    <label for="konditsioneer">Konditsioneer</label>
                </div>
                <div class="form-object">
                    <label for="muu-kuttesusteem">Muu küttesüsteem</label>
                    <input type="text" id="muu-kuttesusteem" name="muu-kuttesusteem" value='<?php echo $data['muu_kuttesusteem'][0] ?>' />
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
                        <input type="number" id="kommunaal-suvi" name="kommunaal-suvi" value='<?php echo $data['komunaal_suvi'][0] ?>' />
                    </div>
                </div>
                <div class="right-container">
                    <div class="form-object">
                        <label for="kommunaal-talv">Talvel keskmiselt</label>
                        <input type="number" id="kommunaal-talv" name="kommunaal-talv" value='<?php echo $data['komunaal_talv'][0] ?>' />
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>
                Kuulutuse pealkiri
            </h3>
            <div class="form-object">
                <input class="title-input" name="title" id="title" value='<?php echo $title_content->post_title ?>' />
            </div>
        </div>
        <div class="form-section">
            <h3>
                Kuulutuse sisu
            </h3>
            <div class="form-object-description">
                <textarea class="description-input" name="content" id="content"><?php echo $title_content->post_content ?></textarea>
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
        </div>
        <div class="form-section">
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
        </div>
        <input type="submit" name="submit" />
    </form>
    <h3 id="error-result" style="color: red; display: none"></h3>


<?php return ob_get_clean();

    if (is_page('edit-post')) {
        echo do_shortcode('[edit-post]');
    }
}
add_shortcode('edit-post', 'render_edit_post_template');
