let currentImageIndex = 0;

function loadImage(obj) {
    let src = $(obj).data('src');

    $(".miniatureImages").children("img").each(function (index, element) {
        $(element).removeClass('active');
    });

    $(obj).addClass('active');

    $('.fullImage img').attr('src', src);

    currentImageIndex = $(".miniatureImages img").index(obj);
}

function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % $(".miniatureImages img").length;
    updateImage();
}

function prevImage() {
    currentImageIndex = (currentImageIndex - 1 + $(".miniatureImages img").length) % $(".miniatureImages img").length;
    updateImage();
}

function updateImage() {
    let nextImage = $(".miniatureImages img").eq(currentImageIndex);
    loadImage(nextImage);
}

$("#btnNext").on("click", nextImage);
$("#btnPrev").on("click", prevImage);

loadImage($(".miniatureImages img:first"));
