$(document).ready(function () {
    listmenu();

    function listmenu() {
        $.ajax({
            url: url + "index.php/Menu/listmenu",
            method: "POST",
            dataType: "JSON",
            cache: false,
            success: function (data) {
                var listmenu = "";

                if (data.responCode == "00") {
                    $("#listmenu").html("");

                    result = data.responResult;

                    for (var i in result) {
                        listmenu += "<div class='col-md-3 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn'>";

                        if (result[i].JENIS === "FRM") {
                            listmenu += "<a href='" + url + "index.php/farmasi?param=" + result[i].KODE + "' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 150px; height: 150px; border-style: solid; border-width: 5px; border-color: #234974;'>";
                        } else {
                            listmenu += "<a href='" + url + "index.php/ns?param=" + result[i].KODE + "' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 150px; height: 150px; border-style: solid; border-width: 5px; border-color: #234974;'>";
                        }

                        listmenu += "<i class='" + result[i].ICON + " fa-3x text-center' style='background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;'></i><br>";
                        listmenu += "<h6 class='text-center' style='color:#234974;'>" + result[i].DISPLAY + " </h6>";
                        listmenu += "</a>";
                        listmenu += "</div>";
                    }

                    listmenu += "<div class='col-md-3 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn'>";
                    listmenu += "<a href='" + url + "index.php/cekresep' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 150px; height: 150px; border-style: solid; border-width: 5px; border-color: #234974;'>";
                    listmenu += "<i class='fa-solid fa-prescription fa-3x text-center' style='background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;'></i><br>";
                    listmenu += "<h6 class='text-center' style='color:#234974;'>CEK RESEP</h6>";
                    listmenu += "</a>";
                    listmenu += "</div>";

                    listmenu += "<div class='col-md-3 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn'>";
                    listmenu += "<a href='" + url + "index.php/checkin' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 150px; height: 150px; border-style: solid; border-width: 5px; border-color: #234974;'>";
                    listmenu += "<i class='fa-solid fa-qrcode fa-3x text-center' style='background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;'></i><br>";
                    listmenu += "<h6 class='text-center' style='color:#234974;'>CHECK-IN</h6>";
                    listmenu += "</a>";
                    listmenu += "</div>";

                    listmenu += "<div class='col-md-3 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn'>";
                    listmenu += "<a href='" + url + "index.php/tabantrianrm' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 150px; height: 150px; border-style: solid; border-width: 5px; border-color: #234974;'>";
                    listmenu += "<i class='fa-solid fa-hospital-user fa-3x text-center' style='background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;'></i><br>";
                    listmenu += "<h6 class='text-center' style='color:#234974;'>ANTRIAN BPJS</h6>";
                    listmenu += "</a>";
                    listmenu += "</div>";

                    listmenu += "<div class='col-md-3 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn'>";
                    listmenu += "<a href='" + url + "index.php/antriankasir?head=concept_2' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 150px; height: 150px; border-style: solid; border-width: 5px; border-color: #234974;'>";
                    listmenu += "<i class='fa-solid fa-wallet fa-3x text-center' style='background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;'></i><br>";
                    listmenu += "<h6 class='text-center' style='color:#234974;'>ANTRIAN KASIR</h6>";
                    listmenu += "</a>";
                    listmenu += "</div>";

                    listmenu += "<div class='col-md-3 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn'>";
                    listmenu += "<a href='" + url + "index.php/cetaklab' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 150px; height: 150px; border-style: solid; border-width: 5px; border-color: #234974;'>";
                    listmenu += "<i class='fa-solid fa-file-pdf fa-3x text-center' style='background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;'></i><br>";
                    listmenu += "<h6 class='text-center' style='color:#234974;'>CETAK LAB</h6>";
                    listmenu += "</a>";
                    listmenu += "</div>";
                }

                $("#listmenu").html(listmenu);
            }
        });
        return false;
    };

});

