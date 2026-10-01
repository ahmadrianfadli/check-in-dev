var urlParams = new URLSearchParams(window.location.search);
var head = urlParams.get('head');
daftarpoli();

function daftarpoli() {
    $.ajax({
        url: url + "index.php/Jadwaldokter/daftarpoli",
        method: "POST",
        dataType: "JSON",
        cache: false,
        success: function (data) {
            if (data.responCode == "00") {
                var btnhome = "";
                var datagroupid = [];
                var containers = {};
                var previousGROUP_ID = null;
                var result = data.responResult;
                var carouselInner = document.getElementById('countainermonitoringpelayanan');

                // Populate datagroupid with unique GROUP_ID values
                for (var i in result) {
                    var GROUP_ID = result[i].GROUP_ID;

                    if (GROUP_ID != previousGROUP_ID) {
                        datagroupid.push(GROUP_ID);
                    }

                    previousGROUP_ID = GROUP_ID;
                }

                // Clear carouselInner
                carouselInner.innerHTML = '';

                // Create carousel items based on unique GROUP_ID values
                datagroupid.forEach(function (item, index) {
                    var div = document.createElement('div');
                    div.className = 'carousel-item' + (index === 0 ? ' active' : '');
                    div.innerHTML = '<div class="row overflow-hidden pl-5 ml-5 pr-5 mr-5" id="' + item + '"></div>';
                    carouselInner.appendChild(div);
                });

                // Initialize containers for each GROUP_ID
                datagroupid.forEach(function (GROUP_ID) {
                    containers[GROUP_ID] = "";
                });

                // Populate containers with content based on GROUP_ID
                for (var i in result) {
                    var GROUP_ID = result[i].GROUP_ID;

                    if (containers[GROUP_ID] !== undefined) {
                        containers[GROUP_ID] += "<div class='col-md-4 d-flex justify-content-center mt-5 mb-5 animate__animated animate__bounceIn'>";
                        containers[GROUP_ID] += "<a href='./pilihdokter?head=" + head + "&subhead=jadwaldokter?head=" + head + "&poliid=" + result[i].POLI_ID + "' class='btn rounded-circle d-flex flex-column justify-content-center  align-items-center'  style='width: 230px; height: 230px; border-style: solid; border-width: 10px; border-color: #234974;'>";
                        containers[GROUP_ID] += "<i class='" + result[i].KATEGORI_ID + " fa-5x text-center' style='background-image: linear-gradient(-45deg, #234974, #4aac90); -webkit-background-clip: text; color: transparent;'></i><br>";
                        containers[GROUP_ID] += "<h5 class='text-center' style='color:#234974;'>" + result[i].KETERANGAN + " </h5>";
                        containers[GROUP_ID] += "</a>";
                        containers[GROUP_ID] += "</div>";
                    }


                    previousGROUP_ID = GROUP_ID;
                }

                // Update HTML with container content
                for (var containerId in containers) {
                    $("#" + containerId).html(containers[containerId]);
                }

                $('#carouselExampleControls').carousel();
            }
        }
    });
    return false;
};