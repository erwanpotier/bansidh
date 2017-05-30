

(function ($) {
  Drupal.behaviors.damnId = {
      attach: function (context, settings){
        // Get the entity reference input
        $eref = $('#edit-search-key-word', context);
        if($eref.val()){
          // If field has value on page load, change it.
          var val = $eref.val();
          var match = val.match(/\((.*?)\)$/);
          $eref.data('real-value', val);
          $eref.val(val.replace(match[0], ''));
        }

        // Listen for the autocompleteSelect event
        $eref.once().on('autocompleteclose', function(e, node){
          var val = $(node).data('autocompletevalue');
          var match = val.match(/\((.*?)\)$/);
          // Put the value with id into data storage
          $eref.data('real-value', val);
          // Set the value without the id
          $eref.val(val.replace(match[0], ''));
        }).closest('form').submit(function(e){
          // On form submit, set the value back to the stored value with id
           $eref.val($eref.data('real-value'));
        });
      }
  };
})(jQuery);
