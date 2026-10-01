document.addEventListener("DOMContentLoaded", function () {
	var urlParams = new URLSearchParams(window.location.search);
	var kode = urlParams.get('kode');
	document.getElementById('kode').value = kode;
});


function addToDisplay(value) {
	idpasantri.value += value;
};

function clearinput() {
	var idpasantri = $("#idpasantri").val();
	$("#idpasantri").val(idpasantri.slice(0, -1));
};

function proses() {
	var form = $("#formantrikasir");
	$("#idpasantri").attr("readonly", "true");
	$.ajax({
		url: form.attr("action"),
		data: form.serialize(),
		method: "POST",
		dataType: "JSON",
		cache: false,
		success: function (data) {
			console.log(data);
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

function gettransaksipasien(){
    $.ajax({
        url     : url+"index.php/Antriangeneral/cektransaksipasien",
        data    : {
                    pasienid: pasienid
        },
        method  : "POST",
        dataType: "JSON",
        cache   : false,
        success: function(res) {
            console.log(res);

            // Lakukan sesuatu dengan data respons
            if (res && res.TOTAL > 0) {
                // Misalnya, tampilkan data transaksi pasien
                // atau update tampilan UI
                console.log("Transaksi ditemukan:", res);
            } else {
                // Jika tidak ada transaksi, tampilkan pesan atau lakukan tindakan lain
                console.log("Tidak ada data pembayaran");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error during AJAX request:", textStatus, errorThrown);
            // Tampilkan pesan error atau lakukan tindakan lain
        }
    });
}

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
				})
					.then((result) => {
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

$(document).on("submit", "#formantrikasir", function (e) {
	e.preventDefault();
	$("#idpasantri").attr("readonly", "true");
	var form = $(this), url = $(this).attr("action");
	$.ajax({
		url: url,
		data: form.serialize(),
		method: "POST",
		dataType: "JSON",
		cache: false,
		success: function (data) {

			if (data.responCode === "00") {
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
	return false;
});
