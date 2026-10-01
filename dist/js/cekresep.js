let lastAjaxConfig = null;
let loadingTimeout = null;
let isAjaxRunning = false;

$("#kodebooking").focus();; 

let kodebooking = document.getElementById("kodebooking");

function addToDisplay(value) {
	kodebooking.value += value;
}

function clearinput() {
    var jumlahkode = document.getElementById('kodebooking').value;
    document.getElementById('kodebooking').value = jumlahkode.substring(0, jumlahkode.length - 1);
}

function clear() {
	$("#kodebooking").val("");
	$("#kodebooking").removeAttr("readonly");
};

var epiid = '';
var dokterid = '';
var pasienid = '';
var dataproses = '';

function modalrating(data) {
	$(".star-ratings a").attr('data-select', false);
	$(".star-ratings a").removeClass('active');
	$("[id='btn-penilaian']").css({ 'background': '#ffffff', 'color': '#234974' });
	$("[id='btn-penilaian']").removeClass('active');
	epiid = data.EPISODE_ID;
	dokterid = data.DOKTER_ID;
	pasienid = data.PASIEN_ID;
	var tester = new Image();
	var dktimg = '';
	tester.onload = function () {
		dktimg = '<img src="' + url + 'dist/img/dokter/' + dokterid + '.png"/>';
		$("#modalrating #dokter_img").html(dktimg);
	};
	tester.onerror = function () {
		dktimg = '<img src="' + url + 'dist/img/dokter/doctor4.png"/>'
		$("#modalrating #dokter_img").html(dktimg);
	};
	tester.src = url + 'dist/img/dokter/' + dokterid + '.png';
	let options = {
		text: 'https://rsudpasarminggu.jakarta.go.id/crm/index.php/saran',
		width: 200,
		height: 200,
		quietZone: 10,
		colorDark: "#222222",
		colorLight: "#ffffff",
		quietZoneColor: 'rgba(255,255,255,1)',
		correctLevel: QRCode.CorrectLevel.L,
		//logo: rsudLogoURL,
		//logoWidth: 80,
		//logoHeight: 80,
		PO: '#4aa88f', // Global Posotion Outer color. if not set, the defaut is colorDark
		PI: '#254a73', // Global Posotion Inner color. if not set, the defaut is colorDark
		// PO_TL: '', // Posotion Outer color - Top Left
		// PI_TL: '', // Posotion Inner color - Top Left
		// PO_TR: '', // Posotion Outer color - Top Right
		// PI_TR: '', // Posotion Inner color - Top Right
		// PO_BL: '', // Posotion Outer color - Bottom Left
		// PI_BL: '', // Posotion Inner color - Bottom Left
	};
	
	// Create QRCode Object
	var qrCode = new QRCode(document.getElementById("qr-review"), options);
	$(".star-ratings a").on('click', function () {
		/* var rating = $(this).attr('data-star');
		if (rating >= 3) {
			$("#ketrating1").show();
			$("#ketrating2").hide();
		} else {
			$("#ketrating1").hide();
			$("#ketrating2").show();
		} */
		$(".star-ratings a").attr('data-select', false);
		$(this).attr('data-select', true);
		var stark = $(this).attr('data-star');
		$(".star-ratings a").removeClass('active');
		for (i = 1; i <= stark; i++) {
			$(".star-ratings a[data-star='" + i + "']").addClass('active');
		}
	})
	$("#modalrating").modal('show');
	$("#modalrating").attr('data-post', data);
	$("#modalrating #dokter_nama").html(data.NAMADOKTER);
	let isFirstClick = true;
	$("#btn-penilaian").on('click', function () {
		if (isFirstClick) {
			// First click action
			console.log("First click action");
		} else {
			// Second click action
			console.log("Second click action");
		}
		isFirstClick = !isFirstClick;  // Toggle the flag
	})
	$("[id='btn-penilaian']").each(function () {
		var $this = $(this);
		let isFirstClick = true;
		$this.on('click', function () {
			$("[id='btn-penilaian']").css({ 'background': '#ffffff', 'color': '#234974' });
			$("[id='btn-penilaian']").removeClass('active');
			//if (isFirstClick) {
			$this.css({ 'background': '#234974', 'color': '#ffffff' });
			$($this).addClass('active');
			/* 	} else {
					$this.css({ 'background': '#ffffff', 'color': '#234974' });
					$($this).removeClass('active');
				}
				isFirstClick = !isFirstClick; */
		})
	})
	$("#modalrating").on('hide.bs.modal', function () {
		qrCode.clear();
	});
}

function proses() {

    Swal.fire({
        position: "center",
        icon: "info",
        title: "<h1 class='font-weight-bold' style='color:#234974;'>INFORMASI</h1>",
        html: "<h5 style='color:#234974;'>Mohon Menunggu Kami Sedang Melakukan Pencarian Data Anda</h5>",
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();

            // timeout detik ? fallback
            loadingTimeout = setTimeout(() => {
                if (isAjaxRunning) {
                    Swal.hideLoading();
                    Swal.update({
                        icon: "warning",
                        html: "<h5 style='color:#234974;'>Proses<br>Silakan coba lagi.</h5>",
                        showConfirmButton: true,
                        confirmButtonText: "Coba Lagi",
                        allowOutsideClick: true
                    });
                }
            }, 10000);

            setTimeout(() => {
                prosesAjax();
            }, 50);
        }
    });

}


