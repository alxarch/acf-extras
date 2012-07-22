(function($){
  acf_youtube = {
  select: function(field){
    var $field = $(field);

    $dialog = $('.acf-youtube-dialog', field).clone();
    $dialog.find('.acf-youtube-go').click(function(){return false});
    $url = $dialog.find('.acf-youtube-url input[type="text"]').change(function(){

      vid = $url.val().replace(/^.*\?v=([a-zA-Z0-9]+).*$/, '$1');
      if(!vid) {
        $('<div>').addClass('error').text($field.data('msg-invalid-url')).hide().appendTo($select).fadeIn(500).wait(4000).fadeOut(200);

        return;
      };

      $.get('https://gdata.youtube.com/feeds/api/videos/'+vid+'?v=2', function(data){
        var $data = $(data),
            title = $data.find('entry > title').text(),
            user = $data.find('entry > author > name').text(),
        
        $preview = $('<div>').addClass('acf-youtube-preview')
        $('<img>',{
            src: 'http://img.youtube.com/vi/'+vid+'/0.jpg',
            width: 120,
            height: 90
        }).appendTo($preview)

        $('<h4>').text(title).appendTo($preview);
        $('<h5>', {text: 'by: '+user}).appendTo($preview);

        $preview.appendTo($dialog);

        $dialog.dialog('option', 'buttons', [
          {text:'confirm', click: function(){
            $('.acf-youtube-title', field).val(title);
            $('input.acf-youtube-value', field).val(vid);
            $(this).dialog('close')}},
          {text:'cancel', click: function(){$(this).dialog('close')}}
        ]).show();
      });
    });
    
    $dialog.dialog({
      modal: true,
      width: 480,
      dialogClass: 'wp-dialog',
      title: $field.data('msg-dialog-title'),
      buttons: [{text: 'cancel', click: function(){$(this).dialog('close')}}]
    })
  }
}
})(jQuery)
