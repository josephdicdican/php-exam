<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <h3>Cats</h3>
            <div class="row">
                &emsp;<select id="cat-categories"></select><br>
                <p id="paginate"></p>
            </div>
            <div class="row">
                <div id="cat-images" class="col-md-12"></div>
            </div>

        </div>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            var base = 'http://127.0.0.1:8000/api';
            var category_select = $('#cat-categories');

            $.get(base+'/cat/categories', function(data) {
                $.each(data, function(key, row) {
                    category_select.append('<option value="'+row.id+'">'+row.name+'</option>');
                });
            });

            $('body').on('click', '#cat-categories', function() {
                fetch_cat_images($(this).val());
            });

            var fetch_cat_images = function(category_id = '', url = '') {
                var cat_image_div = $('#cat-images');
                cat_image_div.html('');

                if(url == '') {
                    url = base+'/cat/images?category_id='+category_id;
                }

                $.get(url, function(response) {
                    $.each(response.data, function(key, row) {
                        cat_image_div.append('<p><a href="'+row.source_url+'"><img src="'+row.url+'"></a></p>');
                    });

                    var paginate = $('#paginate');
                    paginate.html('');
                    paginate.append('<a '+(response.prev_page_url == null ? 'disabled' : '')+' href="'+response.prev_page_url+'" data-category-id="'+category_id+'" class="btn btn-xs">Prev</i>');
                    paginate.append('<a '+(response.next_page_url == null ? 'disabled' : '')+' href="'+response.next_page_url+'" data-category-id="'+category_id+'" class="btn btn-xs">Next</i>');
                });
            };

            $('#paginate').on('click', 'a', function(e) {
                e.preventDefault();
                var category_id = $(this).data('category-id');
                fetch_cat_images(category_id, $(this).attr('href')+'&category_id='+category_id);
            });

            fetch_cat_images();

        </script>
    </body>
</html>
