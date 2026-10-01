//document.addEventListener("DOMContentLoaded", function () {
	var urlParams = new URLSearchParams(window.location.search);
	var kode = urlParams.get('kode');
	var pasienid = urlParams.get('pasienid');
	var head = urlParams.get('head');
	//document.getElementById('kode').value = kode;
	//});
	
	function addToDisplay(value) {
		idpasantri.value += value;
	};
	
	function clearinput() {
		var idpasantri = $("#idpasantri").val();
		$("#idpasantri").val(idpasantri.slice(0, -1));
	};
	
	function proses() {
		var form = $("#formbayarqris");
		$("#idpasantri").attr("readonly", "true");
		$.ajax({
			url: form.attr("action"),
			data: form.serialize()+'&head='+head,
			method: "POST",
			dataType: "JSON",
			cache: false,
			success: function (data) {
				console.log(data);
				if (data.responCode === "01") {
					Swal.fire({
						position: "center",
						icon: data.responHead,
						title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
						html: data.responHtml,
						
						customClass: {
							popup:'swal-wide',
							confirmButton: "btn-xl",
							cancelButton: "btn-xl"
						  },
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
						var urlParams = new URLSearchParams(window.location.search);
						var headValue = urlParams.get('head');
						window.location.href = headValue;
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
					window.location.href = data.responurl;
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
	


	gettransaksi();
	
	function gettransaksi() {
		$.ajax({
			url: url + 'index.php/bayarqris/gettransaksi',
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
						var disableInput = '';

						if (e.ID === 'A') {
							disableInput = 'disabled';
						}
						html += '<div id="item-group">';
						html += '<div class="fw-bold form-check form-check-inline"><input ' + disableInput + ' id="check-all" onchange="checkall(this)" class="form-check-input" type="checkbox" checked/><label class="form-check-label">' + e.GRUP + '</label></div>';
						html += '<table class="table mb-3">';
	
						e.DATA.map(function (f, i) {
													
	
							html +=
								'<tr id="item-layan">' +
								'	<td>' +
								'		<div class="form-check ml-4">' +
								'			<input class="form-check-input" ' + disableInput + ' type="checkbox" onchange="total(this)" data-nom="' + f.TOTAL + '" data-epiid="' + f.EPISODE_ID + '" data-pasienid="' + f.PASIEN_ID + '" data-rekanan="' + f.REKANAN_ID + '" data-poli="' + f.POLI_ID + '" data-dokter="' + f.DOKTER_ID + '" data-transco = "' + f.TRANS_CO + '" data-jenis="' + f.JENIS + '" data-idlayan="' + f.IDLAYAN + '" data-namalayan="' + f.NAMALAYAN + '" data-qty="' + f.QTY + '" data-hargalayan="' + f.HARGA + '" data-wajib="' + f.WAJIB + '" data-cito="' + f.CITO + '" id="data-item-' + f.POLI_ID + '-' + f.JENIS + '-' + i + '" checked>' +
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
						html += '</div>';
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
	function total(e) {
		var pr = $(e).parents("#item-group");
		if(pr.find("[id*='data-item']").length === pr.find("[id*='data-item']:checked").length) {
			pr.find('#check-all').prop('checked',true);
		} else{
			pr.find('#check-all').prop('checked',false);
		}
		var nom = 0, total = 0;
		$("[id*='data-item']:checked").each(function () {
			nom = parseInt($(this).attr('data-nom'));
			total += nom;
		});
		$("#total-transaksi").text(total);
		$('.number').number(true, 0, '', '.');
	}

	function checkall(e) {
		var pr = $(e).parents("#item-group")
		if($(e).prop('checked')) {
		
		pr.find("[id*='data-item']").prop('checked',true);
		} else {
			pr.find("[id*='data-item']").prop('checked',false);
		}
		total()
	}

	let $interValid;
	var tagihanid = '';
	function cekstatusqris() {
			console.log(tagihanid);
		console.log('Checking QRIS Status...');

		$.ajax({
			url: url + 'index.php/bayarqris/cekstatusbayar',
			type: 'post',
			dataType: 'json',
			data: {
				tagihanid: tagihanid
			},
			success: function (res) {
				console.log(res);

				if (res.responseCode === '200') {
					clearInterval($interValid);
					location.href = url+'index.php/bayarqris/buktibayar?bayarid='+res.bayarid+'&pasid='+pasienid+'&subhead=antriankasir?head='+head+'&head='+head+'';
				}
			}
		})
	}
	
	function prosesbayarqris() {
		$("#data-content").html('');
		$("#qrcontent").html('');
		$("#modalqr").modal('show');
	
		// var tombolkasir = document.getElementById('btn-kasir');
		var tombolqris = document.getElementById('btn-qris');
		// tombolkasir.style.display = 'none';
		tombolqris.style.display = 'none';
	
		var nom = 0, totalnums = 0;
		var epiid = [], poliid = [], rekid = [],
			dokterid = [], transco = [], jenis = [],
			namalayan = [], qty = [], harga = [], iscito = [];
		var items = [];
		var layanid = [];
		var wajib = [];
	
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
						"pasienid": pasienid,
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
						"total" : totalnums
			};
		});
		/* console.log(layanid);
		return false; */
		$("#data-content").html('loading');
		$.ajax({
			url: url + 'index.php/bayarqris/viaqris',
			type: 'post',
			data: {
				nominal: totalnums,
				pasienid: pasienid,
				items: items,
				jenis: 'qris'
			},
			dataType: 'json',
			success: function (res) {

				if (res.responCode === '00') {
					tagihanid = res.responAll.additionalInfo.bill_number;
					var content = '';
					content = "<h2 class='fw-bold'>Rp<span class='number'>"+res.responBody.nominal+"</span></h2>";
	
					$("#data-content").html(content);
					$('.number').number(true, 0, '', '.');

					$interValid = setInterval(() => {cekstatusqris();}, 3000);

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
			},
			error:function() {
				alert('error');
			}
		})
	}

	function prosesbayarkasir() {
		var form = $("#formantrikasir");
		$("#idpasantri").attr("readonly", "true");
		$.ajax({
			url: form.attr("action"),
			data: form.serialize(),
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
						/* timerProgressBar: true,
						showConfirmButton: false,
						timer: 5000, */
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
						var urlParams = new URLSearchParams(window.location.search);
						var headValue = urlParams.get('head');
						window.location.href = headValue;
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