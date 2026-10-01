function closebpjs() {
var d = new Date();
var time = ('0' + d.getHours()).slice(-2) + ":" + ('0' + d.getMinutes()).slice(-2) ;
var day = d.getDay() + 1;
var timeclose;
if (day === 6 || day === 7) {
    timeclose = '10:00';
} else {
    timeclose = '12:00';
}
console.log(time);
console.log(timeclose);
if (timeclose < time) {
    $("#verifdatang").addClass('d-none').removeClass('d-flex');
} else {
    $("#verifdatang").removeClass('d-none').addClass('d-flex');
}
}
setInterval(closebpjs,1000)