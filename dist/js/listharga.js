var urlParams = new URLSearchParams(window.location.search);
var kategori  = urlParams.get('kategori');
var isexe  = urlParams.get('isexe');

switch (kategori) {
    case 'JKL-UMU':
        kategoriLabel = 'LAYANAN UMUM';
        break;
    case 'JKL-LAB':
        kategoriLabel = 'LABORATORIUM';
        break;
    case 'JKL-RAD':
        kategoriLabel = 'RADIOLOGI';
        break;
    case 'JKL-OPR':
        kategoriLabel = 'OPERASI';
        break;
    default:
        kategoriLabel = 'LAINNYA';
        break;
}

switch (isexe) {
    case 'Y':
        kategoriLabel = 'EKSEKUTIF';
        break;
    case 'T':
        kategoriLabel = 'NON EKSEKUTIF';
        break;
}

$(document).ready(function() {
    $('#kat').html(kategoriLabel);
    $("#searchKeyword").val('');
	$("#textcari").val('');

    $('#panelkeyboard_abc').removeClass("d-none");
    $('#resultContainer').addClass("d-none");

    $('#textcari').on('focus', function() {
        $('#panelkeyboard_abc').removeClass('d-none');
        $('#resultContainer').addClass("d-none");
    });
});

function addToDisplay(value){
    // searchKeyword.value += value;
    textcari.value      += value;
};

function clearinput(){
    // var searchKeyword = $("#searchKeyword").val();
    // $("#searchKeyword").val(searchKeyword.slice(0, -1));

    var textcari = $("#textcari").val();
    $("#textcari").val(textcari.slice(0, -1));
};

function formatCurrency(value) {
    return new Intl.NumberFormat('id-ID', {
        style                : 'currency',
        currency             : 'IDR',
        currencyDisplay      : 'symbol',
        maximumFractionDigits: 0,
    }).format(value);
}

