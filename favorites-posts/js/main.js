jQuery(document).ready( function($){
	$('a.favorites-link').on('click', function(e){
		e.preventDefault();
		var flag = $(this).data('action');
		$.ajax({
			type: 'POST',
			url: obj.ajaxurl,
			data: {
				action: 'favoritePosts',
				security: obj.nonce,
				postId: obj.postId,
				flag: flag,
			},
			beforeSend: function(){
				$('.favorites-link').fadeOut(300, function(){
					$('.fp-loader').fadeIn(300);
				});
			},
			success: function(res){
				$('.fp-loader').fadeOut(300, function(){
					$('.block-add-favorite-post').html(res);
				});
			},
			error: function(){
				alert('Error!');
			}
		});
	} );
});