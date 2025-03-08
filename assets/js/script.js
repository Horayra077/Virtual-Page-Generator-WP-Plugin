
// Template One JS 

$(document).ready(function () {
    $('.owl-carousel-one').owlCarousel({
        loop: true,
        autoWidth: false,
        dots: false,
        margin: 29,
        autoplay: true,
        autoplayTimeout: 2000,
        autoplaySpeed: 2000,
        slideTransition: 'linear',
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 2,
                margin: 20
            },
            360: {
                items: 3,
                margin: 20
            },
            576: {
                items: 4,
                margin: 32
            },
            767: {
                items: 6,
                margin: 10
            },
            1024: {
                items: 8,
                margin: 9
            },
            1200: {
                items: 10,
                margin: 17
            },
            1400: {
                items: 13
            }
        }
    });
    $('.owl-carousel-two').owlCarousel({
        loop: true,
        autoWidth: false,
        dots: false,
        margin: 29,
        rtl: true,
        autoplay: true,
        autoplayTimeout: 2000,
        autoplaySpeed: 2000,
        slideTransition: 'linear',
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 2,
                margin: 20
            },
            360: {
                items: 3,
                margin: 20
            },
            576: {
                items: 4,
                margin: 32
            },
            767: {
                items: 6,
                margin: 10
            },
            1024: {
                items: 8,
                margin: 9
            },
            1200: {
                items: 10,
                margin: 17
            },
            1400: {
                items: 13
            }
        }
    })
});

// End Template One JS 