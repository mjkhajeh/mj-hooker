(function($) {
	$(document).ready(function(){
		$(".mjhooker_dl").on( 'click', function(e) {
			e.preventDefault();
			$(".mjhooker_overlay").fadeIn();
			var dir = $(this).parent().parent().attr('data-dir'),
				type = $(this).parent().parent().attr('data-type');
			$.ajax({
				url: mjhooker.ajaxurl,
				type: 'POST',
				data: {
					action: 'mjhooker_dl',
					dir: dir,
					type: type,
				},
				success: function(res) {
					if( res ) {
						if( res.success ) {
							window.location.href = res.data;
							var delBtn = '<a href="#" class="mjhooker_del"><i class="dashicons dashicons-trash"></i></a>';
							$(delBtn).appendTo("tr[data-dir='" + dir + "'][data-type='" + type + "'] td:last-child");
						}
					}
				},
				complete: function() {
					$(".mjhooker_overlay").fadeOut();
				}
			});
		} );

		$(document).on( 'click', ".mjhooker_del", function(e) {
			e.preventDefault();
			$(".mjhooker_overlay").fadeIn();
			var dir = $(this).parent().parent().attr('data-dir'),
				type = $(this).parent().parent().attr('data-type');
			$.ajax({
				url: mjhooker.ajaxurl,
				type: 'POST',
				data: {
					action: 'mjhooker_del',
					dir: dir,
					type: type,
				},
				success: function(res) {
					if( res ) {
						if( res.success ) {
							$("tr[data-dir='" + dir + "'][data-type='" + type + "'] .mjhooker_del").remove();
						}
					}
				},
				complete: function() {
					$(".mjhooker_overlay").fadeOut();
				}
			});
		} );
	});
})(jQuery)