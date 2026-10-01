var lastcall = "";

if('speechSynthesis' in window){
	console.log('Browser mendukung sintesis suara.');
} else {
	console.log('Browser tidak mendukung sintesis suara.');
}
  
tensipanggil();
tensiberikutnya();

setInterval(tensiberikutnya,10000);
setInterval(tensipanggil,8000);

function tensiberikutnya(){
    var kodedisplay   = $("input[name='kodedisplay']").val();
    $.ajax({
        url:url+"index.php/ns/tensiberikutnya",
        data : {'kodedisplay':kodedisplay},
        method:"POST",
        dataType:"JSON",
        cache :false,
        success:function(data){
            var tensiberikutnya ="";

            if(data.responCode == "00"){

                $("#tensiberikutnya").html("");
                result = data.responResult;

                for (var i in result ){  

                    tensiberikutnya +="<div class='col-md-6 animate__animated animate__zoomIn'>";
					tensiberikutnya +="<div class='card'>";
					if(result[i].PANGGIL==="T"){
						tensiberikutnya +="<div class='card-body'>";
					}else{
						tensiberikutnya +="<div class='card-body bg-warning'>";
					}
					tensiberikutnya +="<div class='row'>";
					tensiberikutnya +="<div class='col-md-12'>";
					tensiberikutnya +="<div class='row'>";
					tensiberikutnya +="<div class='col-md-9 d-flex justify-content-start'>";
					tensiberikutnya +="<h2 class='text-truncate'>"+result[i].NAMAPASIEN+"</h2>";
					tensiberikutnya +="</div>";
					tensiberikutnya +="<div class='col-md-3 d-flex justify-content-end'>";
					tensiberikutnya +="<h2>"+result[i].URUT+"</h2>";
					tensiberikutnya +="</div>";
					tensiberikutnya +="</div>";
					tensiberikutnya +="<div class='row'>";
					if(result[i].PANGGIL==="T"){
						tensiberikutnya +="<div class='col-md-12'>";
						tensiberikutnya +="<h4 class='text-truncate'>"+result[i].PROVIDER+"</h4>";
						tensiberikutnya +="</div>";
					}else{
						tensiberikutnya +="<div class='col-md-4'>";
						tensiberikutnya +="<h4 class='text-truncate'>"+result[i].PROVIDER+"</h4>";
						tensiberikutnya +="</div>";
						tensiberikutnya +="<div class='col-md-8 d-flex justify-content-end'>";
						tensiberikutnya +="<h4 class='text-truncate'>SUDAH PERNAH DI PANGGIL</h4>";
						tensiberikutnya +="</div>";
					}
					
					tensiberikutnya +="</div>";
					tensiberikutnya +="<hr class='mt-2 mb-2' style='border-top: 2px solid'>";
					tensiberikutnya +="<div class='row'>";
					tensiberikutnya +="<div class='col-md-12'>";
					tensiberikutnya +="<h3 class='text-truncate' style='font-size:14px;'>"+result[i].NAMADOKTER+"</h3>";
					tensiberikutnya +="</div>";
					tensiberikutnya +="<div class='col-md-6 d-flex justify-content-start'>";
					tensiberikutnya +="<h3 class='text-truncate' style='font-size:14px;'>"+result[i].POLITUJUAN+"</h3>";
					tensiberikutnya +="</div>";
					tensiberikutnya +="<div class='col-md-6 d-flex justify-content-end'>";
					tensiberikutnya +="<h5 class='text-truncate' style='font-size:14px;'>"+result[i].JAMCHECKKIN+"</h5>";
					tensiberikutnya +="</div>";
					tensiberikutnya +="</div>";
					tensiberikutnya +="</div>";
					tensiberikutnya +="</div>";
					tensiberikutnya +="</div>";
					tensiberikutnya +="</div>";
					tensiberikutnya +="</div>";
                    tensiberikutnya +="</div>";
                    
                }
            }

            $("#tensiberikutnya").html(tensiberikutnya);
        }
    });
    return false;
};

