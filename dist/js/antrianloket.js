var urls = window.location.search;
var search = new URLSearchParams(urls);
var head = search.get('head');
var parent = search.get('parent');
var kode = search.get('kode');

Swal.fire({
	icon: 'info',
	title: '<span style="font-size:40px;">Diinformasikan Kepada Peserta BPJS</span>',
	html: `
        <div style="
            text-align:center;
            font-size:35px;
            line-height:1.6;
            padding:10px 5px;
        ">
            <div>
                Mulai tanggal <b style="color:#d33;">2 Juni</b> pendaftaran pasien BPJS RSUD Pasar Minggu menggunakan pendaftaran online.
                Anda dapat mendaftar melalui aplikasi <b style="color:#3085d6;">Mobile JKN</b>.
                Jika anda memiliki kendala dalam proses pendaftaran online anda dapat mengakses
                <b>loket Help Desk di lantai 2</b>.
            </div>
        </div>
    `,
	confirmButtonText: 'Mengerti',
	confirmButtonColor: '#3085d6',
	width: 750,
	padding: '1.5em',
	backdrop: 'rgba(0,0,0,0.6)',
	customClass: {
		confirmButton: 'btn-mengerti'
	},
	showClass: {
		popup: 'animate__animated animate__zoomIn'
	},
	hideClass: {
		popup: 'animate__animated animate__fadeOut'
	}
});


masterantrian();
//update
// setInterval(function () {
//     window.location.reload();
// }, 3000);

function masterantrian() {
    $("#daftarantrianloket").html("");
    $.ajax({
        url: url + "index.php/antrianloket/masterantrian",
        method: "POST",
        dataType: "JSON",
        data: { head: head, parent: parent },
        cache: false,
        success: function (data) {
            console.log(data);
            var html = "";

            if (data.responCode == "00") {
                result = data.responResult;

                for (var i in result) {
	                html += "<div class='col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn'>";
                    if (result[i].ADAANAK > '0') {
	                    if (result[i].JENIS_ID === "A22") {
		                    html += "<a href='./pasienbaru?parent=" + result[i].JENIS_ID + "&head=" + head + "&subhead=antrianloket?head=" + head + "&parent=" + result[i].HEADER_ID + "' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
	                    }else if (result[i].JENIS_ID === "A23") {
		                    html += "<a href='./pasienlama?parent=" + result[i].JENIS_ID + "&head=" + head + "&subhead=antrianloket?head=" + head + "&parent=" + result[i].HEADER_ID + "' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
	                    }else{
							
	                    }
                    } else {
                        var parent2 = ''
                        if (parent) {
                            parent2 = '&parent=' + parent;
                        }
	                    
	                    html += "<a href='javascript:' data-kode='"+ result[i].KODE +"' data-jenisid='"+ result[i].JENIS_ID +"' onclick='cetakantrian($(this));' class='btn rounded-circle d-flex flex-column justify-content-center align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
                    }
	                
	                html += "<i class='" + result[i].ICON_CLASS + " fa-5x text-center' style='background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;'></i><br>";
	                html += "<h5 class='text-center' style='color:#234974;'>" + result[i].KETERANGAN + " </h5>";
	                html += "</a>";
	                html += "</div>";
                }

            }

            $("#daftarantrianloket").html(html);
        }
    });
    return false;
};