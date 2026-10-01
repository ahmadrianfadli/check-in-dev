
var dh = $(window).height() - $(".fixed-bottom").outerHeight() - 400;
//document.addEventListener("DOMContentLoaded", function () {
var urlParams = new URLSearchParams(window.location.search);
var kode = urlParams.get('kode');
var pasienid = urlParams.get('pasienid');
var head = urlParams.get('head');
var subhead = urlParams.get('subhead');
// $(document).ready(function(){
// $('#validationTglLahir').mask('00.00.0000');
// });

document.getElementById('closeButton').addEventListener('click', function() {
    window.location.href = url + "index.php/antriankasir?head=concept_2&parent=A11";
});


$("#tanggalvalue").datepicker({
	format: 'dd.mm.yyyy',
	daysOfWeekDisabled: [0],
	enableOnReadonly: false,
	endDate: endDate,
	startDate: startDate,
	todayHighlight: true,
	autoclose: true,
	//container: '.wrapper'
}).on("change", function () {
	$("#jadwal").val('');
	//showPoli();
	//showDokter();
	var dokterId = $('#dokter_id').val();

	if (dokterId) {
		showJadwalDokterDuaMinggu();
		showJadwal();
	} else {
		$('#table-jadwal-poli').html('');
	}
	cekbtn()
})
$('#poli_id').on('change', function () {

	showDokter();
	showJadwal();
	$('#table-jadwal-poli').html('');
	cekbtn()
	/* var dokterId = $('#dokter_id').val();

	if (dokterId) {
		showJadwalDokterDuaMinggu();
	} else {
		$('#table-jadwal-poli').html();
	} */
});

$('#dokter_id').on('change', function () {

	var dokterId = $(this).val();

	if (dokterId) {
		showJadwalDokterDuaMinggu();
		showJadwal();
	} else {
		$('#table-jadwal-poli').html('');
	}
	cekbtn()
});
$('#jadwal').on('change', function () {
	cekbtn();
});
showPoli();
function cekbtn() {
	var tgl = $("#tanggalvalue").val();
	var poliId = $('#poli_id').val();
	var dokterId = $('#dokter_id').val();
	var jadwal = $("#jadwal").val();
	if (tgl && poliId && dokterId && jadwal) {
		$(".btn-next").prop('disabled', false)
	} else {
		$(".btn-next").prop('disabled', true)
	}
}
function cekharilibur() {
	var tgl = $('#tanggalvalue').val();

	$.ajax({
		url: url + 'index.php/registpasienumum/cekharilibur',
		type: 'post',
		dataType: 'json',
		data: {
			tanggal: tgl
		},
		success: function (res) {
			// console.log(res);

			// var ket = '';
			// res.map(function(d){
			// 	ket = d.KETERANGAN;
			// })

			if (res && res.length > 0) {
				var ket = res[0].KETERANGAN;
				alert('Hari Libur! ' + ket + ' Silakan pilih tanggal lain!');
			} else {
				showPoli();
			}
		},

		error: function (xhr, status, error) {
			console.error('Error checking hari libur');
		}
	});
}

// Placeholder functions
function showPoli() {
	// Logic untuk menampilkan poli
	console.log('showPoli called');
}

function showDokter() {
	// Logic untuk menampilkan dokter
	console.log('showDokter called');
}

function showJadwal() {
	// Logic untuk menampilkan jadwal
	console.log('showJadwal called');
}

function showPoli() {
	$.ajax({
		url: url + 'index.php/registpasienumum/showPoli',
		type: 'post',
		dataType: 'json',
		// data: {
		// 	pasienid: pasienid
		// },
		success: function (res) {
			// console.log(res);
			var opt = '';
			res.map(function (d) {
				opt += '<option value="' + d.POLI_ID + '">' + d.KETERANGAN + '</option>';
			})
			$('#poli_id').html(opt);
		}
	});
}

function showDokter() {
	var poliId = $('#poli_id').val();

	$.ajax({
		url: url + 'index.php/registpasienumum/showDokter',
		type: 'post',
		dataType: 'json',
		data: {
			poliid: poliId
		},
		success: function (res) {
			// console.log(res);
			var opt = '';
			res.map(function (d) {
				opt += '<option value="' + d.DOKTER_ID + '">' + d.NAMA + '</option>';
			})
			$('#dokter_id').html(opt);
		}
	});
}

