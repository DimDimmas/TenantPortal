$(document).ready(function () {
    var url = 'https://mmproperty.com/wp-json/wp/v2/posts/?per_page=100&orderby=date&status=publish&categories=8&_embed';
    ajaxPost('', url);



    function ajaxPost(data = {}, url) {
        $('#loading').show();
        $.ajax({
            url: url,
            method: 'GET',
            processData: false,
            crossDomain: true,
            contentType: false,
            beforeSend: function (xhr) {
                if (xhr && xhr.overrideMimeType) {
                  xhr.overrideMimeType('application/json;charset=utf-8');
                }
              },
            contentType: 'application/json',
            success: function (result) {
                $('#tenant_news').html(html_format(result));
                $('#loading').hide();
           },
        });
    }


    function html_format(data) {
        var html = '<div class="media-container-row card-columns">';
                $.each(data, function( index, val) {
                    var image = format_image(val._embedded['wp:featuredmedia']['0']);
                    var svg =  '<svg class="bd-placeholder-img card-img-top" width="100%" height="180" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" role="img">';
                        svg +=  '<rect width="100%" height="100%" fill="#868e96"></rect>';
                        svg +=  '</svg>';
                    // html += '<div class="card p-3 col-12 col-md-6 col-lg-4">';
                    // html += '<div class="card-wrapper">';
                    // html += '<div class="card-img"><img src="'+image.source_url+'"  alt="Mobirise" title="" media-simple="true"></div>';
                    // html += '<div class="card-box">';
                    // html += '<h4 class="card-title mbr-fonts-style display-7"><strong>There are Only 2 Types of Cats</strong></h4>';
                    // html += '<p class="mbr-text mbr-fonts-style display-7">';
                    // html += ' Magazine for children. Learning in a game form through diving into the world of amazing discoveries.';
                    // html += '</p>';
                    // html += '</div>';
                    // html += '<div class="mbr-section-btn text-center"><a href="https://mobirise.com" class="btn btn-black display-4">Read online</a></div>';
                    // html += '</div>'
                    // html += '</div>';
                    // if(image.height > image.width) {
                    //     html += vertical_image_card(image, val);
                    // } else {
                    // }
                    html += horizontal_image_card(image, val, svg);

                })
                 
                html += '</div>';
        return html;
    }

    function format_image(image) {
        return image.media_details.sizes.medium;
        // <img 
        // width="1024" height="472" src="https://mmproperty.com/wp-content/uploads/2021/09/IMG-20210830-WA0056-1024x472.jpg" alt="" 
        // class="wp-image-1930" 
        // srcset="
        // https://mmproperty.com/wp-content/uploads/2021/09/IMG-20210830-WA0056-1024x472.jpg 1024w, 
        // https://mmproperty.com/wp-content/uploads/2021/09/IMG-20210830-WA0056-300x138.jpg 300w, 
        // https://mmproperty.com/wp-content/uploads/2021/09/IMG-20210830-WA0056-768x354.jpg 768w, 
        // https://mmproperty.com/wp-content/uploads/2021/09/IMG-20210830-WA0056-1536x708.jpg 1536w, 
        // https://mmproperty.com/wp-content/uploads/2021/09/IMG-20210830-WA0056.jpg 1600w" 
        // sizes="(max-width: 1024px) 100vw, 1024px" />
    }

    function horizontal_image_card(image, val, svg) {
        console.log(val.excerpt.rendered);
        html = '<div class="card" style="width: 18rem;">';
        html += '<img src="'+image.source_url+'" class="card-img-top img-fluid"  style="width: 100%; height: 15vw; object-fit: cover;" >';
        html += '<div class="card-body" style="width: 100%; height: 25vw;">';
        html += '<h5 class="card-title">'+val.title.rendered+'</h5>';
        html += '<div class="card-text"  style="height:100px;">'+val.excerpt.rendered+'</div>';
        html += '</div>';
        html += '<a href="'+val.link+'" class="btn btn-primary btn-block" style="border-radius: 0 !important">Read More</a>';
        html += '</div>';

        return html;
    }

    function vertical_image_card(image, val) {
        html = '<div class="card" style="max-width:18rem;">';
        html += '<div class="row no-gutters">';
        html += '<div class="col-md-4">';
        html += '<img src="'+image.source_url+'"  width="'+image.height+'"  alt="Mobirise" title="" media-simple="true">';
        html += '</div>';
        html += '<div class="col-md-8">';
        html += '<div class="card-body">';
        html += '<h5 class="card-title">'+val.title.rendered+'</h5>';
        html += '<p class="card-text text-truncate"  style="height: 10px;">'+val.excerpt.rendered+'</p>';
        html += '<a href="'+val.link+'" class="btn btn-primary btn-block">Read More</a>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        return html;
    }
})