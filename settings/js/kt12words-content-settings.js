(function( $ ) {
	'use strict';
	$(function() {
		$('#kt12word_allowed_post_types').select2({ width: '288px' });
      $('.btn-rotate').click(function(){
          $('.card-front').toggleClass(' rotate-card-front');
          $('.card-back').toggleClass(' rotate-card-back');
      });
  });

  // For card rotation
})( jQuery );