function showJadwal() {
	var tgl = $("#tanggalvalue").val();
	var poliId = $('#poli_id').val();
	var dokterId = $('#dokter_id').val();

	$.ajax({
		url: url + 'index.php/registpasienumum/showJadwal',
		type: 'post',
		dataType: 'json',
		data: {
			poliid: poliId,
			dokterid: dokterId,
			tgl: tgl
		},
		success: function (res) {
			console.log(res);
			var opt = '<option value="">PILIH JAM</option>';
			res.map(function (d) {
				opt += '<option value="' + d.value + '">' + d.label + '</option>';
			})
			$('#jadwal').html(opt);
		}
	});
}

function determineJenis(tanggal) {
	let today = new Date();
	let selectedDate = new Date(tanggal);

	return selectedDate > today ? '1' : '2';
}

function showJadwalDokterDuaMinggu() {
	var tgl = $("#tanggalvalue").val();
	var poliId = $('#poli_id').val();
	var dokterId = $('#dokter_id').val();
	var jenis = determineJenis(tgl);
	$.ajax({
		url: url + 'index.php/registpasienumum/showJadwalDokterDuaMinggu',
		type: 'post',
		dataType: 'json',
		data: {
			poliid: poliId,
			dokterid: dokterId,
			tgl: tgl,
			jenis: jenis
		},
		dataType: 'JSON',
		success: function (res) {
			console.log(res);
			if (res.success) {

				let tableContent = '<div class="row">';

				for (let k in res.data) {

					let bgclass;
					let keterangan = res.data[k].KETERANGAN;

					if (res.data[k].HARI_ID === '1') {
						bgclass = "bg-danger";
					} else {
						if ((res.data[k].ADA_JADWAL === 'Y') && (res.data[k].SISA > 0)) {
							bgclass = "bg-success";
						} else {
							bgclass = "bg-secondary";
						}
					}

					kuota = keterangan;

					tableContent += '<div class="col-3 mb-2"><div class="tgl-jadwal ' + bgclass + '" style="border-radius:20px;padding:10px 20px;">'
						+ '<div class="font-weight-bold">' + (res.data[k].TGLPJG) + '</div>'
						+ '<div>' + (res.data[k].HARI) + '</div>'
						+ '<hr class="my-1"/>'
						+ '<div>' + kuota + '</div>'
						+ '</div></div>';
				}

				tableContent += '</div>';

				//	$tableJadwalPoli.html(tableContent);

				$('#table-jadwal-poli').html(tableContent);
				console.log('Content added to table-jadwal-poli'); // Debugging line
			} else {
				console.error('AJAX response indicates failure');
			}
		}
	});
}


$("#data-transaksi").css({
	'max-height': dh,
	'overflow': 'scroll'
});

function addToDisplay(value) {
	idpasantri.value += value;
};

function addToDisplayX(value) {
	validationTglLahir.value += value;
};

function clearinput() {
	var idpasantri = $("#idpasantri").val();
	$("#idpasantri").val(idpasantri.slice(0, -1));
};

function clearinputx() {
	var validationTglLahir = $("#validationTglLahir").val();
	$("#validationTglLahir").val(validationTglLahir.slice(0, -1));
};

$(document).on("submit", "#formregpasien", function (e) {
	e.preventDefault();
	proses();
});

// $('#tanggal').datetimepicker({
// 	format: 'DD.MM.Y',
// 	minDate: new Date(),
// 	autoclose: true
// });

