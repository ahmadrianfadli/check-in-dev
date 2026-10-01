var ppid = {
    text                : "https://rsudpasarminggu.jakarta.go.id/ppid",
    width               : 500,
    height              : 500,
    quietZone           : 5,
    colorDark           : "#000000",
    colorLight          : "#ffffff",
    quietZoneColor      : 'rgba(255,255,255,1)',
    correctLevel        : QRCode.CorrectLevel.H,
    logo                : url+'dist/img/favicon.png',
    logoWidth           : 200,
    logoHeight          : 200,
    PO                  : '#234974',
    PI                  : '#4aac90',
    timing              : '#4aac90',
    AO                  : '#234974',
    AI                  : '#4aac90',
    titleFont           : "normal normal bold 18px Arial",                               //font. default is "bold 16px Arial"
    titleColor          : "#234974",                                                     // color. default is "#000"
    titleBackgroundColor: "#fff",                                                        // background color. default is "#fff"
    titleHeight         : 20,                                                            // height, including subTitle. default is 0
    titleTop            : 15,
    
};

var qrCode = new QRCode(document.getElementById("linkppid"), ppid);
