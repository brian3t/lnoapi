$('body').on('click', function (e) {
    $('[data-toggle="popover-x"]').each(function () {
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            let $this = $(this), href = $this.attr('href'),
                $dialog = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))), //strip for ie7
                option = $dialog.data('popover-x') ? 'toggle' : $.extend({remote: !/#/.test(href) && href});
            if ($dialog.popoverX) $dialog.popoverX('hide').on('hide');
        }
    });
});
function popover_video(video_id){
}
$('#yt_vid_popover').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget) // Button that triggered the modal
    let vidid = button.data('vidid') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    let modal = $(this)
    $(modal.find('#ytlink_first')).attr('src',`https://youtube.com/embed/${vidid}?&autoplay=1`)
})
