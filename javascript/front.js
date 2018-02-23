function getAndFill(from, number, tag, target) {
    $.get(window.location + "api/pictures?from=" + from + "&number=" + number + "&tag=" + tag, function (data) {
        $.each(data, function (i) {
            $(target).append(
                '<li id="' + data[i].id + '">'
                + '<img src="' + data[i].picture + '"/>'
                + '<ul class="tags" id="tags-' + data[i].id + '"></ul>'
                + '<div class="delete" id="delete-' + data[i].id + '">x</div>'
                + '<label>' + data[i].caption + '</label>'
                + '</li>'
            );
            $.each(data[i].tags, function(y) {
                if (data[i].tags[y] === tag) {
                    $('#tags-' + data[i].id).append(
                        '<li class="tag searched">'
                        + data[i].tags[y]
                        + '</li>'
                    );
                }
                else {
                    $('#tags-' + data[i].id).append(
                        '<li class="tag">'
                        + data[i].tags[y]
                        + '</li>'
                    );
                }
            });
            $('#delete-' + data[i].id).on('click', function() {
                /*if (confirm("Delete this photo permanently ?")) {*/
                    $.del(window.location + "api/pictures/" + data[i].id, '', function() {
                        $('#' + data[i].id).fadeOut('slow', function () {
                            $('#' + data[i].id).remove();
                        })
                    }, 'DELETE');
                /*}*/
            });
        });
    });
}

