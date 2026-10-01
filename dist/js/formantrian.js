var urls = window.location.search;
var search = new URLSearchParams(urls);
var head = search.get('head');
var parent = search.get('parent');
var kode = search.get('kode');

$("#kodebooking").focus();

let kodebooking = document.getElementById("kodebooking");

function addToDisplay(value) {
    kodebooking.value += value;
}
function clear() {
    $("#kodebooking").val("");
    $("#kodebooking").removeAttr("readonly");
};
function clearinput() {
    // clear();
    var jumlahkode = document.getElementById('kodebooking').value;
    document.getElementById('kodebooking').value = jumlahkode.substring(0, jumlahkode.length - 1);
}

function proses() {
    var form = $("#formantrian");
    $.ajax({
        url: form.attr("action"),
        data: form.serialize(),
        method: "POST",
        dataType: "JSON",
        cache: false,
        beforeSend: function () {
            Swal.fire({
                position: "center",
                // icon             : data.responHead,
                title: "<h1 class='font-weight-bold' style='color:#234974;'>INFORMASI</h1>",
                html: "<h5 style='color:#234974;'>Mohon Menunggu Kami Sedang Melakukan Pencarian Data Anda</h5>",
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
            });
        },
        success: function (data) {
            if (data.responCode === "00") {

                Swal.fire({
                    position: "center",
                    icon: data.responHead,
                    title: "<h1 class='text-success font-weight-bold'>" + data.responTitle + "</h1>",
                    html: "<h1 class='text-success font-weight-bold'>" + data.responDesc + "</h1>",
                    timerProgressBar: true,
                    showConfirmButton: true,
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
                    },
                    didClose: () => {
                        var urlParams = new URLSearchParams(window.location.search);
                        var headValue = urlParams.get('head');
						if(headValue === 'antrianrad'){
							window.location.href = url + "index.php/antrianrad";	
						}else{
							window.location.href = url + "index.php/antriankasir?parent=A14&head=concept_5&subhead=antriankasir?head=concept_5&kode=N";	
						}
                    }
                });

            } else {
                Swal.fire({
                    position: "center",
                    icon: data.responHead,
                    title: "<h1 class='text-danger font-weight-bold'>GAGAL !!!</h1>",
                    html: "<h4 class='text-danger font-weight-bold'>" + data.responDesc + "</h4>",
                    backdrop: "rgba(255, 0, 0,0.4)",
                    timerProgressBar: true,
                    showConfirmButton: true,
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
                });
            }

            // Membersihkan input setelah pengiriman data
            clear();
        },
        error: function (jqXHR, exception) {
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = jqXHR.statusText + " [ " + jqXHR.status + " ] ";
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }

            // Menampilkan pesan kesalahan dengan toastr
            toastr["error"](msg, "Sorry !");

            // Menampilkan pesan kesalahan dengan SweetAlert
            Swal.fire({ position: 'center', icon: 'error', html: msg + "<br> Terjadi Kesalahan Silakan Scan Ulang", showConfirmButton: true, timer: 5000 });

            // Membersihkan input setelah pengiriman data
            clear();
        }
    });
}