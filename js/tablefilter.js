function filterTable(table, input) {
    doFiltering = function(filter) {
        $(table).find('tbody').find('tr').each(function () {
            var row = $(this);
            var html = "";
            row.find('.indexable').each(function () {
                html += $(this).text() + "\n";
            });
            //alert(html + "\n" + filter);
            if (html.toUpperCase().indexOf(filter) != -1) {
                row.show();
            } else {
                row.hide();
            }
        });
    }

    val = "";
    binding = function(key) {
        filtre = $.trim($(input).val()).toUpperCase();
        if (filtre != val) {
            val = filtre;
            doFiltering(filtre);
        }
    }

    $(input).bind('keyup', binding);
}