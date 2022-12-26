jQuery(document).ready(function () {
    ImgUpload();
});

function ImgUpload() {
    var imgWrap = "";


    jQuery('.upload__inputfile').each(function () {
        jQuery(this).on('change', function (e) {
            imgWrap = jQuery(this).closest('.upload__box').find('.upload__img-wrap');
            var maxLength = jQuery(this).attr('data-max_length');
            console.log("max_length", maxLength);
            var files = e.target.files;
            var filesArr = Array.prototype.slice.call(files);
            var iterator = 0;
            var imgArray = [];
            var html = "";
            console.log(filesArr)
            filesArr.forEach(function (f, index) {

                if (!f.type.match('image.*')) {
                    return;
                }
                if (imgArray.length >= maxLength) {
                    return false
                } else {
                    var len = 0;
                    for (var i = 0; i < imgArray.length; i++) {
                        if (imgArray[i] !== undefined) {
                            len++;
                        }
                    }
                    if (len > maxLength) {
                        return false;
                    } else {
                        imgArray.push(f);

                        var reader = new FileReader();
                        reader.onload = function (e) {
                            html += "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + jQuery(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div></div>";
                            jQuery(imgWrap).html(html);

                            iterator++;
                        }
                        reader.readAsDataURL(f);
                    }
                }
            });
        });
    });

    jQuery('body').on('click', ".upload__img-close", function (e) {
        var file = jQuery(this).parent().data("file");
        for (var i = 0; i < imgArray.length; i++) {
            if (imgArray[i].name === file) {
                imgArray.splice(i, 1);
                break;
            }
        }
        jQuery(this).parent().parent().remove();
    });
}