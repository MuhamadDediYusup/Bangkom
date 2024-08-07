// check if user scroll down show scroll-top button
$(window).scroll(function () {
    if ($(this).scrollTop() > 200) {
        $(".scroll-top").show();
    } else {
        $(".scroll-top").hide();
    }
});

$(".scroll-top").hide();

// if user click scroll-top button scroll to top
$(".scroll-top").click(function () {
    $("html, body").animate(
        {
            scrollTop: 0,
        },
        700
    );
    return false;
});

var now = new Date();
var clickDate = new Date(now.getTime() + 60 * 60010);

// Update the count down every 1 second
var x = setInterval(function () {
    // Get todays date and time
    var now = new Date().getTime();

    // Find the distance between now an the count down date
    var distance = clickDate - now;

    // Time calculations for days, hours, minutes and seconds
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    // Output the result in an element with id="CountDown"
    document.getElementById("Click").innerHTML =
        "<a>Session Exp. in " + minutes + "m&nbsp;" + seconds + "s</a>";

    // If the count down is over, write some text
    if (distance < 0) {
        clearInterval(x);
        document.getElementById("Click").innerHTML = "<a>Session Expired</a>";

        $(function () {
            swal({
                title: "Sesi Anda telah berakhir",
                text: "Silahkan login kembali",
                type: "warning",
                showCancelButton: false,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "OK",
            }).then((result) => {
                window.location.href = "/login";
            });
        });
    }
}, 1000);
