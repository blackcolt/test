<!DOCTYPE html>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <meta charset="UTF-8">
        <title>X-tractor</title>
        <style>
            label{display: block;}
            img{height: 100px; display: block;}
            a{height: 20px;display: block;}
        </style>
        <script>
            $(function () {
                $('#getIt').click(function () {
                    //Check if at least one of the checkboxes is checked - if not: do not do anything.
                    if ($('.find:checked').length && $('#parse').val() != '') {
                        //If its a text html
                        if ($('#parse').val().indexOf('www') !== 0) {
                            parseHtml($('#parse').val())
                        } else {
                            $.get('api.php', {action: 'getHtml', address: $('#parse').val()}, function (response) {
                                response = JSON.parse(response)
                                if (response.error) {
                                    return;
                                }
                                parseHtml(response.html);
                            });
                        }
                    }

                });
                function parseHtml(htamlText) {
                    var parser = new DOMParser();
                    var htmlDoc = parser.parseFromString(htamlText, "text/xml");
                    var elementObj = {};
                    $.each($('.find:checked'), function (key, value) {
                        elementObj[$(value).attr('id')] = new Array();
                        $.each($(htmlDoc).find($(this).attr('id')), function () {
                            var obj = {}
                            obj[$(value).attr('data-attr')] = $(this).attr($(value).attr('data-attr'));
                            obj['text'] = $(this).text();
                            elementObj[$(value).attr('id')].push(obj);
                        });
                    });
                    buildHtml(elementObj);
                }
                function buildHtml(htmlObjects) {
                    $('#responseDiv').empty();
                    $.each(htmlObjects, function (key, value) {
                        $('#responseDiv').append('Found ' + value.length + ' ' + key + ' tags </br>');
                        $.each(this, function (k, v) {
                            var element = $('<' + key + '>');
                            $.each(v, function (attribute, attributeValue) {
                                if (attribute == 'text') {
                                    element.text(attributeValue);
                                    return;
                                }
                                element.attr(attribute, attributeValue);
                            });
                            $('#responseDiv').append(element);
                        });
                    });
                }
            });
        </script>
    </head>
    <body>
        <label>Source: </label>
        <div>
            <textarea id="parse" placeholder="Enter URL or HTML code">
            </textarea>
            <label>
                <input class="find" type="checkbox" id="a" data-attr='href'/>
                X-tract links
            </label>
            <label>
                <input class="find" type="checkbox" id="img" data-attr='src'/>
                X-tract images
            </label>
        </div>
        <br/>
        <input type="button" id="getIt" value="getIt"/>
        <hr/>

        <div id="responseDiv">


        </div>

    </body>
</html>
