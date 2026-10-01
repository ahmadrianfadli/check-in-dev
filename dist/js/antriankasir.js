var urls = window.location.search;
var search = new URLSearchParams(urls);
var head = search.get('head');
var parent = search.get('parent');
var kode = search.get('kode');

masterantrian();
//update
setInterval(function () {
    window.location.reload();
}, 6000000);

function masterantrian() {
    $("#daftarantrian").html("");
    $.ajax({
        url: url + "index.php/Antriankasir/masterantrian",
        method: "POST",
        dataType: "JSON",
        data: { head: head, parent: parent },
        cache: false,
        success: function (data) {
            console.log(data);
            var daftarantrian = "";

            if (data.responCode == "00") {
	            result = data.responResult;
	            
	            for (var i in result) {
		            daftarantrian += "<div class='col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn'>";
		            if (result[i].ADAANAK > '0') {
			            if (result[i].KODE === "O") {
				            daftarantrian += "<a href='./cetakpenunjang?head=concept_5&parent=" + result[i].JENIS_ID + "&kode=" + result[i].KODE + "' data-kode='" + result[i].KODE + "' class='btn rounded-circle d-flex flex-column justify-content-center align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974; '>";
			            }else{
				            daftarantrian += "<a href='./antriankasir?parent=" + result[i].JENIS_ID + "&head=" + head + "&subhead=antriankasir?head=" + head + "&kode=" + result[i].KODE + "' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
			            }
		            } else {
			            var parent2 = ''
			            if (parent) {
				            parent2 = '&parent=' + parent;
			            }
			            if (result[i].JENIS_ID === "A06" || result[i].JENIS_ID === "A05") {
				            daftarantrian += "<a href='./kasirrj?head=" + head + "&subhead=antriankasir?head=" + head + "&kode=" + result[i].KODE + parent2 + "' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
			            } else if (result[i].JENIS_ID === "A07") {
				            daftarantrian += "<a href='./registpasienumum?head=" + head + "&subhead=antriankasir?head=" + head + "&kode=" + result[i].KODE + parent2 + "' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
			            } else if (result[i].JENIS_ID === "A08") {
				            daftarantrian += "<a href='./bayarqris?head=" + head + "&subhead=antriankasir?head=" + head + "&kode=" + result[i].KODE + parent2 + "' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
			            } else if (parent === "A14") {
				            if(head === "concept_5"){
					            daftarantrian += "<a href='./formantrian?head=antriankasir?head=" + head + "&kode=" + result[i].KODE + "' data-kode='" + result[i].KODE + "' class='btn rounded-circle d-flex flex-column justify-content-center align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974; '>";
				            }else{
					            daftarantrian += "<a href='./formantrian?head=antriankasir?parent=" + parent + "&kode=" + result[i].KODE + "' data-kode='" + result[i].KODE + "' class='btn rounded-circle d-flex flex-column justify-content-center align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974; '>";
				            }
			            } else {
				            daftarantrian += "<a href='./formantrian?head=" + head + "&subhead=antriankasir?head=" + head + "&kode=" + result[i].KODE + parent2 + "' data-kode='" + result[i].KODE + "' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
			            }/* else{
                            daftarantrian += "<a onclick='simpanantrian($(this));' data-kode='"+result[i].KODE+"' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
                        } */
		            }
		            
		            daftarantrian += "<i class='" + result[i].ICON_CLASS + " fa-5x text-center' style='background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;'></i><br>";
		            daftarantrian += "<h5 class='text-center' style='color:#234974;'>" + result[i].KETERANGAN + " </h5>";
		            daftarantrian += "</a>";
		            daftarantrian += "</div>";
	            }
            }

            // daftarantrian += "<div class='col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn'>";
            // daftarantrian += "<a href='./registrasipasienbaru?head="+head+"&subhead=antriankasir' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center' style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
            // daftarantrian += "<i class='fa-solid fa-globe fa-4x text-center' style='background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;'></i><br>";
            // daftarantrian += "<h4 class='text-center' style='color:#234974;'>REGISTRASI PASIEN BARU</h4>";
            // daftarantrian += "</a>";
            // daftarantrian += "</div>";

            $("#daftarantrian").html(daftarantrian);
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