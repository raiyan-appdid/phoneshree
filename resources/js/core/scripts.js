window.addEventListener("load", (function () {
    var e = document.getElementById("custom-loader"); document.body.removeChild(e), $(".dataTables_scrollBody").length > 0 && new PerfectScrollbar(".dataTables_scrollBody")
}));
document.addEventListener("keydown", (evt) => { //when this happens
    if (evt.key === '/') {
        evt.preventDefault();
        document.querySelector('.nav-link-search').click();
    }

});