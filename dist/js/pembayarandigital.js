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
    $("#pembayarandigital").html("");
    $.ajax({
        url: url + "index.php/pembayarandigital/masterantrian",
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
						
                    } else {
                        var parent2 = ''
                        if (parent) {
                            parent2 = '&parent=' + parent;
                        }
						
                        if (result[i].JENIS_ID === "A31") {
	                        html += "<a href='./bayarpoliqris?head=concept_5&subhead=pembayarandigital?head=concept_5&parent=A21' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
                        } else if (result[i].JENIS_ID === "A32") {
	                        html += "<a href='./bayarqris?head=concept_5&subhead=pembayarandigital?head=concept_5&parent=A21' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
                        }
                    }
	                
	                html += "<i class='" + result[i].ICON_CLASS + " fa-5x text-center' style='background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;'></i><br>";
	                html += "<h5 class='text-center' style='color:#234974;'>" + result[i].KETERANGAN + " </h5>";
	                html += "</a>";
	                html += "</div>";
                }

            }

            $("#pembayarandigital").html(html);
        }
    });
    return false;
};