function tensipanggil(){
	var kodedisplay   = $("input[name='kodedisplay']").val();
	$.ajax({
		url:url+"index.php/ns/tensipanggil",
		data : {'kodedisplay':kodedisplay},
		method:"POST",
		dataType:"JSON",
		cache :false,
		success:function(data){
			var call         = "";
			var tensipanggil = "";
			
			if(data.responCode == "00"){
				$("#tensipanggil").html("");
				result = data.responResult;
				
				for (var i in result ){
					var antrian            = "";
					/* var soundAntrianString = result[i].SOUNDANTRIAN;
					var soundAntrianArray  = soundAntrianString.split("|"); */

					antrian = result[i].URUT+" "+result[i].NAMAPASIEN+" "+result[i].NAMADOKTER+" "+result[i].POLITUJUAN;
					call    = result[i].URUT+" | "+result[i].NAMAPASIEN;

					/* var audioFiles = soundAntrianArray.map(function (soundFileName) {
						return new Audio(url + soundFileName.trim());
					}); */

					/* function playAudio(index) {
                        if (index >= 0 && index < audioFiles.length) {
                            audioFiles[index].play();
                            setTimeout(function () {
                                playAudio(index + 1);
                            }, 1000); // Ubah 1000 menjadi jarak yang Anda inginkan dalam milidetik (1 detik = 1000 milidetik).
                        }
                    } */

					// playAudio(0);
					// responsiveVoice.speak(antrian, "Indonesian Female");

					if (lastcall !== call) {
						// console.log('Start Play');
						//playAudio(0);
						// console.log('End Play');
						tensipanggil += "<div class='col-md-12 animate__animated animate__zoomIn'>";
					} else {
						tensipanggil += "<div class='col-md-12'>";
					}

					tensipanggil +="<div class='card bg-primary'>";
					tensipanggil +="<div class='card-body'>";
					tensipanggil +="<div class='row'>";
					tensipanggil +="<div class='col-md-12 d-flex justify-content-center'>";
					tensipanggil +="<h1 style='font-size:340px'>"+result[i].URUT+"</h1>";
					// tensipanggil +="<h1 style='font-size:300px'>A-1</h1>";
					tensipanggil +="</div>";
					// tensipanggil +="<div class='col-md-12 d-flex justify-content-center'>";
					// tensipanggil +="<h1 style='font-size:150px'>"+result[i].PROVIDER+"</h1>";
					// tensipanggil +="</div>";
					tensipanggil +="<div class='col-md-12 d-flex justify-content-start mt-5'>";
					tensipanggil +="<h1 class='text-truncate' style='font-size:100px'>"+result[i].NAMAPASIEN+"</h1>";
					tensipanggil +="</div>";
					tensipanggil +="</div>";
					tensipanggil +="<hr class='mt-2 mb-2' style='border-top: 5px solid white;'>";
					tensipanggil +="<div class='row'>";
					tensipanggil +="<div class='col-md-12'>";
					tensipanggil +="<h1 class='text-truncate' style='font-size:60px'>"+result[i].NAMADOKTER+"</h1>";
					tensipanggil +="</div>";
					tensipanggil +="<div class='col-md-12'>";
					tensipanggil +="<h1 class='text-truncate' style='font-size:60px'>"+result[i].POLITUJUAN+"</h1>";
					tensipanggil +="</div>";
					tensipanggil +="</div>";
					tensipanggil +="</div>";
					tensipanggil +="</div>";
					tensipanggil +="</div>";
					
					lastcall = result[i].URUT+" | "+result[i].NAMAPASIEN;
				}
			}

			$("#tensipanggil").html(tensipanggil);  
		}
	});
	return false;
};

function suaraantrian(antrian){
	var msg        = new SpeechSynthesisUtterance();
	    msg.text   = antrian;
	    msg.lang   = 'id-ID';                         // Setel bahasa/lokalisasi ucapan
	    msg.volume = 1;                               // Volume (0 hingga 1)
	    msg.rate   = 0.75;                               // Kecepatan berbicara (0.1 hingga 10)
	    msg.pitch  = 1; 
		                              // Pitch (0 hingga 2)\
	window.speechSynthesis.speak(msg);

	console.log('Speech synthesis initiated.');
}