function masterantrian() {
	$("#daftarantrian").html("");
	$.ajax({
		url: url + "index.php/Registpasienumum/masterantrian",
		method: "POST",
		dataType: "JSON",
		cache: false,
		success: function (data) {
			var daftarantrian = "";

			if (data.responCode == "00") {
				result = data.responResult;

				for (var i in result) {
					daftarantrian += "<div class='col-md-6 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn'>";

					if (result[i].JENIS_ID === "A07") {
						daftarantrian += "<a href='./registpasienumum?kode=" + result[i].KODE + "&head=concept_2&subhead=registpasienumum' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
					} else {
						daftarantrian += "<a onclick='simpanantrian($(this));' data-kode='" + result[i].KODE + "' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
					}

					daftarantrian += "<i class='" + result[i].ICON_CLASS + " fa-5x text-center' style='background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;'></i><br>";
					daftarantrian += "<h5 class='text-center' style='color:#234974;'>" + result[i].KETERANGAN + " </h5>";
					daftarantrian += "</a>";
					daftarantrian += "</div>";
				}

			}

			$("#daftarantrian").html(daftarantrian);
		}
	});
	return false;
};

function proses() {
	var form = $("#formregpasien");
	$("#idpasantri").attr("readonly", "true");
	$.ajax({
		url: form.attr("action"),
		type: form.attr('method'),
		data: form.serialize() + "&subhead=" + subhead,
		dataType: "JSON",
		cache: false,
		success: function (data) {
			console.log(data);
			if (data.responCode === "00") {
				//$('#validationModal').modal('show');

				window.location = data.responurl;
				/* Swal.fire({
					position         : "center",
					icon             : data.responHead,
					title            : "<h1 class='font-weight-bold' style='color:#234974;'>"+data.responDesc+"</h1>",
					html             : data.responHtml,
					timerProgressBar : true,
					showConfirmButton: false,
					timer            : 5000,
					showClass: {
						popup: `
						  animate__animated
						  animate__fadeInUp
						  animate__faster
						`
					},
					hideClass: {
						popup: `
						  animate__animated
						  animate__fadeOutDown
						  animate__faster
						`
					}
				}).then((result) => {
					if (result.dismiss === Swal.DismissReason.timer) {
						$("#idpasantri").val("");
						$("#idpasantri").removeAttr("readonly");
					}else{
						if (Swal.DismissReason.close) {
							$("#idpasantri").val("");
							$("#idpasantri").removeAttr("readonly");
						}
					}
				}); */
			} else {
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
						showClass: {
							popup: `
							  animate__animated
							  animate__fadeInUp
							  animate__faster
							`
						},
						hideClass: {
							popup: `
							  animate__animated
							  animate__fadeOutDown
							  animate__faster
							`
						}
					}).then((result) => {
						if (result.dismiss === Swal.DismissReason.timer) {
							$("#idpasantri").val("");
							$("#idpasantri").removeAttr("readonly");
						} else {
							if (Swal.DismissReason.close) {
								$("#idpasantri").val("");
								$("#idpasantri").removeAttr("readonly");
							}
						}
					});
				} else if (data.responCode === "03") {
					// qris transaksi sebelumnya
					tagihanid = data.responData.tagihanid;

					$("#data-content").html('');
					$("#qrcontent").html('');
					$("#modalqr").modal('show');
					$("#data-content").html('loading');
					$("#alert").html(data.responHtml);

					var content = '';
					content = "<h2 class='fw-bold'>Rp<span class='number'>" + data.responData.nominal + "</span></h2>";

					$("#data-content").html(content);
					$('.number').number(true, 0, '', '.');

					setInterval(() => { cekstatusbayar(); }, 3000);

					let options = {
						text: data.responData.qris,
						width: 350,
						height: 350,
						quietZone: 10,
						colorDark: "#000000",
						colorLight: "#ffffff",
						quietZoneColor: 'rgba(255,255,255,1)',
						correctLevel: QRCode.CorrectLevel.H,

						PO_TL: '', // Posotion Outer color - Top Left 
						PI_TL: '', // Posotion Inner color - Top Left 
						PO_TR: '', // Posotion Outer color - Top Right 
						PI_TR: '', // Posotion Inner color - Top Right 
						PO_BL: '', // Posotion Outer color - Bottom Left 
						PI_BL: '', // Posotion Inner color - Bottom Left 
					};

					// Create QRCode Object
					let qrCode = new QRCode(document.getElementById("qrcontent"), options);

				} else {
					Swal.fire({
						position: "center",
						// icon             : data.responHead,
						title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
						html: data.responHtml,
						backdrop: "rgba(255, 0, 0,0.4)",
						timerProgressBar: true,
						showDenyButton: true,
						showCancelButton: false,
						confirmButtonText: "LANJUTKAN",
						denyButtonText: "MASUKAN IDENTITAS KEMBALI",
						timer: 5000,
						showClass: {
							popup: `
							  animate__animated
							  animate__fadeInUp
							  animate__faster
							`
						},
						hideClass: {
							popup: `
							  animate__animated
							  animate__fadeOutDown
							  animate__faster
							`
						}
					}).then((result) => {
						if (result.dismiss === Swal.DismissReason.timer) {
							$("#idpasantri").val("");
							$("#idpasantri").removeAttr("readonly");
						} else {
							if (Swal.DismissReason.close) {
								$("#idpasantri").val("");
								$("#idpasantri").removeAttr("readonly");
							} else {
								if (result.isConfirmed) {
									var urlParams = new URLSearchParams(window.location.search);
									var kode = urlParams.get('kode');

									if (kode === "E") {
										simpanantrian("D")
									} else {
										simpanantrian("G")
									}
								} else if (result.isDenied) {
									$("#idpasantri").val("");
									$("#idpasantri").removeAttr("readonly");
								}
							}

						}

					});
				}
			}
		}
	});
};

