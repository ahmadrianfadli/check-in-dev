let activeInput = null;

document.querySelectorAll('input[type="text"]').forEach(input => {
	input.addEventListener('focus', function () {
		document.getElementById('keyboard').classList.remove('d-none');
		activeInput = this;
	});
});

function addToDisplay(value) {
	if (!activeInput) return;
	
	if (activeInput.id === "norm") {
		activeInput.value += value;
		return;
	}
	
	let rawValue = activeInput.value.replace(/\D/g, "");
	if (rawValue.length >= 8) return;
	
	rawValue += value;
	
	activeInput.value = formatDate(rawValue);
}

function clearinput() {
	if (!activeInput) return;
	
	if (activeInput.id === "norm") {
		activeInput.value = activeInput.value.slice(0, -1);
		return;
	}
	
	let rawValue = activeInput.value.replace(/\D/g, "");
	rawValue = rawValue.slice(0, -1);
	
	activeInput.value = formatDate(rawValue);
}

function formatDate(value) {
	if (value.length > 4) {
		return `${value.slice(0, 2)}/${value.slice(2, 4)}/${value.slice(4)}`;
	} else if (value.length > 2) {
		return `${value.slice(0, 2)}/${value.slice(2)}`;
	} else {
		return value;
	}
}

function isValidDateFormat(dateStr) {
	// Format yang benar: dd/mm/yyyy
	let regex = /^(\d{2})\/(\d{2})\/(\d{4})$/;
	let match = dateStr.match(regex);
	
	if (!match) return false;
	
	let day = parseInt(match[1], 10);
	let month = parseInt(match[2], 10);
	let year = parseInt(match[3], 10);
	
	// Validasi tanggal (cek apakah benar ada dalam kalender)
	let date = new Date(year, month - 1, day);
	return (
			date.getFullYear() === year &&
			date.getMonth() === month - 1 &&
			date.getDate() === day
	);
}

function proses() {
	let tgllahir = document.getElementById("tgllahir").value.trim();
	let tglkunjungan = document.getElementById("tglkunjungan").value.trim();
	
	// Validasi tanggal lahir & tanggal kunjungan sebelum AJAX dijalankan
	if (!isValidDateFormat(tgllahir) || !isValidDateFormat(tglkunjungan)) {
		Swal.fire({
			icon: "error",
			title: "Format Tanggal Salah",
			text: "Pastikan format tanggal lahir & tanggal kunjungan adalah dd/mm/yyyy. (contoh: 15/08/1980)",
		});
		return;
	}
	
	var form = $("#formcetaklab");
	$("#norm").attr("readonly", "true");
	
	Swal.fire({
		title: 'Loading...',
		text: 'Silakan tunggu',
		allowOutsideClick: false,
		didOpen: () => {
			Swal.showLoading();
		}
	});
	
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
					icon: data.responHead,
					title: data.responDesc,
					showCancelButton: true,
					confirmButtonText: 'Ya, Lanjutkan!',
					cancelButtonText: 'Tidak',
					reverseButtons: true
				}).then((result) => {
					if (result.isConfirmed) {
						requestDoc(); // Memanggil fungsi AJAX jika user pilih "Yes"
					}else{
						Swal.fire({
							position: "center",
							icon: data.responHead,
							html: 'Silahkan ke bagian pendaftaran untuk mengganti alamat email Anda',
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
							window.location.reload(true);

							$("#norm").val("");
							$("#norm").removeAttr("readonly");

							$("#tgllahir").val("");
							$("#tgllahir").removeAttr("readonly");

							$("#tglkunjungan").val("");
							$("#tglkunjungan").removeAttr("readonly");
						});
					}
				});
			} else {
				Swal.fire({
					position: "center",
					icon: data.responHead,
					html: data.responDesc,
					backdrop: "rgba(255, 0, 0,0.4)",
					timerProgressBar: true,
					showConfirmButton: false,
					timer: 20000,
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
					window.location.reload(true);

					$("#norm").val("");
					$("#norm").removeAttr("readonly");

					$("#tgllahir").val("");
					$("#tgllahir").removeAttr("readonly");

					$("#tglkunjungan").val("");
					$("#tglkunjungan").removeAttr("readonly");
				});
			}
		}
	});
}

function requestDoc(){
	var form = $("#formcetaklab");
	
	Swal.fire({
		title: 'Loading...',
		text: 'Mohon menunggu, kami sedang menyiapkan dokumen',
		allowOutsideClick: false,
		didOpen: () => {
			Swal.showLoading();
		}
	});
	
	$.ajax({
		url: base_url+"/cetaklab/reqdoc",
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
					html: data.responDesc,
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
					window.location.reload(true);

					$("#norm").val("");
					$("#norm").removeAttr("readonly");

					$("#tgllahir").val("");
					$("#tgllahir").removeAttr("readonly");

					$("#tglkunjungan").val("");
					$("#tglkunjungan").removeAttr("readonly");
				});
			} else {
				Swal.fire({
					position: "center",
					icon: data.responHead,
					html: data.responDesc,
					backdrop: "rgba(255, 0, 0,0.4)",
					timerProgressBar: true,
					showConfirmButton: false,
					timer: 20000,
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
					window.location.reload(true);

					$("#norm").val("");
					$("#norm").removeAttr("readonly");

					$("#tgllahir").val("");
					$("#tgllahir").removeAttr("readonly");

					$("#tglkunjungan").val("");
					$("#tglkunjungan").removeAttr("readonly");
				});
			}
		}
	});
}
