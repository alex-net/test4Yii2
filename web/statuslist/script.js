$(function(){
	$('.status-list').sortable({
		axis:'y',
		cursor:'move',
		items:'> .status-el',
		stop:function(e,ui){
			var weights=[];
			$(this).find('.status-el input').each(function(ind,el){
				$(this).val(ind);
				var w=this.name.match(/\[(\d+)\]/);
				weights.push(w[1]);
			});
			$.post('/admin/statuses-set-weight',{
				'weights':weights,

			},function(data){
				console.log(data);
			});
			console.log('stop',weights);

		}
	});
});