function simpanantrian(btn) {
	var kode = btn;
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
					icon: data.responHead,
					title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
					html: data.responHtml,
					timerProgressBar: true,
					showConfirmButton: false,
					timer: 5000,
					showClass: {
						popup: `
						  animate__animated
						  animate__fadeInUp
						  animate__faster
						`
					},
					hideClass: {
						popup: `
						  animate__animated
						  animate__fadeOutDown
						  animate__faster
						`
					}
				})
					.then((result) => {
						if (result.dismiss === Swal.DismissReason.timer) {
							$("#idpasantri").val("");
							$("#idpasantri").removeAttr("readonly");
						} else {
							if (Swal.DismissReason.close) {
								$("#idpasantri").val("");
								$("#idpasantri").removeAttr("readonly");
							}
						}
					});
			} else {
				Swal.fire({
					position: "center",
					// icon             : data.responHead,
					title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
					html: data.responHtml,
					backdrop: "rgba(255, 0, 0,0.4)",
					timerProgressBar: true,
					showConfirmButton: false,
					timer: 5000,
					showClass: {
						popup: `
						  animate__animated
						  animate__fadeInUp
						  animate__faster
						`
					},
					hideClass: {
						popup: `
						  animate__animated
						  animate__fadeOutDown
						  animate__faster
						`
					}
				}).then((result) => {
					if (result.dismiss === Swal.DismissReason.timer) {
						$("#idpasantri").val("");
						$("#idpasantri").removeAttr("readonly");
					} else {
						if (Swal.DismissReason.close) {
							$("#idpasantri").val("");
							$("#idpasantri").removeAttr("readonly");
						}
					}
				});
			}
		}
	});
	return false;
};

function validateTglLahir() {
	var validationTglLahir = $('#validationTglLahir').val();
	// Ambil nilai tanggal lahir asli dari data pasien
	var originalTglLahir = $('#tgllahir').val();

	if (validationTglLahir === originalTglLahir) {
		alert('Validasi berhasil!');
		$('#validationModal').modal('hide');
		// Lakukan proses setelah validasi berhasil
	} else {
		alert('Tanggal lahir tidak sesuai dengan Data Pasien!');
	}
}

getdatapasien();

function getdatapasien() {
	$.ajax({
		url: url + 'index.php/registpasienumum/getdatapasien',
		type: 'post',
		dataType: 'json',
		data: {
			pasienid: pasienid
		},
		success: function (res) {
			console.log(res);

			if (res) {
				const patientInfo = $('#patientInfo');
				//patientInfo.html('');  // Kosongkan konten sebelumnya

				const patientDetails = `
					<div class="form-group row">
							<label class="col-sm-4">Medical Record</label>
							<div class="col-sm-8">: <strong> ${res.INT_PASIEN_ID} </strong> </div>
							<label class="col-sm-4">Nama Pasien</label>
							<div class="col-sm-8">: <strong> ${res.NAMA} </strong> </div>
						</div>
					</div>
				`;
				patientInfo.html(patientDetails);
			}
			// else {
			// 	alert('Data Pasien tidak ditemukan');
			// }
		},
		error: function (xhr, status, error) {
			console.error('Error fetching patient data:', error);
		}
	})
}

