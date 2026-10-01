$("#kodebookingcheckin").focus();
moment.locale('id');
function addToDisplay(value) {
	kodebookingcheckin.value += value;
}

function clearinput() {
	var kodebookingcheckin = $("#kodebookingcheckin").val();
	$("#kodebookingcheckin").val(kodebookingcheckin.slice(0, -1));
};

$('#modalconcent').on('hidden.bs.modal', function () {
	$("#modalconcent input[type='checkbox']").prop('checked', false);
	$("#modalconcent input[type='checkbox']").prop('disabled', false);
	$('#modalconcent [class="form-check"]').find('label.invalid').show();
	$("#kodebookingcheckin").focus();
})

var wrapper = document.getElementById("signature-pad");
var canvas = wrapper.querySelector("canvas");
var clearButton = wrapper.querySelector("[data-action=clear]");
let signaturePad = new SignaturePad(canvas, {
	backgroundColor: 'rgb(255, 255, 255)'/* ,
	minWidth: 5,
	maxWidth: 10 */
});
var pasienid;
var nik;
var updatenik = false;
function generalconsent(data) {
	var noka = data.BPJS_NO;
	var jmlepisode = data.JMLEPISODE;
	var hub = data.CONCENT_HUBPASIEN;
	var nilai = data.CONCENT_NILAI;
	var parsehub = hub ? $.parseJSON(hub) : '';
	var parsenilai = nilai ? $.parseJSON(nilai) : '';
	$("#div-detail-consent").remove();
	/* 	if (jmlepisode === '0') {
			$("#div-detail-consent").remove();
		}
	
		if (jmlepisode > '0') {
			$("#tableconsent").hide();
		} */
	if (parsehub) {
		if (parsehub.keluarga_inti === 'Y') {
			$("#keluarga_inti").prop('checked', true);
		}
		if (parsehub.penjamin_asuransi === 'Y') {
			$("#penjamin_asuransi").prop('checked', true);
		}
		if (parsehub.wali === 'Y') {
			$("#wali").prop('checked', true);
		}
	}
	if (parsenilai) {
		if (parsenilai.tdk_makan_daging_sapi === 'Y') {
			$("#tdk_makan_daging_sapi").prop('checked', true);
		}
		if (parsenilai.tdk_menggunakan_obat_unsur_babi === 'Y') {
			$("#tdk_menggunakan_obat_unsur_babi").prop('checked', true);
		}
		if (parsenilai.tdk_mau_imunisasi === 'Y') {
			$("#tdk_mau_imunisasi").prop('checked', true);
		}
		if (parsenilai.perlakuan_khusus_meninggal === 'Y') {
			$("#perlakuan_khusus_meninggal").prop('checked', true);
		}
	}

	if (data.ACC_SATUSEHAT === 'Y') {
		$("#setuju_1").prop('checked', true).prop('disabled', true);
	}
	if (data.ACC_SATUSEHAT_FASKES === 'Y') {
		$("#setuju_2").prop('checked', true).prop('disabled', true);
	}
	if (data.ADACONCENT > 0) {
		$("#setuju_3").prop('checked', true).prop('disabled', true);
		$("#setuju_3").closest('.form-check').find('label.invalid').hide();
	}

	/* if (jmlepisode > 0) {
		$("#tableconcent").hide();
		$('#div-detail-concent').show();
	} else {
		$("#tableconcent").show();
		$("#div-detail-concent").hide();
	} */
	if (isNaN(data.NO_IDENTITAS)) {
		$.ajax({
			url: url + 'index.php/checkinkiosk/getpesertabynoka',
			type: 'POST',
			dataType: 'JSON',
			async: false,
			data: {
				noka: noka
			},
			success: function (res) {
				console.log(res);
				if (res.success) {
					nik = res.data.pasien.nik;
					updatenik = true;
				}
			}
		});
	} else {
		nik = data.NO_IDENTITAS;
	}
	$("#kodebooking").attr("readonly", "true");
	$("#modalconcent").modal('show');
	$("#modalconcent #namapasien").text(data.NAMAPASIEN);
	$("#modalconcent #norm").text(data.INT_PASIEN_ID);
	$("#modalconcent #tgllahir").text(moment(data.TGLLAHIR).format('LL'));
	$("#modalconcent #nik").text(nik);
	$("#modalconcent #alamat").text(data.ALAMAT1);
	$("#modalconcent #ttl").text(data.TEMPAT_LAHIR_TXT + ' / ' + moment(data.TGLLAHIR).format('LL'));
	pasienid = data.PASIEN_ID;
	setTimeout(() => {
		resizeCanvas();
	}, 1000);
}
var signlength = 0;
function getlengthpad() {
	var datasign = signaturePad.toData();
	console.log(datasign);
	signlength = 0;
	datasign.map(function (d) {
		signlength += d.points.length;
	})
	if (signaturePad.isEmpty()) {
		$("#signature-pad-canvas").css('border-color', '#dc3545')
		$("#notif-sign-valid").addClass("text-danger").removeClass("text-primary").html("TTD belum ada");
	} else if (signlength < 20) {
		$("#signature-pad-canvas").css('border-color', '#dc3545')
		$("#notif-sign-valid").addClass("text-danger").removeClass("text-primary").html("TTD terlalu kecil / tidak valid, ulangi lagi");
	}/*  else if (signlength > 80) {
        $("#signature-pad-canvas").css('border-color', '#dc3545')
        $("#notif-sign-valid").addClass("text-danger").removeClass("text-primary").html("TTD terlalu besar / tidak valid, ulangi lagi");
    } */ else {
		$("#signature-pad-canvas").css('border-color', '#0069d9')
		$("#notif-sign-valid").addClass("text-primary").removeClass("text-danger").html("TTD valid");
	}
	activebuttonsubmit();
}
signaturePad.addEventListener("endStroke", () => {
	console.log("Signature started");
	getlengthpad();
});
$("input[type='checkbox']").on('change', function () {
	if ($(this).is(':checked')) {
		$(this).closest('li').find('label.invalid').hide()
	} else {
		$(this).closest('li').find('label.invalid').show()
	}
	activebuttonsubmit();
})
var keluarga_inti, penjamin_asuransi, wali, setuju_1, setuju_2, setuju_3, tdk_makan_daging_sapi, tdk_menggunakan_obat_unsur_babi, tdk_mau_imunisasi, perlakuan_khusus_meninggal;
function activebuttonsubmit() {
	keluarga_inti = $("#keluarga_inti:checked").val();
	penjamin_asuransi = $("#penjamin_asuransi:checked").val();
	wali = $("#wali:checked").val();
	tdk_makan_daging_sapi = $("#tdk_makan_daging_sapi:checked").val();
	tdk_menggunakan_obat_unsur_babi = $("#tdk_menggunakan_obat_unsur_babi:checked").val();
	tdk_mau_imunisasi = $("#tdk_mau_imunisasi:checked").val();
	perlakuan_khusus_meninggal = $("#perlakuan_khusus_meninggal:checked").val();
	setuju_1 = $("#setuju_1:checked").val();
	setuju_2 = $("#setuju_2:checked").val();
	setuju_3 = $("#setuju_3:checked").val();

	//$("#btn-lanjut").prop('disabled', true);
	if (setuju_3 === 'Y') {
		if (signlength < 20) {
			$("#btn-lanjut").prop('disabled', true);
		} else {
			$("#btn-lanjut").prop('disabled', false);
		}
	} else {
		$("#btn-lanjut").prop('disabled', true);
	}

}
function resizeCanvas() {
	const ratio = Math.max(window.devicePixelRatio || 1, 1);

	/* 	$('#signature-pad-canvas').css('width', '100%');
		let padWidth = $('#signature-pad-canvas').get(0).clientWidth;
		$('#signature-pad-canvas').css('height', (padWidth * 2) / 3); */
	$('#signature-pad-canvas').css('width', 600);
	let padWidth = $('#signature-pad-canvas').get(0).clientWidth;
	$('#signature-pad-canvas').css('height', 400);

	canvas.width = canvas.offsetWidth * ratio;
	canvas.height = canvas.offsetHeight * ratio;

	canvas.getContext("2d").scale(ratio, ratio);

	signaturePad.clear();
	signlength = 0;
	getlengthpad();
};
clearButton.addEventListener("click", function (event) {
	signaturePad.clear();
	signlength = 0;
	getlengthpad();
});


