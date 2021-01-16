jQuery(document).ready( function($){
	$('a.fp-admin-delete').on('click', function(e){
		e.preventDefault();
		if( !confirm('Delete favorite post?') ) return false;
		var post = $(this).data('post'),
				parent = $(this).parent(),
				loader = parent.next(),
				li = $(this).closest('li');
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				action: 'deleteFavoritePostAdmin',
				security: obj.nonce,
				post_id: post,
			},
			beforeSend: function(){
				parent.fadeOut(300, function(){
				loader.fadeIn(300);
				});
			},
			success: function(res){
				loader.fadeOut(300, function(){
					li.html(res);
				});
			},
			error: function(){
				alert('Error!');
			}
		});
	} );
	$('#button-delete-all').on('click', function(e){
		e.preventDefault();
		if( !confirm('Delete all favorite posts?') ) return false;
		 var $this = $(this);
		 		 loader = $(this).next(),
				 parent = $(this).parent(),
				 list = parent.prev();
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				action: 'deleteAllFavoritePostAdmin',
				security: obj.nonce,
			},
			beforeSend: function(){
				$this.fadeOut(300, function(){
				loader.fadeIn(300);
				});
			},
			success: function(res){
				loader.fadeOut(300, function(){
					if(res==1){
						list.fadeOut(300);
						parent.html('All favorite posts deleted');
					} else{
						parent.html('Error delete');
					}
				});
			},
			error: function(){
				alert('Error!');
			}
		});
	} );
});