var form = $("#formsurat");
var norm = $("#norm");
var nosep = '';
var poli = '';
var polibpjs = '';
var namapoli = '';
var dokterid = '';
var dokteridbpjs = '';
var namadokter = '';
var tglrencana = '';
var polirujukan = '';
var pasienid = '';
var nokunjungan = '';
function addToDisplay(value) {
    var val = norm.val() + value;
    norm.val(val)
};

function clearinput() {
    norm.val(norm.val().slice(0, -1));
};
$(document).ready(function () {
    var tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    var enddate = new Date();
    enddate.setDate(enddate.getDate() + 60);
    $('#tgl-kontrol').datepicker({
        startDate: tomorrow,
        endDate: enddate,
        format: 'yyyy-mm-dd',
        daysOfWeekHighlighted: '0',
        daysOfWeekDisabled: '0'
    }).on('changeDate', function (e) {
        tglrencana = $('#tgl-kontrol').datepicker("getFormattedDate");
        step(4);
        $("#nama-poli3").html(namapoli);
        $("#nama-dokter2").html(namadokter);
        $("#tgl-rencana").html(tglrencana);
    });
});
form.on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: form.serialize(),
        dataType: 'json',
        success: function (data) {
            console.log(data);
            if (data.responCode === "01") {
                nosep = '';
                pasienid = '';
                nokunjungan = '';
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
                        $("#norm").val("");
                    } else {
                        if (Swal.DismissReason.close) {
                            $("#norm").val("");
                        }
                    }
                });
            } else {
                nosep = data.responData.SEP_NOMOR;
                polirujukan = data.responData.POLIRUJUKAN_KDPOLI;
                pasienid = data.responData.PASIEN_ID;
                nokunjungan = data.responData.NOKUNJUNGAN;
                console.log(nosep);

                modalsurkon();
            }
        }
    });
})
function proses() {
    form.submit();
}
function getpoli() {
    console.log(polirujukan);
    $.ajax({
        url: url + 'index.php/Suratkontrol/getpoli',
        type: 'post',
        data: {
            polirujukan: polirujukan
        },
        dataType: 'json',
        success: function (res) {
            var html = '';
            if (res.code === '00') {
                html += "<div class='row g-4'>";
                res.data.map(function (d) {
                    html += "<div class='col-sm-4 d-grid'>";
                    html += "   <button class='btn btn-primary' style='font-size:28px;background-color:#234974;border-color:#234974;padding:10px 20px;' onclick='pilihpoli(this)' data-id='" + d.POLI_ID + "' data-id-bpjs='" + d.POLI_BPJSID + "' data-nama='" + d.KETERANGAN + "'>" + d.KETERANGAN + "</button>";
                    html += "</div>";
                })
                html += "</div>";
            } else {
                html += "<h1 class='text-secondary text-center'>Poli tidak tersedia</h1>";
            }

            $("#poli-kontrol").html(html);
        }
    });
}

