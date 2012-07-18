(function($){
  $.fn.acfNameField = function(){
    return this.each(function(){
      var field = this;

      function isPartRequired(part){
        return $('.required', part).length
      }


      $('.acf-name-part input', field).change(function(){
        var parts = [], value, valid = true;

        function partValue(part){
          var $part = $('.acf-name-part-'+part, field),
              value = $part.val();

          if($part.hasClass('required') && value == '' ){
            valid = false;
          }
          return value;
        }

        parts.push(partValue('prefix'));
        parts.push(partValue('first'));
        parts.push(partValue('middle'));
        parts.push(partValue('last'));
        parts.push(partValue('suffix'));

        value = parts.join('|');

        if(valid){
          $('.acf-name-value', field).val(value);
        }
        else{
          $('.acf-name-value', field).val('').data('acf-name-value', value);
        }
      })
      .change();
    })

  }

  $(function(){
    $('.acf-name-field').acfNameField()
  })

})(jQuery)