function prosesAjax() {

    if (isAjaxRunning) return; // anti double klik
    isAjaxRunning = true;

    var form = $("#formcekresep");
    $("#kodebooking").attr("readonly", true);

    // simpan config terakhir (buat retry)
    lastAjaxConfig = {
        url: form.attr("action"),
        data: form.serialize(),
        method: "POST",
        dataType: "JSON",
        cache: false
    };

    $.ajax({
        ...lastAjaxConfig,
        success: function (data) {
            clearTimeout(loadingTimeout);
            isAjaxRunning = false;
            Swal.close();
			
            if (data.responCode === "01") {
                Swal.fire({
                    position: "center",
                    title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
                    html: data.responHtml,
                    backdrop: "rgba(255, 0, 0,0.4)",
                    timer: 5000,
                    showConfirmButton: false
                }).then(() => resetInput());
            } else {
                if (data.responData && data.responData.EPISODE_ID) {
                    statuscheckin(data.responData.EPISODE_ID);
                }
                dataproses = data;
                pendingrespon();
            }
        },
        error: function () {
            clearTimeout(loadingTimeout);
            isAjaxRunning = false;

            Swal.fire({
                icon: "error",
                title: "Coba Lagi",
                text: "Gagal Memproses Data",
                confirmButtonText: "Coba Lagi"
            }).then(() => retryAjax());
        }
    });
}

function retryAjax() {
    if (!lastAjaxConfig) return;
    Swal.fire({
        position: "center",
        icon: "info",
        title: "Mengulang Proses",
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
            prosesAjax();
        }
    });
}

function resetInput() {
    $("#kodebooking").val("");
    $("#kodebooking").removeAttr("readonly");
}


function pendingrespon() {
	
	var data = dataproses;

	let isWarning = data.responData.OBATWARNING != 0;
	Swal.fire({
		position: "center",
		icon: data.responHead,
		title: data.responDesc,
		html: data.responHtml,
		showCancelButton: !isWarning,
		confirmButtonText: isWarning ? "OK" : "DITUNGGU",
		cancelButtonText: "DIANTAR JNE",
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#228f5eff",
		showClass: { popup: "animate__animated animate__fadeInUp animate__faster" },
		hideClass: { popup: "animate__animated animate__fadeOutDown animate__faster" }
	}).then((result) => {
		if (result.isConfirmed) {

			statusambil('T',data);

			// var urlParams = new URLSearchParams(window.location.search);
			// var headValue = urlParams.get("head");
			// window.location.href = headValue;

		} else if (result.dismiss === Swal.DismissReason.cancel) {
			statusambil('A',data);
			
			Swal.fire({
				position: "center",
				title: "Silahkan mengisi Formulir pengantaran obat pada Booth JNE yang tersedia di lantai 1",
  				icon: "success",
			}).then((result) =>{
				if (result.isConfirmed){
					// var urlParams = new URLSearchParams(window.location.search);
					// var headValue = urlParams.get("head");
					// window.location.href = headValue;
				}
			});
			
		}

		// Setelah swal ditutup, reset input kodebooking
		$("#kodebooking").val("");
		$("#kodebooking").removeAttr("readonly");
	});
	//}
}

$(document).on("submit", "#formcekresep", function (e) {
	e.preventDefault();
	
    Swal.fire({
		position: "center",
		icon: "info",
		title: "<h1 class='font-weight-bold' style='color:#234974;'>INFORMASI</h1>",
		html: "<h5 style='color:#234974;'>Mohon Menunggu Kami Sedang Melakukan Pencarian Data Anda</h5>",
		allowOutsideClick: false,
		showConfirmButton: false,
		didOpen: () => {
			Swal.showLoading();
		}
	});
	
	var form = $("#formcekresep");
	$("#kodebooking").attr("readonly", "true");
	$.ajax({
		url: form.attr("action"),
		data: form.serialize(),
		method: "POST",
		dataType: "JSON",
		cache: false,
		success: function (data) {
			Swal.close();
			//cek rating
			//statuscheckin(data.responData.EPISODE_ID);

			if (data.responCode === "01") {
				Swal.fire({
					position: "center",
					// icon             : data.responHead,
					title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
					html: data.responHtml,
					backdrop: "rgba(255, 0, 0,0.4)",
					timerProgressBar: true,
					showConfirmButton: false,
					timer: 5000,
					showClass: { popup: "animate__animated animate__fadeInUp animate__faster" },
					hideClass: { popup: "animate__animated animate__fadeOutDown animate__faster" }
				}).then((result) => {
					if (result.dismiss === Swal.DismissReason.timer) {
						$("#kodebooking").val("");
						$("#kodebooking").removeAttr("readonly");
					} else {
						if (Swal.DismissReason.close) {
							$("#kodebooking").val("");
							$("#kodebooking").removeAttr("readonly");
						}
					}
				});
			} else {
				if (data.responData && data.responData.EPISODE_ID) {
					statuscheckin(data.responData.EPISODE_ID);
				} //tambahan 23/01/2026

				dataproses = data;
				$.ajax({
					url: url + 'index.php/Cekresepkiosk/cekrating',
					data: {
						episodeid: data.responData.EPISODE_ID
					},
					method: "POST",
					dataType: "JSON",
					
					success: function (res) {
						if (res.responCode === "01") {
							modalrating(data.responData);
						} else {
							pendingrespon();
						}
					}
				});
			}
			//}
		}
	});
});

function statusambil(status,dataproses){
	var data = dataproses;
	var statusambil = status;

	$.ajax({
		url: url + 'index.php/Cekresep/statusambil',
		method: "POST",
		dataType: "JSON",
		cache: false,
		data: {statusambil: statusambil, episodeid: data.responData.EPISODE_ID},
		success: function (data) {
			if (data.responCode === "00") {

			}
		}
	});
	return false;
}

function statuscheckin(episode){
	$.ajax({
		url: url + 'index.php/Cekresep/statucheckin',
		method: "POST",
		dataType: "JSON",
		cache: false,
		data: {episodeid: episode},
		success: function (data) {
			if (data.responCode === "00") {

			}
		}
	});
	return false;
}