function modalsurkon() {
    $("#modalsurkon").modal('show');
    getpoli();
    step(1);
    $("#btn-kembali").hide();
    $("#btn-proses").hide();
}
function pilihpoli(e) {
    poli = $(e).attr('data-id');
    polibpjs = $(e).attr('data-id-bpjs');
    namapoli = $(e).attr('data-nama');
    step(2);
    $("#nama-poli").html(namapoli);
    getdokter();
}
function getdokter() {
    // console.log(polibpjs);
    $.ajax({
        url: url + 'index.php/Suratkontrol/getdokter',
        data: {
            poli: poli,
        },
        type: 'post',
        dataType: 'json',
        success: function (res) {
            console.log(res);
            var html = '';
            if (res.code === '00') {
                html += "<div class='row g-4'>";
                res.data.map(function (d) {
                    html += "<div class='col-sm-12 d-grid'>";
                    html += "   <button class='btn btn-primary' style='font-size:28px;background-color:#234974;border-color:#234974;padding:10px 20px;' onclick='pilihdokter(this)' data-id='" + d.DOKTER_ID + "' data-id-bpjs='" + d.PREFIX + "' data-nama='" + d.NAMA + "'>" + d.NAMA + "</button>";
                    html += "</div>";
                })
                html += "</div>";
            } else {
                html += "<h1 class='text-secondary text-center'>Dokter tidak tersedia</h1>";
            }

            $("#dokter-kontrol").html(html);
        }
    });
}
function pilihdokter(e) {
    dokterid = $(e).attr('data-id');
    dokteridbpjs = $(e).attr('data-id-bpjs');
    namadokter = $(e).attr('data-nama');
    $("#nama-poli3").html(namapoli);
    $("#nama-dokter2").html(namadokter);
    $("#tgl-rencana").html(tglrencana);
    step(3);
    $("#nama-poli2").html(namapoli);
    $("#nama-dokter").html(namadokter);
}
function step(step) {
    console.log(step);
    if (step === '1') {
        $("#btn-kembali").hide();
    } else {
        $("#btn-kembali").show();
    }
    if (step === 4) {
        $("#btn-proses").show();
    } else {
        $("#btn-proses").hide();
    }
    $("#btn-kembali").attr('data-step', step - 1);
    $("[id*=step]").addClass('d-none').removeClass('animate__fadeInLeft');
    $("[id*=" + step + "]").removeClass('d-none');
    $("[id*=" + step + "]").addClass('animate__fadeInRight');
}
$("[data-step]").on('click', function () {
    var stepindex = $(this).attr('data-step');
    step(stepindex);
    /*  if (step !== '1') {
         $("#btn-kembali").attr('data-step', step - 1);
     } */
    $("[id*=step]").addClass('d-none').removeClass('animate__fadeInRight');
    $("[id*=" + stepindex + "]").removeClass('d-none');
    $("[id*=" + stepindex + "]").addClass('animate__fadeInLeft');
})
function simpansurkon() {
    $.ajax({
        url: url + 'index.php/Suratkontrol/createsuratkontrol',
        type: 'post',
        dataType: 'json',
        data: {
            nosep: nosep,
            poli: poli,
            polibpjs: polibpjs,
            dokterid: dokterid,
            dokteridbpjs: dokteridbpjs,
            tglrencana: tglrencana,
            pasienid: pasienid,
            nokunjungan: nokunjungan
        }, success: function (data) {
            console.log(data);

            if (data.responCode === "01") {
                nosep = '';
                Swal.fire({
                    position: "center",
                    icon: data.responHead,
                    title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
                    html: "<h2>" + data.responHtml + "</h2>",
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
                    $("#modalsurkon").modal('hide');
                    if (result.dismiss === Swal.DismissReason.timer) {
                        $("#norm").val("");
                    } else {
                        if (Swal.DismissReason.close) {
                            $("#norm").val("");
                        }
                    }
                    nosep = '';
                    poli = '';
                    polibpjs = '';
                    namapoli = '';
                    dokterid = '';
                    dokteridbpjs = '';
                    namadokter = '';
                    tglrencana = '';
                    polirujukan = '';
                    pasienid = '';
                    nokunjungan = '';
                });
            } else {
                Swal.fire({
                    position: "center",
                    icon: data.responHead,
                    title: "<h1 class='font-weight-bold' style='color:#234974;'>" + data.responDesc + "</h1>",
                    html: "<h2>" + data.responHtml + "</h2>",
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
                    $("#modalsurkon").modal('hide');
                    if (result.dismiss === Swal.DismissReason.timer) {
                        $("#norm").val("");
                    } else {
                        if (Swal.DismissReason.close) {
                            $("#norm").val("");
                        }
                    }
                    nosep = '';
                    poli = '';
                    polibpjs = '';
                    namapoli = '';
                    dokterid = '';
                    dokteridbpjs = '';
                    namadokter = '';
                    tglrencana = '';
                    polirujukan = '';
                    pasienid = '';
                    nokunjungan = '';
                });
            }
        }
    });
}