function proses() {
    var carikata = $("#textcari").val();
    $.ajax({
        url     : url + 'index.php/listharga/cariDataLayan',
        data    : {carikata:carikata,kategori:kategori, isexe: isexe},
        method  : "POST",
        dataType: "JSON",
        cache   : false,
        beforeSend: function() {
            $('#panelkeyboard_abc').addClass("d-none");
        },
        success: function (data) {
            if(data.responCode === "00"){
                $('#resultContainer').removeClass("d-none");
                var result          = data.responResult;
                var card            = '<div class="accordion accordion-flush" id="accordionFlushExample">';

                for (var i in result) {
                    var hargaRJ     = result[i].RJ ? formatCurrency(result[i].RJ) : 'N/A';
                    var hargaKelas1 = result[i].KELAS1 ? formatCurrency(result[i].KELAS1) : 'N/A';
                    var hargaKelas2 = result[i].KELAS2 ? formatCurrency(result[i].KELAS2) : 'N/A';
                    var hargaKelas3 = result[i].KELAS3 ? formatCurrency(result[i].KELAS3) : 'N/A';
                    var hargaVIP    = result[i].VIP ? formatCurrency(result[i].VIP) : 'N/A';
                    var hargaVVIP   = result[i].VVIP ? formatCurrency(result[i].VVIP) : 'N/A';

					var is_eksekutif = '';
					var show_harga = '';
					if(result[i].IS_EXE === 'Y'){
						is_eksekutif = '(Poli Eksekutif)';
						
						show_harga = `
                            <tr>
                                <td style="font-size: 20px;">`+hargaRJ+`</td>
                                <td style="font-size: 20px;" colspan="5">-</td>
                            </tr>
						`;
					}else{
						is_eksekutif = '';
						
						show_harga = `
                            <tr>
                                <td style="font-size: 20px;">`+hargaRJ+`</td>
                                <td style="font-size: 20px;">`+hargaKelas1+`</td>
                                <td style="font-size: 20px;">`+hargaKelas2+`</td>
                                <td style="font-size: 20px;">`+hargaKelas3+`</td>
                                <td style="font-size: 20px;">`+hargaVIP+`</td>
                                <td style="font-size: 20px;">`+hargaVVIP+`</td>
                            </tr>
						`;
					}

                    card += `
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-`+i+`" aria-expanded="false" aria-controls="flush-collapseOne" style="font-size:20px">
                                        `+result[i].NAMA_LAYAN2+` - `+result[i].NAMALAYAN+`
                                    </button>
                                    </h2>
        
                                    <div id="flush-`+i+`" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body table-responsive px-4">
                                            <table class="table table-striped table-bordered">
                                                <tbody>
                                                    <tr class='text-center'>
                                                        <th>Rawat Jalan</th>
                                                        <th>Kelas 1</th>
                                                        <th>Kelas 2</th>
                                                        <th>Kelas 3</th>
                                                        <th>Kelas VIP</th>
                                                        <th>Kelas VVIP</th>
                                                    </tr>
                                                    `+ show_harga +`								
                                                </tbody>
                                            </table>
                                            <p style="color: red; font-style: italic; font-size:20px"><strong>*harga CITO = 1.5 x harga yang tertera</strong></p>
                                        </div>	
                                    </div>
                                </div>
                            `;
                }

                $("#resultContainer").html(card);
                $(".card-header").click(function() {
                    $(this).next(".card-body").slideToggle();
                });
            }else{
                $('#panelkeyboard_abc').removeClass("d-none");
                $("#searchKeyword").val('');
                $("#textcari").val('');

                Swal.fire({
                    position         : "center",
                    icon             : data.responHead,
                    title            : "<h1 class='text-danger font-weight-bold'>Information !!!</h1>",
                    html             : "<h4 class='text-danger font-weight-bold'>" + data.responDesc + "</h4>",
                    backdrop         : "rgba(255, 0, 0,0.4)",
                    timerProgressBar : true,
                    showConfirmButton: true,
                    timer            : 5000,
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
            // console.log(res);

            // if (res && res.length > 0) {
            //     $("#searchModal").modal('hide');

            //     var $resultContainer = $("#resultContainer");
            //     $resultContainer.empty(); // Clear existing results
            //     var card = '<div class="accordion accordion-flush" id="accordionFlushExample">';
            //     res.forEach(function(item,i) {
                    
            //         var hargaRJ     = item.RJ ? formatCurrency(item.RJ) : 'N/A';
            //         var hargaKelas1 = item.KELAS1 ? formatCurrency(item.KELAS1) : 'N/A';
            //         var hargaKelas2 = item.KELAS2 ? formatCurrency(item.KELAS2) : 'N/A';
            //         var hargaKelas3 = item.KELAS3 ? formatCurrency(item.KELAS3) : 'N/A';
            //         var hargaVIP    = item.VIP ? formatCurrency(item.VIP) : 'N/A';
            //         var hargaVVIP   = item.VVIP ? formatCurrency(item.VVIP) : 'N/A';
                    
            //         card += `
                    
            //             <div class="accordion-item">
            //                 <h2 class="accordion-header" id="flush-headingOne">
            //                 <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-`+i+`" aria-expanded="false" aria-controls="flush-collapseOne" style="font-size:20px">
            //                     `+item.NAMALAYAN+`
            //                 </button>
            //                 </h2>

            //                 <div id="flush-`+i+`" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
            //                     <div class="accordion-body table-responsive px-4">
            //                         <table class="table table-striped table-bordered">
            //                             <tbody>
            //                                 <tr class='text-center'>
            //                                     <th>Rawat Jalan</th>
            //                                     <th>Kelas 1</th>
            //                                     <th>Kelas 2</th>
            //                                     <th>Kelas 3</th>
            //                                     <th>Kelas VIP</th>
            //                                     <th>Kelas VVIP</th>
            //                                 </tr>
            //                                 <tr>
            //                                     <td style="font-size: 20px;">`+hargaRJ+`</td>
            //                                     <td style="font-size: 20px;">`+hargaKelas1+`</td>
            //                                     <td style="font-size: 20px;">`+hargaKelas2+`</td>
            //                                     <td style="font-size: 20px;">`+hargaKelas3+`</td>
            //                                     <td style="font-size: 20px;">`+hargaVIP+`</td>
            //                                     <td style="font-size: 20px;">`+hargaVVIP+`</td>
            //                                 </tr>										
            //                             </tbody>
            //                         </table>
            //                         <p style="color: red; font-style: italic; font-size:20px"><strong>*harga CITO = 1.5 x harga yang tertera</strong></p>
            //                     </div>	
            //                 </div>
            //             </div>

            //         `;
            //     });
            //     card += '</div>';

            //     $resultContainer.html(card);
            //     $(".card-header").click(function() {
            //         $(this).next(".card-body").slideToggle();
            //     });
            // }else{
            //     $('#panelkeyboard_abc').removeClass("d-none");
            //     $("#searchKeyword").val('');
            //     $("#textcari").val('');

            //     Swal.fire({
            //         position         : "center",
            //         icon             : data.responHead,
            //         title            : "<h1 class='text-danger font-weight-bold'>GAGAL !!!</h1>",
            //         html             : "<h4 class='text-danger font-weight-bold'>" + data.responDesc + "</h4>",
            //         backdrop         : "rgba(255, 0, 0,0.4)",
            //         timerProgressBar : true,
            //         showConfirmButton: true,
            //         timer            : 5000,
            //         showClass: {
            //             popup: `
            //                 animate__animated
            //                 animate__fadeInUp
            //                 animate__faster
            //             `
            //         },
            //         hideClass: {
            //             popup: `
            //                 animate__animated
            //                 animate__fadeOutDown
            //                 animate__faster
            //             `
            //         }
            //     });
            // }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data: ', error);
            alert('An error occurred while fetching data.');
        }
    });
}