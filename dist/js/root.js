const idleTimeout = 120000;
var segments = window.location.pathname.split('/');
var timedown = 11000;
let idleTime = 0;
let intervaliddle;
let intervaldown;
var urls = window.location.search;
var search = new URLSearchParams(urls);
var head = search.get('head');
var parent = search.get('parent');

//lastupdate();

function lastupdate() {
    var timezone = new Date().toLocaleString("en-US", { timeZone: "Asia/Jakarta" });
    var jakartaTime = new Date(timezone);

    var jam = jakartaTime.getHours();
    var menit = jakartaTime.getMinutes();
    var detik = jakartaTime.getSeconds();

    var zonaWaktu = jakartaTime.toString().match(/\(([^)]+)\)$/)[1];

    var formattedTime = jam + ":" + (menit < 10 ? "0" + menit : menit) + ":" + (detik < 10 ? "0" + detik : detik);

    $("#lastupdate").html("Last Update : " + formattedTime);
};

if (
    segments[3] != "concept_1" &&
    segments[3] != "concept_2" &&
    segments[3] != "concept_3" &&
    segments[3] != "concept_4" &&
    segments[3] != "concept_5" && /*segments[3] != "antrianloket" && segments[3] != "pasienbaru" && segments[3] != "pasienlama" && segments[3] != "bayarpoli" && segments[3] != "bayartindakan" &&*/
    segments[3] != "bayarpoliqris" &&
    segments[3] != "cekresep" &&
    segments[3] != "checkin" &&
    segments[3] != "checkinlandscape" &&
    segments[3] != "ns" &&
    segments[3] != "registpasienumum" &&
    segments[3] != "suratkontrolv2" &&
    segments[3] != "antrianrad" && (segments[3] != "formantrian" || head != 'antrianrad') && (segments[3] != "cetakradiologi" || head != 'antrianrad') &&
    (segments[3] != "antriankasir" || parent != 'A14')
) {

    resetIdleTime();

    function resetIdleTime() {
        idleTime = 0;
        timedown = 6000;
        clearInterval(intervaldown);
        clearInterval(intervaliddle)
        intervaliddle = setInterval(checkIdle, 1000);

    }

    function checkIdle() {
        idleTime += 1000;
        if (idleTime >= idleTimeout) {
            clearInterval(intervaliddle)
            Swal.fire({
                icon: "info",
                html:
                    "<hr><h4 style='color:#234974;'>Halaman Tidak Aktif Lebih Dari <b>" + (idleTimeout / 1000) + " Detik</b></h4>" +
                    "<h3 class='font-weight-bold' style='color:#234974;'>Redirect Otomatis Dalam Waktu : </h3>" +
                    "<h2 style='color:#234974;'><b><span id='countdown'></span> Detik</b></h2>" +
                    "<h2 style='color:#234974;'><b>KLIK TOMBOL OK</b></h2>" +
                    "<h5 style='color:#234974;'>Untuk Membatalkan Redirect Otomatis</h5>",
                didOpen: () => {
                    // Swal.showLoading();
                    intervaldown = setInterval(autoredirect, 1000);
                },
                showClass: { popup: "animate__animated animate__fadeInUp animate__faster" },
                hideClass: { popup: "animate__animated animate__fadeOutDown animate__faster" }
            }).then((result) => {
                resetIdleTime();
            })
        }
    }

    function autoredirect() {
        timedown -= 1000;
        if (timedown >= 0) {
            $("#countdown").html(timedown / 1000);
        } else {
            var urlParams = new URLSearchParams(window.location.search);
            var headValue = urlParams.get('head');
            window.location.href = url + "index.php/" + headValue;
        }
    }
};

function simpanantrian(btn) {
    var kode = btn.attr("data-kode");
    $.ajax({
        url: url + "index.php/Antriangeneral/simpanantrian",
        data: { 'kode': kode },
        method: "POST",
        dataType: "JSON",
        cache: false,
        success: function (data) {

            if (data.responCode === "00") {
                Swal.fire({
                    position: "center",
                    // icon             : data.responHead,
                    title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responTitle + "</h1>",
                    html: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
                    timerProgressBar: true,
                    showConfirmButton: false,
                    timer: 3000,
                    showClass: { popup: "animate__animated animate__fadeInUp animate__faster" },
                    hideClass: { popup: "animate__animated animate__fadeOutDown animate__faster" }
                });
            } else {
                Swal.fire({
                    position: "center",
                    // icon             : data.responHead,
                    title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responTitle + "</h1>",
                    html: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
                    backdrop: "rgba(255, 0, 0,0.4)",
                    timerProgressBar: true,
                    showConfirmButton: false,
                    timer: 3000,
                    showClass: { popup: "animate__animated animate__fadeInUp animate__faster" },
                    hideClass: { popup: "animate__animated animate__fadeOutDown animate__faster" }
                });
            };

        }
    });
    return false;
};

function cetakantrian(btn) {
    var kode = btn.attr("data-kode");
    var jenisid = btn.attr("data-jenisid");
    $.ajax({
        url: url + "index.php/Antriangeneral/cetakantrian",
        data: { 'kode': kode, 'jenisid': jenisid, },
        method: "POST",
        dataType: "JSON",
        cache: false,
        success: function (data) {

            if (data.responCode === "00") {
	            // PRINT TANPA TAB BARU
	            var form = document.createElement("form");
	            form.method = "POST";
	            form.action = url + "index.php/Antriangeneral/printantrian";
	            form.target = "framePrint";
	            
	            var input = document.createElement("input");
	            input.type = "hidden";
	            input.name = "trans_id";
	            input.value = data.responData.TRANS_ID;
	            
	            form.appendChild(input);
	            document.body.appendChild(form);
	            form.submit();
	            document.body.removeChild(form);
	            
	            Swal.fire({
		            position: "center",
		            icon: data.responHead,
		            title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responTitle + "</h1>",
		            html: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
		            width: '90%',
		            showConfirmButton: true,
		            confirmButtonText: "OK",
		            confirmButtonColor: "#234974",
		            showClass: {
			            popup: "animate__animated animate__fadeInUp animate__faster"
		            },
		            hideClass: {
			            popup: "animate__animated animate__fadeOutDown animate__faster"
		            },
		            didOpen: () => {
			            const myCarousel = document.querySelector('#carouselAntrian');
			            new bootstrap.Carousel(myCarousel, {
				            interval: 3000,
				            ride: 'carousel'
			            });
		            }
	            });
				
            } else {
                Swal.fire({
                    position: "center",
                    // icon             : data.responHead,
                    title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responTitle + "</h1>",
                    html: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
                    backdrop: "rgba(255, 0, 0,0.4)",
                    timerProgressBar: true,
                    showConfirmButton: false,
                    timer: 3000,
                    showClass: { popup: "animate__animated animate__fadeInUp animate__faster" },
                    hideClass: { popup: "animate__animated animate__fadeOutDown animate__faster" }
                });
            };

        }
    });
    return false;
};

