// TAB FEATURE
function openLevel(evt, level) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");

    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(level).style.display = "block";
    evt.currentTarget.className += " active";
}


// BACK TO TOP BUTTON
let backToTopButton = document.getElementById("btn-back-to-top");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () {
    scrollFunction();
};

function scrollFunction() {
    if (
        document.body.scrollTop > 20 ||
        document.documentElement.scrollTop > 20
    ) {
        backToTopButton.style.display = "block";
    } else {
        backToTopButton.style.display = "none";
    }
}
// When the user clicks on the button, scroll to the top of the document
backToTopButton.addEventListener("click", backToTop);

function backToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}