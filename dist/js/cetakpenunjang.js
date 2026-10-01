var urls = window.location.search;
var search = new URLSearchParams(urls);
var head = search.get('head');
var parent = search.get('parent');
var kode = search.get('kode');

masterantrian();
//update
// setInterval(function () {
//     window.location.reload();
// }, 6000000);

function masterantrian() {
    $("#daftarcetak").html("");
    $.ajax({
        url: url + "index.php/Cetakpenunjang/mastercetak",
        method: "POST",
        dataType: "JSON",
        data: { head: head, parent: parent , kode: kode },
        cache: false,
        success: function (data) {
            console.log(data);
            var daftarcetak = "";
            
            if (data.responCode == "00") {
                result = data.responResult;
                
                for (var i in result) {
                    daftarcetak += "<div class='col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn'>";
                    if (result[i].ADAANAK > '0') {
                        daftarcetak += "<a href='./antriankasir?parent=" + result[i].JENIS_ID + "&head=" + head + "&subhead=antriankasir?head=" + head + "&kode=" + result[i].KODE + "' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
                    } else {
                        var parent2 = ''
                        if (parent) {
                            parent2 = '&parent=' + parent;
                        }
                        if (result[i].JENIS_ID === "A16") {
                            daftarcetak += "<a href='./cetaklab' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
                        } else if (result[i].JENIS_ID === "A17") {
                            daftarcetak += "<a href='./cetakradiologi' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
                        } else {
                            daftarcetak += "<a href='./formantrian?head=" + head + "&subhead=antriankasir?head=" + head + "&kode=" + result[i].KODE + parent2 + "' data-kode='" + result[i].KODE + "' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
                        }/* else{
                            daftarcetak += "<a onclick='simpanantrian($(this));' data-kode='"+result[i].KODE+"' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
                        } */
                    }
                    
                    daftarcetak += "<i class='" + result[i].ICON_CLASS + " fa-5x text-center' style='background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;'></i><br>";
                    daftarcetak += "<h5 class='text-center' style='color:#234974;'>" + result[i].KETERANGAN + " </h5>";
                    daftarcetak += "</a>";
                    daftarcetak += "</div>";
                }
                
            }
            
            daftarcetak += "<div class='col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn'>";
            daftarcetak += "<a href='./antriankasir?parent=A14&head=concept_5&subhead=antriankasir&kode=N' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center' style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
            daftarcetak += "<i class='fa-solid fa-angle-double-left fa-4x text-center' style='background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;'></i><br>";
            daftarcetak += "<h4 class='text-center' style='color:#234974;'>KEMBALI</h4>";
            daftarcetak += "</a>";
            daftarcetak += "</div>";
            
            $("#daftarcetak").html(daftarcetak);
        }
    });
    return false;
};


function simpanantrian(btn) {
    var kode = btn.attr("data-kode");
    
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
        }
    });
    
    return false;
};