function detailconsent() {
	$("#tableconsent").show();
	$("#detail-consent").attr('onclick', 'hideconsent()');
	$("#detail-consent").text('Tutup General Consent');
}
function hideconsent() {
	$("#tableconsent").hide();
	$("#detail-consent").attr('onclick', 'detailconsent()');
	$("#detail-consent").text('Lihat General Consent');
}
function simpanconsent() {
	var sign = signaturePad.isEmpty() ? '' : signaturePad.toDataURL();
	sign = sign.split(',');
	$.ajax({
		type: "POST",
		dataType: 'JSON',
		url: url + 'index.php/checkinkiosk/simpanconcent',
		data: {
			signature: sign[1],
			keluarga_inti: keluarga_inti,
			penjamin_asuransi: penjamin_asuransi,
			wali: wali,
			tdk_makan_daging_sapi: tdk_makan_daging_sapi,
			tdk_menggunakan_obat_unsur_babi: tdk_menggunakan_obat_unsur_babi,
			tdk_mau_imunisasi: tdk_mau_imunisasi,
			perlakuan_khusus_meninggal: perlakuan_khusus_meninggal,
			pasienid: pasienid,
			nik: nik,
			updatenik: updatenik,
			setuju_1: setuju_1,
			setuju_2: setuju_2,
			setuju_3: setuju_3
		},
		success: function (data) {
			console.log(data);
			signaturePad.clear();
			signlength = 0;
			getlengthpad();
			if (data.responCode === "01") {
				Swal.fire({
					position: "center",
					icon: data.responHead,
					title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
					html: data.responHtml,
					backdrop: "rgba(255, 0, 0,0.4)",
					timerProgressBar: true,
					showConfirmButton: false,
					timer: 2000,
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
						$("#kodebookingcheckin").val("");
						$("#kodebookingcheckin").removeAttr("readonly");
					} else {
						if (Swal.DismissReason.close) {
							$("#kodebookingcheckin").val("");
							$("#kodebookingcheckin").removeAttr("readonly");
						}
					}
				});
			} else {
				$("#modalconcent").modal('hide');
				Swal.fire({
					position: "center",
					icon: data.responHead,
					title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
					html: data.responHtml,
					timerProgressBar: true,
					showConfirmButton: false,
					timer: 2000,
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
					checkin();
					if (result.dismiss === Swal.DismissReason.timer) {
						$("#kodebookingcheckin").val("");
						$("#kodebookingcheckin").removeAttr("readonly");
					} else {
						if (Swal.DismissReason.close) {
							$("#kodebookingcheckin").val("");
							$("#kodebookingcheckin").removeAttr("readonly");
						}
					}
				});

			}
		},
		error: function (xhr, status, error) {
			console.error("Error saving signature: " + error);
		}
	});
}
function checkin() {
	//$("#formcheckin").submit();
	/* console.log('checkin');
	return false; */
	var form = $("#formcheckin");
	$.ajax({
		url: form.attr("action"),
		data: form.serialize(),
		method: "POST",
		dataType: "JSON",
		cache: false,
		success: function (data) {
			
			if (data.responCode === "01") {
				Swal.fire({
					position: "center",
					icon: data.responHead,
					title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
					html: data.responHtml,
					backdrop: "rgba(255, 0, 0,0.4)",
					timerProgressBar: true,
					showConfirmButton: false,
					timer: 3500,
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
						$("#kodebookingcheckin").val("");
						$("#kodebookingcheckin").removeAttr("readonly");
						$("#kodebookingcheckin").focus();
					} else {
						if (Swal.DismissReason.close) {
							$("#kodebookingcheckin").val("");
							$("#kodebookingcheckin").removeAttr("readonly");
							$("#kodebookingcheckin").focus();
						}
					}
				});
			} else {
				Swal.fire({
					position: "center",
					icon: data.responHead,
					title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
					html: data.responHtml,
					timerProgressBar: true,
					showConfirmButton: false,
					timer: 3500,
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
						$("#kodebookingcheckin").val("");
						$("#kodebookingcheckin").removeAttr("readonly");
						$("#kodebookingcheckin").focus();
					} else {
						if (Swal.DismissReason.close) {
							$("#kodebookingcheckin").val("");
							$("#kodebookingcheckin").removeAttr("readonly");
							$("#kodebookingcheckin").focus();
						}
					}
				});
			}
		}
	});
}