// <p>
// 	Rekam Medis   : ${res.INT_PASIEN_ID}<br>
// 	Nama          : ${res.NAMA}<br>
// 	Tgl. Lahir    : ${res.TGLLAHIR}<br>
// 	No. Hp        : ${res.NOHP}<br>
// </p>

function total() {
	var nom = 0, total = 0;
	$("[id*='data-item']:checked").each(function () {
		nom = parseInt($(this).attr('data-nom'));
		total += nom;
	});
	$("#total-transaksi").text(total);
	$('.number').number(true, 0, '', '.');
}

let $interValid;
var tagihanid = '';

function cekstatusbayar() {
	//console.log(tagihanid);
	$.ajax({
		url: url + 'index.php/registpasienumum/cekstatusbayar',
		type: 'post',
		dataType: 'json',
		data: {
			tagihanid: tagihanid
		},
		success: function (res) {
			console.log(res);

			if (res.responseCode === '200') {
				clearInterval($interValid);
				location.href = url + 'index.php/registpasienumum/buktibayar?bayarid=' + res.bayarid + '&pasid=' + res.pasienid + '&subhead=antriankasir?head=' + head + '&head=' + head + '';
			}
		}
	})
}
function cekstatusqris() {
	$.ajax({
		url: url + 'index.php/registpasienumum/cekstatusqris',
		type: 'post',
		dataType: 'json',
		data: {
			tagihanid: tagihanid
		},
		success: function (res) {
			console.log(res);
			if (res.responseCode === '200') {
				clearInterval($interValid);
				location.href = url + 'index.php/registpasienumum/buktibayar?bayarid=' + res.bayarid + '&pasid=' + res.pasienid + '&subhead=antriankasir?head=' + head + '&head=' + head + '';
			}
		}
	})
}

function prosesbayarqris() {
	$("#data-content").html('');
	$("#qrcontent").html('');
	$("#modalqr").modal('show');

	/* console.log(layanid);
	return false; */

	$("#data-content").html('loading');
	$.ajax({
		url: url + 'index.php/registpasienumum/viaqris',
		type: 'post',
		data: {
			pasienid: pasienid,
			tanggal: $("#tanggalvalue").val(),
			dokterId: $("#dokter_id").val(),
			poliId: $("#poli_id").val(),
			jadwal: $("#jadwal").val(),
		},
		dataType: 'json',
		success: function (res) {
			//console.log(res);
			// return;

			if (res.responCode === '00') {
				var content = '';
				content = "<h2 class='fw-bold'>Rp<span class='number'>" + res.responBody.nominal + "</span></h2>";

				$("#data-content").html(content);
				$('.number').number(true, 0, '', '.');
				tagihanid = res.responAll.additionalInfo.bill_number;

				setInterval(() => { cekstatusbayar(); }, 3000);

				let options = {
					text: res.responQris,
					width: 350,
					height: 350,
					quietZone: 10,
					colorDark: "#000000",
					colorLight: "#ffffff",
					quietZoneColor: 'rgba(255,255,255,1)',
					correctLevel: QRCode.CorrectLevel.H,

					PO_TL: '', // Posotion Outer color - Top Left 
					PI_TL: '', // Posotion Inner color - Top Left 
					PO_TR: '', // Posotion Outer color - Top Right 
					PI_TR: '', // Posotion Inner color - Top Right 
					PO_BL: '', // Posotion Outer color - Bottom Left 
					PI_BL: '', // Posotion Inner color - Bottom Left 
				};

				// Create QRCode Object
				let qrCode = new QRCode(document.getElementById("qrcontent"), options);
			}
		},
		error: function () {
			alert('error');
		}
	})
}

function cekpembayaran() {

}