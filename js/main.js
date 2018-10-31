
(function($) {	
	$(document).ready(function() {
		$('body').on('click', '#addBtn', function() {			
			var form_data = new FormData();	
				form_data.append('image', $('#image').prop('files')[0]);
				form_data.append('width', $('#width').val());		
				form_data.append('height', $('#height').val());				
			$.ajax({
				url: './api.php',
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				type: 'post',
				success: function(data){
					$('.card-body').append(' <div class="row mt-3 justify-content-center"><div class="alert alert-danger" role="alert">'+data.message+'</div></div>');
					$(".alert").fadeOut(5000);	
				}
			});		
		});		
	});
})(jQuery);