$(document).on("submit", "#formcheckin", function (e) {
	e.preventDefault();
	var kodebookingcheckin = $("#kodebookingcheckin").val();
	$.ajax({
		url: url + 'index.php/checkinkiosk/cekgeneralconsent',
		type: 'post',
		dataType: 'JSON',
		cache: false,
		data: {
			kodebookingcheckin: kodebookingcheckin
		},
		success: function (data) {
			if (data.responCode === "01") {
				Swal.fire({
					position: "center",
					icon: data.responHead,
					title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
					html: data.responHtml,
					backdrop: "rgba(255, 0, 0,0.4)",
					timerProgressBar: true,
					showConfirmButton: false,
					timer: 2000,
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
						$("#kodebookingcheckin").val("");
						$("#kodebookingcheckin").removeAttr("readonly");
						$("#kodebookingcheckin").focus();
					} else {
						if (Swal.DismissReason.close) {
							$("#kodebookingcheckin").val("");
							$("#kodebookingcheckin").removeAttr("readonly");
							$("#kodebookingcheckin").focus();
						}
					}
				});
			} else if (data.responCode === "99") {
				generalconsent(data.responData);
			} else {
				checkin();
			}
		}
	});
	//checkin();
	/* $("#kodebooking").attr("readonly", "true");
	
	var form = $(this);
	var url = $(this).attr("action");
	$.ajax({
		url: url,
		data: form.serialize(),
		method: "POST",
		dataType: "JSON",
		cache: false,
		success: function (data) {
			if (data.responCode === "01") {
				Swal.fire({
					position: "center",
					icon: data.responHead,
					title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
					html: data.responHtml,
					backdrop: "rgba(255, 0, 0,0.4)",
					timerProgressBar: true,
					showConfirmButton: false,
					timer: 2000,
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
						$("#kodebookingcheckin").val("");
						$("#kodebookingcheckin").removeAttr("readonly");
					} else {
						if (Swal.DismissReason.close) {
							$("#kodebookingcheckin").val("");
							$("#kodebookingcheckin").removeAttr("readonly");
						}
					}
				});
			} else if (data.responCode === '99') {
				generalconsent(data.responData);
			} else {
				Swal.fire({
					position: "center",
					icon: data.responHead,
					title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
					html: data.responHtml,
					timerProgressBar: true,
					showConfirmButton: false,
					timer: 2000,
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
						$("#kodebookingcheckin").val("");
						$("#kodebookingcheckin").removeAttr("readonly");
					} else {
						if (Swal.DismissReason.close) {
							$("#kodebookingcheckin").val("");
							$("#kodebookingcheckin").removeAttr("readonly");
						}
					}
				});
			}
		}
	});
	return false; */
});
