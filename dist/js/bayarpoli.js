var dh = $(window).height() - $(".fixed-bottom").outerHeight() - 400;
//document.addEventListener("DOMContentLoaded", function () {
var urlParams = new URLSearchParams(window.location.search);
var kode = urlParams.get('kode');
var pasienid = urlParams.get('pasienid');
var head = urlParams.get('head');
var subhead = urlParams.get('subhead');

$("#data-transaksi").css({
	'max-height': dh,
	'overflow': 'scroll'
});

function addToDisplay(value) {
	idpasantri.value += value;
};

function clearinput() {
	var idpasantri = $("#idpasantri").val();
	$("#idpasantri").val(idpasantri.slice(0, -1));
};

$(document).on("submit", "#formregpasien", function (e) {
	e.preventDefault();
	proses();
});

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
}

getdatapasien();
function getdatapasien() {
	$.ajax({
		url: url + 'index.php/bayarpoli/getdatapasien',
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
					<input type="hidden" id="pasien_id" name="pasien_id" value="${res.INT_PASIEN_ID}"/>
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

showPoli();
function showPoli() {
	$.ajax({
		url: url + 'index.php/bayarpoli/showPoli',
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

$('#poli_id').on('change', function () {
	cekbtn()
});

function cekbtn() {
	var poliId = $('#poli_id').val();
	if (poliId && poliId !== "null") {
		$(".btn-next").prop('disabled', false)
	} else {
		$(".btn-next").prop('disabled', true)
	}
}

let isProcessing = false;
function prosesbayarpoli(){
	if (isProcessing) return;
	isProcessing = true;
	
	$('.btn-next').prop('disabled', true);
	Swal.fire({
		title: 'Memproses...',
		text: 'Mohon tunggu',
		allowOutsideClick: false,
		allowEscapeKey: false,
		showConfirmButton: false,
		didOpen: () => {
			Swal.showLoading();
		}
	});
	
	var poliId = $('#poli_id').val();
	var pasien_id = $('#pasien_id').val();
	
	$.ajax({
		url: url + 'index.php/bayarpoli/antrianbayarpoli',
		type: 'post',
		dataType: 'json',
		data: {
			poliid: poliId,
			pasien_id: pasien_id,
		},
		success: function (res) {
			Swal.close(); // tutup loading
			
			if(res.responCode === "00"){
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
			}else{
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
		error: function(){
			Swal.close();
			
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Terjadi kesalahan, silakan coba lagi'
			});
		},
		complete: function(){
			isProcessing = false;
			$('.btn-next').prop('disabled', false);
		}
	});
}