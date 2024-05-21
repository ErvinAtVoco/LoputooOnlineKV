<?php
function filter_form()
{
    ob_start() ?>

    <script src="https://kit.fontawesome.com/e1ff197604.js" crossorigin="anonymous"></script>
    <script>
        window.onload = function() {
            let data = <?php get_json(); ?>

            console.log(data);

            let maakonnaSelect = document.getElementById('maakond');

            // set maakonnad
            for (let i = 0; i < data.length; i++) {
                let button = document.createElement('button');
                button.textContent = data[i]["Maakond"];
                button.classList = "filter-button";
                button.type = "button";
                maakonnaSelect.appendChild(button);
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
                asula.innerHTML = '';
                linnadVallad = data.filter(obj => {
                    return obj.Maakond === this.value;
                });

                let temp = Object.keys(linnadVallad[0]["linn/vald"]);

                for (let i = 0; i < temp.length; i++) {
                    let button = document.createElement('button');
                    button.textContent = temp[i];
                    button.classList = "filter-button";
                    button.type = "button";
                    linnVald.appendChild(button);
                }
                linnVald.disabled = false;
                linnVald.classList.remove("disabled");
            })

            // Check if vald/linn selected
            linnVald.addEventListener("input", function(e) {
                let temp = Object.values(linnadVallad[0]["linn/vald"][this.value]);
                asula.innerHTML = '';

                for (let i = 0; i < temp.length; i++) {
                    let container = document.createElement('div');
                    container.className = 'asula';
                    let label = document.createElement('label');
                    label.textContent = temp[i];
                    container.append(label);
                    let checkbox = document.createElement('input');
                    checkbox.type = "checkbox";
                    checkbox.value = temp[i];
                    container.appendChild(checkbox);
                    asula.append(container);
                }
                asula.disabled = false;
                asula.classList.remove("disabled");
            })
        }
    </script>

    <form id="filter-form" class="filter" method="post" enctype="multipart/form-data">

        <div class="filter-header">
            <!--Searchbar container-->
            <div class="search-bar-container">
                <div class="icon-container">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" placeholder="Märksõnad..." />
            </div>
        </div>

        <div class="button-container">
            <div class="top-button-parent-marketing">
                <h5>Tehingutüüp</h5>
                <div class="button-object">
                    <button type="button" class="filter-button">Üür</button>
                    <button type="button" class="filter-button">Müük</button>
                    <button type="button" class="filter-button">Lühi. üür</button>
                </div>
            </div>
            <div class="vertical-line"></div>
            <div class="top-button-parent-realestate">
                <h5>Hoonetüüp</h5>
                <div class="button-object">
                    <button type="button" class="filter-button">Korter</button>
                    <button type="button" class="filter-button">Maja</button>
                    <button type="button" class="filter-button">Majaosa</button>
                    <button type="button" class="filter-button">Äripind</button>
                    <button type="button" class="filter-button">Suvila</button>
                    <button type="button" class="filter-button">Peopind</button>
                </div>
            </div>
        </div>

        <hr />

        <div class="filter-segment">
            <div class="filter-object">
                <h5>Maakond</h5>
                <div id="maakond" class="location">
                 
                </div>
            </div>
            <div class="vertical-line"></div>
            <div class="filter-object">
                <h5>Linn/Vald</h5>
                <div id="linn-vald" class="location">
                    
                </div>
            </div>
            <div class="vertical-line"></div>
            <h5>Asula</h5>
            <div class="filter-object">
                <div id="asula" class="asula-container">

                </div>
            </div>
        </div>

        <!--Filter header-->
        <div class="filter-header">

            <div class="flex-display" style="margin-bottom:33px;">
            </div>

            <div class="flex-display" style="margin-bottom:33px;">
                <div class="location-container">
                    <!--Location-->



                </div>
                <div class="basic-container">
                    <!--Rooms-->
                    <div class="form-object">
                        <h4>Tube</h4>
                        <input type="number" style="margin-right: 20px" id="tube-alates" name="tube-alates" placeholder="Alates" />
                        <input type="number" id="tube-kuni" name="tube-kuni" placeholder="Kuni">
                    </div>
                    <!--Price-->
                    <div class="form-object">
                        <h4>Hind</h4>
                        <input type="number" style="margin-right: 20px" id="hind-alates" name="hind-alates" placeholder="Alates" />
                        <input type="number" id="hind-kuni" name="hind-kuni" placeholder="Kuni" />
                    </div>
                    <!--Size-->
                    <div class="form-object">
                        <h4>Pindala m&sup2;</h4>
                        <input type="number" style="margin-right: 20px" id="pindala-alates" name="pindala-alates" placeholder="Alates" />
                        <input type="number" id="pindala-kuni" name="pindala-kuni" placeholder="Kuni" />
                    </div>
                    <!--Condition-->
                    <div class="form-object">
                        <h4>Seisukord</h4>
                        <select name="seisukord" id="seisukord">
                            <option value="" selected disabled hidden>Vali</option>
                            <option value="Renoveeritud">Renoveeritud</option>
                            <option value="Renoveerimata">Renoveerimata</option>
                            <option value="Uus">Uus</option>
                        </select>
                    </div>

                    <!--From the owner-->
                    <div class="form-object">
                        <h4>Otse Omanikult</h4>
                        <button type="button">Jah</button>
                        <button type="button">Ei</button>
                    </div>
                </div>
            </div>

            <div class="location-container">

                <!--Specifics filter button-->
                <div>
                    <button>Täpsustused</button>
                </div>
            </div>

            <div class="container">
                <div class="left-container">

                </div>
                <div class="right-container">

                </div>
            </div>
        </div>




        <!--Basic filters-->
        <div class="basic-filters">

            <div class="container">
                <div class="left-container">
                    <!--Energy class-->
                    <div>
                        <h4>Energiaklass</h4>
                        <select name="energiaklass" id="energiaklass">
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
                    <!--Year of construction-->
                    <div>
                        <h4>Ehitusaasta</h4>
                        <input type="number" id="ehitusaasta-alates" name="ehitusaasta-alates" placeholder="Alates" />
                        <input type="number" id="ehitusaasta-kuni" name="ehitusaasta-kuni" placeholder="kuni" />
                    </div>
                </div>
                <div class="right-container">

                </div>
            </div>

            <div class="container">
                <div class="left-container">
                    <!--Ownership-->
                    <div>
                        <h4>Omandivorm</h4>
                        <select id="omandivorm" name="omandivorm">
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
                    <!--Number of floors-->
                    <div>
                        <h4>Korruseid</h4>
                        <input type="number" id="korruseid-alates" name="korruseid-alates" placeholder="Alates" />
                        <input type="number" id="korruseid-kuni" name="korruseid-kuni" placeholder="Kuni" />
                    </div>
                    <!--Number of bedrooms-->
                    <div>
                        <h4>Magamistubade arv</h4>
                        <input type="number" id="magamistubade-arv-alates" name="magamistubade-arv-alates" placeholder="Alates" />
                        <input type="number" id="magamistubade-arv-kuni" name="magamistubade-arv-kuni" placeholder="Kuni" />
                    </div>
                </div>
                <div class="right-container">
                    <!--Praking-->
                    <div>
                        <h4>Prakimine</h4>
                        <select name="parkimine" id="parkimine">
                            <option value="" selected disabled hidden>Vali</option>
                            <option value="Tasuta">Tasuta</option>
                            <option value="Tasuline">Tasuline</option>
                            <option value="Puudub">Puudub</option>
                        </select>
                    </div>
                    <!--Number of bathrooms-->
                    <div>
                        <h4>WC arv</h4>
                        <input type="number" id="WC-arv-alates" name="WC-arv-alates" placeholder="Alates" />
                        <input type="number" id="WC-arv-kuni" name="WC-arv-kuni" placeholder="Kuni" />
                    </div>
                    <div>
                        <h4>Vannitubade arv</h4>
                        <input type="number" id="vannitubade-arv-alates" name="vannitubade-arv-alates" placeholder="Alates" />
                        <input type="number" id="vannitubade-arv-kuni" name="vannitubade-arv-kuni" placeholder="Kuni" />
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="left-container">
                    <!--Sanitary room-->
                    <div>
                        <h4>Sanitaarruum</h4>
                        <div>
                            <label for="Vann">Vann</label>
                            <input type="checkbox" id="Vann" value="Vann" name="sanitaarruum[]" />
                        </div>
                        <div>
                            <label for="Dušš">Vann</label>
                            <input type="checkbox" id="Dušš" value="Dušš" name="sanitaarruum[]" />
                        </div>
                        <div>
                            <label for="Pesumasin">Pesumasin</label>
                            <input type="checkbox" id="Pesumasin" value="Pesumasin" name="sanitaarruum[]" />
                        </div>
                        <div>
                            <label for="Saun">Saun</label>
                            <input type="checkbox" id="Saun" value="Saun" name="sanitaarruum[]" />
                        </div>
                        <label>Muu Sanitaarruum</label>
                        <input type="text" id="muu-sanitaarruum" placeholder="Sisesta" name="muu-sanitaarruum" />
                    </div>
                    <!--Roads-->
                    <div>
                        <h4>Teedeseisukord/Olemasolevad teed</h4>
                        <label for="teedeseisukord">Teedeseisukord</label>
                        <select name="teedeseisukord" id="teedeseisukord">
                            <option value="" selected disabled hidden>Vali</option>
                            <option value="Hea">Hea</option>
                            <option value="Halb">Halb</option>
                        </select>
                    </div>
                    <div>
                        <label for="olemasolevad-teed">Olemasolevad teed</label>
                        <select name="olemasolevad-teed" id="olemasolevad-teed">
                            <option value="" selected disabled hidden>Vali</option>
                            <option value="Kõnnitee">Kõnnitee</option>
                            <option value="Kergliiklustee">Kergliiklustee</option>
                            <option value="Sissesõit">Sissesõit</option>
                            <option value="Asfalttee">Asfalttee</option>
                            <option value="Kruusatee">Kruusatee</option>
                        </select>
                        <label>Muud Olemasolevad teed</label>
                        <input type="text" id="muud-olemasolevad-teed" placeholder="Sisesta" name="muud-olemasolevad-teed" />
                    </div>
                    <!--Water supply-->
                    <div>
                        <h4>Veevarustus</h4>
                        <div>
                            <label for="Puurkaev">Puurkaev</label>
                            <input type="checkbox" id="Puurkaev" value="Puurkaev" name="veevarustus[]" />
                        </div>
                        <div>
                            <label for="Salvkaev">Salvkaev</label>
                            <input type="checkbox" id="Salvkaev" value="Salvkaev" name="veevarustus[]" />
                        </div>
                        <div>
                            <label for="Tsentraalne vesi">Tsentraalne vesi</label>
                            <input type="checkbox" id="Tsentraalne vesi" value="Tsentraalne vesi" name="veevarustus[]" />
                        </div>
                        <label>Muu Veevarustus</label>
                        <input type="text" id="muu-veevarustus" placeholder="Sisesta" name="muu-veevarustus" />
                    </div>

                    <!--Sewage-->
                    <div>
                        <h4>Kanalisatsioon</h4>
                        <div>
                            <label for="Lokaalne">Lokaalne</label>
                            <input type="checkbox" id="Lokaalne" value="Lokaalne" name="kanalisatsioon[]" />
                        </div>
                        <div>
                            <label for="Imbväljak">Imbväljak</label>
                            <input type="checkbox" id="Imbväljak" value="Imbväljak" name="kanalisatsioon[]" />
                        </div>
                        <div>
                            <label for="Tsentraalne kanalisatsioon">Tsentraalne kanalisatsioon</label>
                            <input type="checkbox" id="Tsentraalne kanalisatsioon" value="Tsentraalne kanalisatsioon" name="kanalisatsioon[]" />
                        </div>
                        <div>
                            <label for="Mahuti">Mahuti</label>
                            <input type="checkbox" id="Mahuti" value="Mahuti" name="kanalisatsioon[]" />
                        </div>
                        <div>
                            <label for="Septik">Septik</label>
                            <input type="checkbox" id="Septik" value="Septik" name="kanalisatsioon[]" />
                        </div>
                        <div>
                            <label for="Biopuhasti">Biopuhasti</label>
                            <input type="checkbox" id="Biopuhasti" value="Biopuhasti" name="kanalisatsioon[]" />
                        </div>
                        <label>Muu Kanalisatsioon</label>
                        <input type="text" id="muu-kanalisatsioon" placeholder="Sisesta" name="muu-kanalisatsioon" />
                    </div>

                    <!--Protection-->
                    <div>
                        <h4>Turvalisus</h4>
                        <div>
                            <label for="Naabrivalve">Naabrivalve</label>
                            <input type="checkbox" id="Naabrivalve" value="Naabrivalve" name="turvalisus[]" />
                        </div>
                        <div>
                            <label for="Videovalve">Videovalve</label>
                            <input type="checkbox" id="Videovalve" value="Videovalve" name="turvalisus[]" />
                        </div>
                        <div>
                            <label for="Trepikoda lukus">Trepikoda lukus</label>
                            <input type="checkbox" id="Trepikoda lukus" value="Trepikoda lukus" name="turvalisus[]" />
                        </div>
                        <div>
                            <label for="Turvauks">Turvauks</label>
                            <input type="checkbox" id="Turvauks" value="Turvauks" name="turvalisus[]" />
                        </div>
                        <div>
                            <label for="Valvur">Valvur</label>
                            <input type="checkbox" id="Valvur" value="Valvur" name="turvalisus[]" />
                        </div>
                        <label for="muu-side">Muu Turvalisus</label>
                        <input type="text" id="muu-turvalisus" placeholder="Sisesta" name="muu-turvalisus" />
                    </div>
                    <!--Extras-->
                    <div>
                        <h4>Lisapinnad</h4>
                        <div>
                            <label for="Rõdu">Rõdu</label>
                            <input type="checkbox" id="Rõdu" value="Rõdu" name="lisapinnad[]" />
                        </div>
                        <div>
                            <label for="Terrass">Terrass</label>
                            <input type="checkbox" id="Terrass" value="Terrass" name="lisapinnad[]" />
                        </div>
                        <div>
                            <label for="Garaaž">Garaaž</label>
                            <input type="checkbox" id="Garaaž" value="Garaaž" name="lisapinnad[]" />
                        </div>
                        <div>
                            <label for="Eraldi panipaik">Eraldi panipaik</label>
                            <input type="checkbox" id="Eraldi panipaik" value="Eraldi panipaik" name="lisapinnad[]" />
                        </div>
                        <div>
                            <label for="Majaalune kelder">Majaalune kelder</label>
                            <input type="checkbox" id="Majaalune kelder" value="Majaalune kelder" name="lisapinnad[]" />
                        </div>
                        <div>
                            <label for="Kõrvalhoone">Kõrvalhoone</label>
                            <input type="checkbox" id="Kõrvalhoone" value="Kõrvalhoone" name="lisapinnad[]" />
                        </div>
                        <label>Muu Lisapind</label>
                        <input type="text" id="muu-lisapind" placeholder="Sisesta" name="muu-lisapind" />
                    </div>
                </div>
                <div class="right-container">
                    <!--Neighbourhood-->
                    <div>
                        <h4>Naabruskond</h4>
                        <div>
                            <label for="Väike">Väike</label>
                            <input type="checkbox" id="Väike" value="Väike" name="naabruskond[]" />
                        </div>
                        <div>
                            <label for="Suur">Suur</label>
                            <input type="checkbox" id="Suur" value="Suur" name="naabruskond[]" />
                        </div>
                        <div>
                            <label for="Rahulik">Rahulik</label>
                            <input type="checkbox" id="Rahulik" value="Rahulik" name="naabruskond[]" />
                        </div>
                        <div>
                            <label for="Palju tegevusi">Palju tegevusi</label>
                            <input type="checkbox" id="Palju tegevusi" value="Palju tegevusi" name="naabruskond[]" />
                        </div>
                        <label>Muu Naabruskond</label>
                        <input type="text" id="muu-naabruskond" placeholder="Sisesta" name="muu-naabruskond" />
                    </div>
                    <!--Warm Water-->
                    <div>
                        <h4>Soe vesi</h4>
                        <div>
                            <label for="Boiler">Boiler</label>
                            <input type="checkbox" id="Boiler" value="Boiler" name="soe_vesi[]" />
                        </div>
                        <div>
                            <label for="Soojuspump">Soojuspump</label>
                            <input type="checkbox" id="Soojuspump" value="Soojuspump" name="soe_vesi[]" />
                        </div>
                        <div>
                            <label for="Tsentraalne soe vesi">Tsentraalne soe vesi</label>
                            <input type="checkbox" id="Tsentraalne soe vesi" value="Tsentraalne soe vesi" name="soe_vesi[]" />
                        </div>
                        <label>Muu Soe vesi</label>
                        <input type="text" id="muu-soevesi" placeholder="Sisesta" name="muu-soevesi" />
                    </div>
                    <!--Connections-->
                    <div>
                        <h4>Side</h4>
                        <div>
                            <label for="Internet">Internet</label>
                            <input type="checkbox" id="Internet" value="Internet" name="side[]" />
                        </div>
                        <div>
                            <label for="Kaabel">Kaabel</label>
                            <input type="checkbox" id="Kaabel" value="Kaabel" name="side[]" />
                        </div>
                        <div>
                            <label for="Telefon">Telefon</label>
                            <input type="checkbox" id="Telefon" value="Telefon" name="side[]" />
                        </div>
                        <label for="muu-side">Muu Side</label>
                        <input type="text" id="muu-side" placeholder="Sisesta" name="muu-side" />
                    </div>
                    <!--Heating-->
                    <div>
                        <h4>Küttesüsteem</h4>
                        <div>
                            <label for="Keskküte">Keskküte</label>
                            <input type="checkbox" id="Keskküte" value="Keskküte" name="kuttesusteem[]" />
                        </div>
                        <div>
                            <label for="Ahjuküte">Ahjuküte</label>
                            <input type="checkbox" id="Ahjuküte" value="Ahjuküte" name="kuttesusteem[]" />
                        </div>
                        <div>
                            <label for="Elektriküte">Elektriküte</label>
                            <input type="checkbox" id="Elektriküte" value="Elektriküte" name="kuttesusteem[]" />
                        </div>
                        <div>
                            <label for="Gaasiküte">Gaasiküte</label>
                            <input type="checkbox" id="Gaasiküte" value="Gaasiküte" name="kuttesusteem[]" />
                        </div>
                        <div>
                            <label for="Põrandaküte">Põrandaküte</label>
                            <input type="checkbox" id="Põrandaküte" value="Põrandaküte" name="kuttesusteem[]" />
                        </div>
                        <div>
                            <label for="Konditsioneer">Konditsioneer</label>
                            <input type="checkbox" id="Konditsioneer" value="Konditsioneer" name="kuttesusteem[]" />
                        </div>
                        <div>
                            <label for="Maaküte">Maaküte</label>
                            <input type="checkbox" id="Maaküte" value="Maaküte" name="kuttesusteem[]" />
                        </div>
                        <label for="muu-side">Muu Küttesüsteem</label>
                        <input type="text" id="muu-kuttesusteem" placeholder="Sisesta" name="muu-kuttesusteem" />
                    </div>

                    <!--Kitchen-->
                    <div>
                        <h4>Köök</h4>
                        <select name="kook" id="kook">
                            <option value="" selected disabled hidden>Vali</option>
                            <option value="eraldi">Eraldi</option>
                            <option value="avatud">Avatud</option>
                            <option value="puudu">Puudu</option>
                        </select>
                        <label for="koogi_pindala">Köögi pindala (m&sup2;)</label>
                        <input type="number" id="koogi_pindala" for="koogi_pindala" placeholder="0m&sup2;" />
                    </div>
                    <!--Interior-->
                    <div>
                        <h4>Sisustus</h4>
                        <select name="sisustus" id="sisustus">
                            <option value="" selected disabled hidden>Vali</option>
                            <option value="Sisustatud">Sisustatud</option>
                            <option value="Sisustus puudu">Sisustus puudu</option>
                        </select>
                    </div>
                    <div>
                        <h4>Lisad</h4>
                        <div>
                            <label for="Bassein">Bassein</label>
                            <input type="checkbox" id="Bassein" value="Bassein" name="lisad[]" />
                        </div>
                        <div>
                            <label for="Mullivann">Mullivann</label>
                            <input type="checkbox" id="Mullivann" value="Mullivann" name="lisad[]" />
                        </div>
                        <div>
                            <label for="Garderoob">Garderoob</label>
                            <input type="checkbox" id="Garderoob" value="Garderoob" name="lisad[]" />
                        </div>
                        <div>
                            <label for="Kamin">Kamin</label>
                            <input type="checkbox" id="Kamin" value="Kamin" name="lisad[]" />
                        </div>
                        <div>
                            <label for="Lift">Lift</label>
                            <input type="checkbox" id="Lift" value="Lift" name="lisad[]" />
                        </div>
                        <div>
                            <label for="Jõusaal">Jõusaal</label>
                            <input type="checkbox" id="Jõusaal" value="Jõusaal" name="lisad[]" />
                        </div>
                        <div>
                            <label for="Lemmikloom lubatud">Lemmikloom lubatud</label>
                            <input type="checkbox" id="Lemmikloom lubatud" value="Lemmikloom lubatud" name="lisad[]" />
                        </div>
                        <label>Muud lisad</label>
                        <input type="text" id="muu-lisad" placeholder="Sisesta" name="muu-lisad" />
                    </div>
                </div>
            </div>
        </div>
        <!--Specifics filter-->
        <div class="specifics-filter">

        </div>
    </form>

<?php return ob_get_clean();

    if (is_page('filter-dev')) {
        echo do_shortcode('[filter_form]');
    }
}
add_shortcode("filter_form", "filter_form");
?>