// Function to extract poliid from the current URL
// function getPoliIdFromURL() {
//     var urlParams = new URLSearchParams(window.location.search);
//     return urlParams.get('poliid') || '';
// }

// Function to handle the click event
// function handleJadwalDokterClick() {
//     var poliidValue = getPoliIdFromURL();
//     var jadwalDokterLink = document.getElementById('jadwalDokterLink');
    
//     // Update the href attribute with the dynamic poliid
//     jadwalDokterLink.href = "./pilihdokter?poliid=" + poliidValue;

//     // Optional: Open the link in the same window/tab
//     window.location.href = jadwalDokterLink.href;
    
//     // Optional: Open the link in a new window/tab
//     // window.open(jadwalDokterLink.href, '_blank');
// }

// // Add an onclick event handler to trigger the function
// var jadwalDokterLink = document.getElementById('jadwalDokterLink');
// jadwalDokterLink.onclick = handleJadwalDokterClick;

calendar();

function calendar() {
    var urlParams  = new URLSearchParams(window.location.search);
    var poliid     = urlParams.get('poliid');
    var dokterid   = urlParams.get('dokterid');
    var Calendar   = FullCalendar.Calendar;
    var calendarEl = document.getElementById("calendar");

    var today           = new Date();
    var disabledEndDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 15);  // 14 days from today

    var calendar = new Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        firstDay: 1,
        plugins: ["bootstrap", "interaction", "dayGrid", "timeGrid"],
        header: {
            left: "prevYear,prev,next,nextYear",
            center: "title",
            right: "today",
        },
		hiddenDays: [0],
        themeSystem: "bootstrap",
		locale: 'id',
        eventSources: [{
            url: url + "index.php/detaildokter/detailjadwaldokter?poliid="+poliid+"&dokterid="+dokterid,
            method: 'POST'
        }],
        dateClick: function (info) {
            if (info.date.getDay() === 0) {
                Swal.fire({
                    position: "center",
                    icon: "info",
                    title: "<h1 class='text-info font-weight-bold'>INFORMATION</h1>",
                    html: "<h5 class='text-info font-weight-bold'>Mohon Maaf Anda Tidak Dapat Memilih Pada Hari Minggu, Silakan Pilih Hari Lain</h5>",
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
                    icon: "info",
                    title: "<h1 class='text-info font-weight-bold'>INFORMATION</h1>",
                    html: "<h1 class='text-info font-weight-bold'>SYSTEM BELUM TERSEDIA</h1>",
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
        },
        aspectRatio: 1.3,
        validRange: {
            start: today.toISOString().split("T")[0], // today
            end: disabledEndDate.toISOString().split("T")[0] // 14 days from today
        }
    });

    calendar.render();
};
