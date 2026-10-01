namadokter();

function namadokter(){
    var urlParams = new URLSearchParams(window.location.search);
    var poliid    = urlParams.get('poliid');
	$.ajax({
		url     : url+"index.php/Pilihdokter/namadokter",
		data    : { poliid: poliid },
		method  : "POST",
		dataType: "JSON",
		cache   : false,
		success:function(data){
			var daftardokter = "";

			if(data.responCode == "00"){
				var datagroupid      = [];
				var containers       = {};
				var previousGROUP_ID = null;
				var result           = data.responResult;
				var carouselInner    = document.getElementById('countainermonitoringpelayanan');

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
                    div.innerHTML = '<div class="row overflow-hidden p-5 d-flex justify-content-center" id="' + item + '"></div>';
                    carouselInner.appendChild(div);
                });

				// Initialize containers for each GROUP_ID
                datagroupid.forEach(function (GROUP_ID) {
                    containers[GROUP_ID] = "";
                });

				for (var i in result ){
					var GROUP_ID = result[i].GROUP_ID;

					if (containers[GROUP_ID] !== undefined) {
						var animationClass = (i % 2 === 0) ? 'animate__backInLeft' : 'animate__backInRight';

						containers[GROUP_ID] += "<a href='./detaildokter?head=concept_1&subhead=pilihdokter?head=concept_1%26subhead=jadwaldokter?head=concept_1%26poliid="+result[i].DEF_POLI_ID+"&poliid="+result[i].DEF_POLI_ID+"&dokterid="+result[i].DOKTER_ID+"&poliid="+result[i].DEF_POLI_ID+"' class='col-md-10 mt-3 mb-3 d-flex align-items-center animate__animated " + animationClass + "' style='border-style: solid; border-width: 10px; border-color: #234974; border-radius:110px;'>";
						containers[GROUP_ID] += "<div class='rounded-circle image-container' style='box-shadow: 5px 10px 50px #808080; margin-left:-5px; width: 200px; height: 200px; border-style: solid; border-width: 10px; border-color: #fff; background-color: #fff; overflow: hidden; position: relative;'>";
						containers[GROUP_ID] += "<img src='" + url + "/dist/img/dokter/" + result[i].DOKTER_ID + ".png' class='hover-image' style='width: 100%; height: 100%; object-fit: cover;'>";
						containers[GROUP_ID] += "</div>";
						containers[GROUP_ID] += "<div class='col-md-9 pl-3'>";
						containers[GROUP_ID] += "<h3 class='d-flex align-items-end text-truncate font-weight-bold' style='color:#234974;'>" + result[i].NAMA + "</h3>";
						containers[GROUP_ID] += "<h5 class='d-flex align-items-start' style='color:#234974;'>" + result[i].KOLEGIUM + "</h5>";
						containers[GROUP_ID] += "</div>";
						containers[GROUP_ID] += "</a>";
					}

					previousGROUP_ID = GROUP_ID;
                }
			}

			for (var containerId in containers) {
				$("#" + containerId).html(containers[containerId]);
			}

			$('#carouselExampleControls').carousel();
		}
	});
	return false;
};