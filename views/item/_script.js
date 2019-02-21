$('i.glyphicon-refresh-animate').hide();

function updateItems(r) {
    _opts.items.available = r.available;
    _opts.items.assigned = r.assigned;
    search('available');
    search('assigned');
}

$('.btn-assign').click(function () {
    var $this = $(this);
    var target = $this.data('target');
    var type = $this.data('type');
    var items = $('select.list[data-target="' + target + '"]').val();

    if (type == 'show' && items && items.length) {
        $this.children('i.glyphicon-refresh-animate').show();
        $.post($this.attr('href'), {items: items}, function (r) {
            updateItems(r);
        }).always(function () {
            $this.children('i.glyphicon-refresh-animate').hide();
        });
    } else {
        if ($.isArray(r.assigned)) {
            r.assigned = new Object();
        }
        if ($.isArray(r.available)) {
            r.available = new Object();
        }

        $.each(items, function (index, value) {
            if (type == 'assign') {
                $("input[name='availableItem[]'][value='" + value + "']").remove();
                str = "<input type='hidden' name='AuthItem[assignedItem][]' value=" + value + ">";
                r.assigned[value] = r.available[value];
                delete r.available[value];
            } else if (type == 'remove') {
                $("input[name='assignedItem[]'][value='" + value + "']").remove();
                str = "<input type='hidden' name='AuthItem[availableItem][]' value=" + value + ">";
                r.available[value] = r.assigned[value];
                delete r.assigned[value]
            }
            $('.row').append(str);
        });
        updateItems(r)
    }

    return false;
});

$('.search[data-target]').keyup(function () {
    search($(this).data('target'));
});

function search(target) {
    var $list = $('select.list[data-target="' + target + '"]');
    $list.html('');
    var q = $('.search[data-target="' + target + '"]').val();

    var groups = {
        role: [$('<optgroup label="Roles">'), false],
        permission: [$('<optgroup label="Permission">'), false],
        route: [$('<optgroup label="Routes">'), false],
    };

    $.each(_opts.items[target], function (name, group) {
        if (name.indexOf(q) >= 0) {
            $('<option>').text(name).val(name).appendTo(groups[group][0]);
            groups[group][1] = true;
        }
    });
    $.each(groups, function () {
        if (this[1]) {
            $list.append(this[0]);
        }
    });
}

// initial
search('available');
search('assigned');

var r = new Array();
r['available'] = _opts.items['available'];
r['assigned'] = _opts.items['assigned'];
r['success'] = true;

