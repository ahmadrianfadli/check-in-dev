	var urlParams = new URLSearchParams(window.location.search);
	var kode      = urlParams.get('kode');
	var pasienid  = urlParams.get('pasienid');
	var kategori  = urlParams.get('kategori');

	switch (kategori) {
        case 'JKL-UMU':
            kategoriLabel = 'LAYANAN UMUM';
            break;
        case 'JKL-LAB':
            kategoriLabel = 'LABORATORIUM';
            break;
        case 'JKL-RAD':
            kategoriLabel = 'RADIOLOGI';
            break;
		case 'JKL-OPR':
			kategoriLabel = 'OPERASI';
			break;
        default:
            kategoriLabel = 'LAINNYA';
            break;
    }

	$('#kat').html(kategoriLabel);

	function redirectToDaftarHarga() {
		// window.location;
	}

	function addToDisplayX(value) {
		searchKeyword.value += value;
		textcari.value += value;
	};

	function clearinputx() {
		var searchKeyword = $("#searchKeyword").val();
		$("#searchKeyword").val(searchKeyword.slice(0, -1));

		var textcari = $("#textcari").val();
		$("#textcari").val(textcari.slice(0, -1));
	};

	function showSearchModal() {
		$("#searchKeyword").val('');
		$("#textcari").val('');
		$('#searchModal').modal('show');
	}
	
	$(document).ready(function() {
		showSearchModal();
	});

	$(document).on("submit", "#searchForm", function (e) {
		proses();
	});

	function formatCurrency(value) {
		return new Intl.NumberFormat('id-ID', {
			style                : 'currency',
			currency             : 'IDR',
			currencyDisplay      : 'symbol',
			maximumFractionDigits: 0,
		}).format(value);
	}

	function caridata() {
		var carikata = $("#searchKeyword").val();
		$.ajax({
			url     : url + 'index.php/daftarharga/cariDataLayan',
			data    : {carikata: carikata,kategori: kategori},
			method  : "POST",
			dataType: "JSON",
			cache   : false,
			success: function (res) {

				console.log(res);

				if (res && res.length > 0) {
					$("#searchModal").modal('hide');

					var $resultContainer = $("#resultContainer");
					$resultContainer.empty(); // Clear existing results
					var card = '<div class="accordion accordion-flush" id="accordionFlushExample">';
					res.forEach(function(item,i) {
						
						var hargaRJ     = item.RJ ? formatCurrency(item.RJ) : 'N/A';
						var hargaKelas1 = item.KELAS1 ? formatCurrency(item.KELAS1) : 'N/A';
						var hargaKelas2 = item.KELAS2 ? formatCurrency(item.KELAS2) : 'N/A';
						var hargaKelas3 = item.KELAS3 ? formatCurrency(item.KELAS3) : 'N/A';
						var hargaVIP    = item.VIP ? formatCurrency(item.VIP) : 'N/A';
						var hargaVVIP   = item.VVIP ? formatCurrency(item.VVIP) : 'N/A';
						
						card += `
						
							<div class="accordion-item">
								<h2 class="accordion-header" id="flush-headingOne">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-`+i+`" aria-expanded="false" aria-controls="flush-collapseOne" style="font-size:20px">
									`+item.NAMALAYAN+`
								</button>
								</h2>

								<div id="flush-`+i+`" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
									<div class="accordion-body table-responsive px-4">
										<table class="table table-striped table-bordered">
											<tbody>
												<tr class='text-center'>
													<th>Rawat Jalan</th>
													<th>Kelas 1</th>
													<th>Kelas 2</th>
													<th>Kelas 3</th>
													<th>Kelas VIP</th>
													<th>Kelas VVIP</th>
												</tr>
												<tr>
													<td style="font-size: 20px;">`+hargaRJ+`</td>
													<td style="font-size: 20px;">`+hargaKelas1+`</td>
													<td style="font-size: 20px;">`+hargaKelas2+`</td>
													<td style="font-size: 20px;">`+hargaKelas3+`</td>
													<td style="font-size: 20px;">`+hargaVIP+`</td>
													<td style="font-size: 20px;">`+hargaVVIP+`</td>
												</tr>										
											</tbody>
										</table>
										<p style="color: red; font-style: italic; font-size:20px"><strong>*harga CITO = 1.5 x harga yang tertera</strong></p>
									</div>	
								</div>
							</div>

						`;
					});
					card += '</div>';

					$resultContainer.html(card);
					$(".card-header").click(function() {
						$(this).next(".card-body").slideToggle();
					});
				} else {
					alert('Data tidak ditemukan.');
				}
			},
			error: function(xhr, status, error) {
				console.error('Error fetching data: ', error);
				alert('An error occurred while fetching data.');
			}
		});
	}
	
	function proses() {
		caridata();
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
	
	function gettransaksi() {
		$.ajax({
			url: url + 'index.php/kasirrj/gettransaksi',
			type: 'post',
			dataType: 'json',
			data: {
				pasienid: pasienid
			},
			success: function (res) {
				console.log(res);
				grandTotal = 0;
	
				for (let i in res) {
					let total = Number.parseInt(res[i].TOTAL);
				}
	
				var html = '';
				res.POLI.map(function (d) {
	
					html +=
						'<div class="card shadow-none"  id="item-poli">' +
						'	<div class="card-header text-center" style="background:linear-gradient(to right, #4aac90, #234974);color:#ffffff;position:sticky;top:0;z-index:1;"><strong>' + d.NAMAPOLI + '</strong></div>' +
						'	<div class="card-body">';
					d.JENIS.map(function (e) {
						html += '<div class="fw-bold">' + e.GRUP + '</div>';
						html += '<table class="table mb-3">';
	
						e.DATA.map(function (f, i) {
							var disableInput = '';
	
							if (e.ID === 'A') {
								disableInput = 'disabled';
							}
	
							html +=
								'<tr id="item-layan">' +
								'	<td>' +
								'		<div class="form-check">' +
								'			<input class="form-check-input" ' + disableInput + ' type="checkbox" onchange="total()" data-nom="' + f.TOTAL + '" data-epiid="' + f.EPISODE_ID + '" data-pasienid="' + f.PASIEN_ID + '" data-rekanan="' + f.REKANAN_ID + '" data-poli="' + f.POLI_ID + '" data-dokter="' + f.DOKTER_ID + '" data-transco = "' + f.TRANS_CO + '" data-jenis="' + f.JENIS + '" data-idlayan="' + f.IDLAYAN + '" data-namalayan="' + f.NAMALAYAN + '" data-qty="' + f.QTY + '" data-hargalayan="' + f.TOTAL + '" data-wajib="' + f.WAJIB + '" data-cito="' + f.CITO + '" id="data-item-' + f.POLI_ID + '-' + f.JENIS + '-' + i + '" checked>' +
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
						})
						html += '</tbody></table>';
					})
					html +=
						'	</div>' +
						'</div>';
	
					/* d.JENIS.map(function (e) {
						console.log(e);
						e.DATA.map(function (f) {
							console.log(f);
						})
					}) */
				});
	
				$("#data-transaksi").html(html);
				total();
				$('.number').number(true, 0, '', '.');
			}
		})
	}

	function total() {
		var nom = 0, total = 0;
		$("[id*='data-item']:checked").each(function () {
			nom = parseInt($(this).attr('data-nom'));
			total += nom;
		});
		$("#total-transaksi").text(total);
		$('.number').number(true, 0, '', '.');
	}
	
	function cekstatusqris() {
		$.ajax({
			url: url + 'index.php/kasirrj/cekstatusbayar',
			type: 'post',
			//dataType: 'json',
			data: {
				pasienid: pasienid
			},
			success: function (res) {
				console.log(res);
			}
		})
	}
	
	function prosesbayarqris() {
		$("#data-content").html('');
		$("#qrcontent").html('');
		$("#modalqr").modal('show');
	
		var tombolkasir = document.getElementById('btn-kasir');
		var tombolqris = document.getElementById('btn-qris');
		tombolkasir.style.display = 'none';
		tombolqris.style.display = 'none';
	
		var nom = 0, totalnums = 0;
		var epiid = [], poliid = [], rekid = [],
			dokterid = [], transco = [], jenis = [],
			namalayan = [], qty = [], harga = [], iscito = [];
		var items = [];
		var layanid = [];
		var wajib = [];
	
	
		/* $("#item-poli").each(function(i) {
			epiid = $(this).attr('episode');
			console.log(epiid);
			layanid[i] = []; */
		$("[id*='data-item']:checked").each(function (i) {
			nom = parseInt($(this).attr('data-nom'));
			totalnums += nom;
			epiid = $(this).attr('data-epiid');
			pasienid = $(this).attr('data-pasienid');
			rekid = $(this).attr('data-rekanan');
			poliid = $(this).attr('data-poli');
			dokterid = $(this).attr('data-dokter');
			transco = $(this).attr('data-transco');
			layanid = $(this).attr('data-idlayan');
			namalayan = $(this).attr('data-namalayan');
			qty = $(this).attr('data-qty');
			harga = $(this).attr('data-hargalayan');
			iscito = $(this).attr('data-cito');
			jenis = $(this).attr('data-jenis');
			wajib = $(this).attr('data-wajib');
			items[i] = {
				"epiid": epiid,
				'pasienid': pasienid,
				"rekid": rekid,
				"poliid": poliid,
				"dokterid": dokterid,
				"transco": transco,
				"layanid": layanid,
				"namalayan": namalayan,
				"qty": qty,
				"harga": harga,
				"iscito": iscito,
				"jenis": jenis,
				"wajib": wajib,
			};
		});
		/* 	}) */
		/* console.log(items);
		return false; */
	
		console.log(items);
	
		/* console.log(layanid);
		return false; */
		$.ajax({
			url: url + 'index.php/kasirrj/viaqris',
			type: 'post',
			data: {
				nominal: totalnums,
				pasienid: pasienid,
				items: items
			},
			dataType: 'json',
			success: function (res) {
				console.log(res);
				// return false;
				// return false; */
				if (res.responCode === '00') {
					var content = '';
					content = "<h2 class='fw-bold'>Rp<span class='number'>" + res.responBody.nominal + "</span></h2>";
	
					// content += res.responQris;
					// content += res.responAll.qrContent;
	
					$("#data-content").html(content);
					$('.number').number(true, 0, '', '.');
					let options = {
						text: res.responQris,
						width: 350,
						height: 350,
						quietZone: 10,
						colorDark: "#000000",
						colorLight: "#ffffff",
						quietZoneColor: 'rgba(255,255,255,1)',
						correctLevel: QRCode.CorrectLevel.H,
						//logo: rsudLogoURL,
						//logoWidth: 80,
						//logoHeight: 80,
						//PO: '#4aa88f', // Global Posotion Outer color. if not set, the defaut is colorDark
						//PI: '#254a73', // Global Posotion Inner color. if not set, the defaut is colorDark
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
			}
		})
	}