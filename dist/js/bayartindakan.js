// document.addEventListener("DOMContentLoaded", function () {
// 	var urlParams = new URLSearchParams(window.location.search);
// 	var kode = urlParams.get('kode');
// 	document.getElementById('kode').value = kode;
// });


function addToDisplay(value) {
	idpasantri.value += value;
};

function clearinput() {
	var idpasantri = $("#idpasantri").val();
	$("#idpasantri").val(idpasantri.slice(0, -1));
};

function proses() {
	var form = $("#formantrikasir");
	
	var btn = $('#btnsimpan');
	
	// cegah double submit
	if (btn.prop('disabled')) {
		return;
	}
	
	// disable tombol
	btn.prop('disabled', true);
	
	// loading info
	Swal.fire({
		title: 'Sedang diproses...',
		html: 'Mohon tunggu sebentar',
		allowOutsideClick: false,
		allowEscapeKey: false,
		showConfirmButton: false,
		didOpen: function () {
			Swal.showLoading();
		}
	});
	
	$("#idpasantri").attr("readonly", "true");
	
	$.ajax({
		url: form.attr("action"),
		data: form.serialize(),
		method: "POST",
		dataType: "JSON",
		cache: false,
		success: function (res) {
			console.log(res);
			
			Swal.close();
			
			if (res.responCode === "00") {
				Swal.fire({
					position: "center",
					icon: res.responHead,
					title: "<h1 class='font-weight-bold' style='color:#234974;'>" + res.responDesc + "</h1>",
					html: "<h1 class='font-weight-bold' style='color:#234974;'>" + res.responHtml + "</h1>",
					showConfirmButton: true,
					confirmButtonText: "OK",
					confirmButtonColor: "#234974",
					showClass: {
						popup: "animate__animated animate__fadeInUp animate__faster"
					},
					hideClass: {
						popup: "animate__animated animate__fadeOutDown animate__faster"
					}
				}).then(function (result) {
					if (result.value) {
						window.location.href = res.responUrl;
					}
				});
			} else {
				
				// aktifkan lagi tombol kalau gagal
				btn.prop('disabled', false);
				
				Swal.fire({
					position: "center",
					icon: res.responHead,
					title: "<h1 class='font-weight-bold' style='color:#234974;'>" + res.responDesc + "</h1>",
					html: "<h1 class='font-weight-bold' style='color:#234974;'>" + res.responHtml + "</h1>",
					showConfirmButton: true,
					confirmButtonText: "OK",
					confirmButtonColor: "#234974",
					showClass: {
						popup: "animate__animated animate__fadeInUp animate__faster"
					},
					hideClass: {
						popup: "animate__animated animate__fadeOutDown animate__faster"
					}
				});
			}
		},
		error: function () {
			
			Swal.close();
			
			// aktifkan lagi tombol kalau error
			btn.prop('disabled', false);
			
			Swal.fire({
				icon: 'error',
				title: 'Terjadi kesalahan',
				text: 'Server tidak merespon.',
				confirmButtonColor: '#234974'
			});
		}
	});
};