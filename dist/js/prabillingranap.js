var dh = $(window).height() - $(".fixed-bottom").outerHeight() - 400;
//document.addEventListener("DOMContentLoaded", function () {
var urlParams = new URLSearchParams(window.location.search);
var kode = urlParams.get('kode');
var pasienid = urlParams.get('pasienid');

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

function proses() {
	var form = $("#formprabillingranap");
	$("#idpasantri").attr("readonly", "true");
	$.ajax({
		url: form.attr("action"),
		data: form.serialize(),
		method: "POST",
		dataType: "JSON",
		cache: false,
		success: function (data) {
			
			if (data.responCode === "00") {
				window.location = data.responurl;
				
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

$(document).on("submit", "#formantrikasir", function (e) {
	e.preventDefault();
	proses();
});

gettransaksi();
// alert(pasienid);
function gettransaksi() {
	alert('test');
	$.ajax({
		url: url + 'index.php/Prabillingigd/gettrranap',
		type: 'post',
		dataType: 'json',
		data: {
			pasienid: pasienid
		},
		success: function (res) {
			console.log(res);
			//return false;
			grandTotal = 0;

			for (let i in res) {
				let total = Number.parseInt(res[i].TOTAL);
			}

			var html = '';
			var total = 0;
			res.POLI.map(function (d) {

				html +=
					'<div class="card shadow-none"  id="item-poli">' +
					'	<div class="card-header text-center" style="background:linear-gradient(to right, #4aac90, #234974);color:#ffffff;position:sticky;top:0;z-index:1;"><strong> LIST TRANSAKSI </strong></div>' +
					'	<div class="card-body">';
					
				d.JENIS.map(function (e) {
					html += '<div class="fw-bold">' + e.GRUP + '</div>';
					html += '<table class="table mb-3">';

					e.DATA.map(function (f, i) {
						var disableInput  = '';

						html +=
							'<tr id="item-layan">' +
							'	<td>' +
							'		<div class="form-check">' +
							
							'			<label class="form-check-label" for="data-item-' + f.POLI_ID + '-' + f.JENIS + '-' + i + '">' + f.NAMALAYAN + '</label>' +
							'		</div>' +
							'	</td>' +
							'	<td width="300">' +
							'		<div class="row">' +
							'			<div class="col-sm-6 text-right">' +
							'				<span>Rp.</span>' +
							'			</div>' +
							'			<div class="col-sm-6 text-right">' +
							'				<span class="number">' + f.TOTAL + '</span>' +
							'			</div>' +
							'		</div>' +
							'	</td>' +
							'</tr>';
							total +=  parseInt(f.TOTAL);
					})
					html += '</tbody></table>';

				})
				html +=
					'	</div>' +
					'</div>';
			});
			
			$("#data-transaksi").html(html);
			$("#total-transaksi").text(total);
			//total();
			$('.number').number(true, 0, '', '.');
		}
	})
}
/* function total() {
	var nom = 0, total = 0;
	$("[id*='data-item']:checked").each(function () {
		nom = parseInt($(this).attr('data-nom'));
		total += nom;
	});
	$("#total-transaksi").text(total);
	$('.number').number(true, 0, '', '.');
} */