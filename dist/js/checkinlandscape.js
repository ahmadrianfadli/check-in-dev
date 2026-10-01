function addToDisplay(value){
    kodebookingcheckin.value            += value;
};

function clearinput(){
    var kodebookingcheckin            = $("#kodebookingcheckin").val();
    $("#kodebookingcheckin").val(kodebookingcheckin.slice(0, -1));
};

function proses()
{
	var form = $("#formcheckin");
	$("#kodebookingcheckin").attr("readonly", "true");
	$.ajax({
		url     : form.attr("action"),
		data    : form.serialize(),
		method  : "POST",
		dataType: "JSON",
		cache   : false,
		success: function (data) {
			if (data.responCode === "01") {
                Swal.fire({
					position         : "center",
					icon             : data.responHead,
					title            : "<h1 class='font-weight-bold' style='color:#234974;'>"+data.responDesc+"</h1>",
					html             : data.responHtml,
					backdrop         : "rgba(255, 0, 0,0.4)",
					timerProgressBar : true,
					showConfirmButton: false,
					timer            : 2000,
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
						$("#kodebookingcheckin").val("");
						$("#kodebookingcheckin").removeAttr("readonly");
					}else{
						if (Swal.DismissReason.close) {
							$("#kodebookingcheckin").val("");
							$("#kodebookingcheckin").removeAttr("readonly");
						}
					}
				});
            }else{
				Swal.fire({
					position         : "center",
					icon             : data.responHead,
					title            : "<h1 class='font-weight-bold' style='color:#234974;'>"+data.responDesc+"</h1>",
					html             : data.responHtml,
					timerProgressBar : true,
					showConfirmButton: false,
					timer            : 2000,
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
						$("#kodebookingcheckin").val("");
						$("#kodebookingcheckin").removeAttr("readonly");
					}else{
						if(Swal.DismissReason.close) {
							$("#kodebookingcheckin").val("");
							$("#kodebookingcheckin").removeAttr("readonly");
						}
					}
				});
			}
		}
	});
};

$(document).on("submit", "#formcheckin", function (e) {
	e.preventDefault();
	$("#kodebooking").attr("readonly", "true");
	var form = $(this),url  = $(this).attr("action");
	$.ajax({
        url: url,
        data: form.serialize(),
		method: "POST",
		dataType: "JSON",
		cache: false,
		success: function (data) {
			if (data.responCode === "01") {
                Swal.fire({
					position         : "center",
					icon             : data.responHead,
					title            : "<h1 class='font-weight-bold' style='color:#234974;'>"+data.responDesc+"</h1>",
					html             : data.responHtml,
					backdrop         : "rgba(255, 0, 0,0.4)",
					timerProgressBar : true,
					showConfirmButton: false,
					timer            : 2000,
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
						$("#kodebookingcheckin").val("");
						$("#kodebookingcheckin").removeAttr("readonly");
					}else{
						if (Swal.DismissReason.close) {
							$("#kodebookingcheckin").val("");
							$("#kodebookingcheckin").removeAttr("readonly");
						}
					}
				});
            }else{
				Swal.fire({
					position         : "center",
					icon             : data.responHead,
					title            : "<h1 class='font-weight-bold' style='color:#234974;'>"+data.responDesc+"</h1>",
					html             : data.responHtml,
					timerProgressBar : true,
					showConfirmButton: false,
					timer            : 2000,
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
						$("#kodebookingcheckin").val("");
						$("#kodebookingcheckin").removeAttr("readonly");
					}else{
						if(Swal.DismissReason.close) {
							$("#kodebookingcheckin").val("");
							$("#kodebookingcheckin").removeAttr("readonly");
						}
					}
				});
			}
		}
	});
    return false;
});