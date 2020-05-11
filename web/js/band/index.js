$('body').on('click', function (e) {
    $('[data-toggle="popover-x"]').each(function () {
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            let $this = $(this), href = $this.attr('href'),
                $dialog = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))), //strip for ie7
                option = $dialog.data('popover-x') ? 'toggle' : $.extend({remote: !/#/.test(href) && href});
            $dialog.popoverX('hide').on('hide');
        }
    });
});
