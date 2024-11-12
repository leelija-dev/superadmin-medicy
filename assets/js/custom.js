(function($) {
    "use strict";

    /* ==============================================
    AFFIX - Conditional on desktop only
    ============================================== */
    if ($(window).width() >= 768) {
        $('.megamenu').affix({
            offset: {
                top: 0,
                bottom: function() {
                    return (this.bottom = $('.footer').outerHeight(true))
                }
            }
        });
    }

    /* ==============================================
    BACK TOP - Appears after scrolling down a bit
    ============================================== */
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('.dmtop').css({ bottom: "75px" });
        } else {
            $('.dmtop').css({ bottom: "-100px" });
        }
    });

    /* ==============================================
    PRELOADER - Fades out after loading
    ============================================== */
    $(window).on('load', function() {
        $("#preloader").delay(500).fadeOut();
        $(".preloader").delay(600).fadeOut("slow");
    });

    /* ==============================================
    COUNTER - Animates statistics (desktop only)
    ============================================== */
    if ($(window).width() >= 768) {
        function count($this) {
            let current = parseInt($this.html(), 10);
            current = current + 50;
            $this.html(++current);
            if (current > $this.data('count')) {
                $this.html($this.data('count'));
            } else {
                setTimeout(function() { count($this) }, 30);
            }
        }
        $(".stat_count, .stat_count_download").each(function() {
            $(this).data('count', parseInt($(this).html(), 10));
            $(this).html('0');
            count($(this));
        });
    }

    /* ==============================================
    TOOLTIP - Initialize tooltips and popovers
    ============================================== */
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    /* ==============================================
    CONTACT FORM AJAX - Handles form submission
    ============================================== */
    $(document).ready(function() {
        $('#contactform').submit(function() {
            var action = $(this).attr('action');
            $("#message").slideUp(750, function() {
                $('#message').hide();
                $('#submit').after('<img src="images/ajax-loader.gif" class="loader" />').attr('disabled', 'disabled');
                $.post(action, {
                    first_name: $('#first_name').val(),
                    last_name: $('#last_name').val(),
                    email: $('#email').val(),
                    phone: $('#phone').val(),
                    select_service: $('#select_service').val(),
                    select_price: $('#select_price').val(),
                    comments: $('#comments').val(),
                    verify: $('#verify').val()
                }, function(data) {
                    $('#message').html(data).slideDown('slow');
                    $('#contactform img.loader').fadeOut('slow', function() { $(this).remove() });
                    $('#submit').removeAttr('disabled');
                    if (data.match('success') != null) $('#contactform').slideUp('slow');
                });
            });
            return false;
        });
    });

    /* ==============================================
    CODE WRAPPER - Handles design preview sliding
    ============================================== */
    $('.code-wrapper').on("mousemove", function(e) {
        var offsets = $(this).offset();
        var fullWidth = $(this).width();
        var mouseX = e.pageX - offsets.left;

        mouseX = Math.max(0, Math.min(mouseX, fullWidth));

        $(this).parent().find('.divider-bar').css({
            left: mouseX,
            transition: 'none'
        });
        $(this).find('.design-wrapper').css({
            transform: `translateX(${mouseX}px)`,
            transition: 'none'
        });
        $(this).find('.design-image').css({
            transform: `translateX(${(-1 * mouseX)}px)`,
            transition: 'none'
        });
    });

    $('.divider-wrapper').on("mouseleave", function() {
        $(this).parent().find('.divider-bar').css({
            left: '50%',
            transition: 'all .3s'
        });
        $(this).find('.design-wrapper').css({
            transform: 'translateX(50%)',
            transition: 'all .3s'
        });
        $(this).find('.design-image').css({
            transform: 'translateX(-50%)',
            transition: 'all .3s'
        });
    });

})(jQuery);

/* ==============================================
TYPEWRITER - Text animation
============================================== */
var TxtType = function(el, toRotate, period) {
    this.toRotate = toRotate;
    this.el = el;
    this.loopNum = 0;
    this.period = parseInt(period, 10) || 2000;
    this.txt = '';
    this.tick();
    this.isDeleting = false;
};

TxtType.prototype.tick = function() {
    var i = this.loopNum % this.toRotate.length;
    var fullTxt = this.toRotate[i];

    this.txt = this.isDeleting ? fullTxt.substring(0, this.txt.length - 1) : fullTxt.substring(0, this.txt.length + 1);
    this.el.innerHTML = '<span class="wrap">' + this.txt + '</span>';

    var that = this;
    var delta = this.isDeleting ? 100 : 200 - Math.random() * 100;

    if (!this.isDeleting && this.txt === fullTxt) {
        delta = this.period;
        this.isDeleting = true;
    } else if (this.isDeleting && this.txt === '') {
        this.isDeleting = false;
        this.loopNum++;
        delta = 500;
    }

    setTimeout(function() { that.tick() }, delta);
};

window.onload = function() {
    var elements = document.getElementsByClassName('typewrite');
    for (var i = 0; i < elements.length; i++) {
        var toRotate = elements[i].getAttribute('data-type');
        var period = elements[i].getAttribute('data-period');
        if (toRotate) {
            new TxtType(elements[i], JSON.parse(toRotate), period);
        }
    }

    // Inject CSS for the typewriter effect
    var css = document.createElement("style");
    css.type = "text/css";
    css.innerHTML = ".typewrite > .wrap { border-right: 0.08em solid #fff}";
    document.body.appendChild(css);
};

/* ==============================================
MAP INITIALIZATION
============================================== */
function myMap() {
    var mapProp = {
        center: new google.maps.LatLng(51.508742, -0.120850),
        zoom: 5,
    };
    var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
}