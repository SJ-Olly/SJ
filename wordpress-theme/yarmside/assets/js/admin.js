/* Property gallery picker: choose extra photos with the media library. */
(function ($) {
  'use strict';

  var frame;

  function ids() {
    var value = $('#yarmside_gallery').val();
    return value ? value.split(',').filter(Boolean) : [];
  }

  function renderPreview(selection) {
    var $preview = $('#yarmside-gallery-preview').empty();
    selection.forEach(function (attachment) {
      var thumb =
        (attachment.sizes && attachment.sizes.thumbnail && attachment.sizes.thumbnail.url) ||
        attachment.url;
      $('<img>', {
        src: thumb,
        css: { width: '80px', height: '60px', 'object-fit': 'cover' }
      }).appendTo($preview);
    });
  }

  $(document).on('click', '#yarmside-gallery-add', function (e) {
    e.preventDefault();

    if (frame) {
      frame.open();
      return;
    }

    frame = wp.media({
      title: 'Choose gallery photos',
      button: { text: 'Use these photos' },
      library: { type: 'image' },
      multiple: 'add'
    });

    frame.on('open', function () {
      var selection = frame.state().get('selection');
      ids().forEach(function (id) {
        var attachment = wp.media.attachment(id);
        attachment.fetch();
        selection.add(attachment);
      });
    });

    frame.on('select', function () {
      var selection = frame.state().get('selection').toJSON();
      $('#yarmside_gallery').val(
        selection
          .map(function (attachment) {
            return attachment.id;
          })
          .join(',')
      );
      renderPreview(selection);
    });

    frame.open();
  });

  $(document).on('click', '#yarmside-gallery-clear', function (e) {
    e.preventDefault();
    $('#yarmside_gallery').val('');
    $('#yarmside-gallery-preview').empty();
  });
})(jQuery);
