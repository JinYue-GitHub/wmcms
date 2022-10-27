function getForm(option){
	var html = formHtml = select = name = selected = defaultVal = '';
	option = option.data;
	for (o in option){
		formHtml = select = selected = defaultVal = '';
		select = option[o]['option'];
		name = option[o]['name'];
		defaultVal = option[o]['default'];
		value = option[o]['value'];
		if(value != '' && value !== null && value !== undefined){
			defaultVal = option[o]['value'];
		}
		title = option[o]['title'];
		switch ( option[o]['formtype'] )
		{
			//下拉列表
			case 'select':
				for (v in select){
					selected = '';
					if ( defaultVal == select[v] )
					{
						selected = 'selected=""';
					}
					formHtml += '<option value="'+select[v]+'" '+selected+'>'+select[v]+'</option>';
				}
				formHtml = '<b class="select-level"><select class="modify hidden showselect valid" name="field['+name+']" style="display: inline-block; visibility: visible;">'+formHtml+'</select></b>';
				break;

			//文本域
			case 'textarea':
				formHtml = '<textarea name="field['+name+']">'+defaultVal+'</textarea>';
				break;
	
			//单选按钮
			case 'radio':
				for (v in select){
					selected = '';
					if ( defaultVal == select[v] )
					{
						selected = 'checked="1"';
					}
					formHtml += '<label><input name="field['+name+']" type="radio" value="'+select[v]+'" '+selected+' />'+select[v]+'</label>';
				}
				formHtml = '<p>'+formHtml+'</p>';
				break;
	
			//多选按钮
			case 'check':
				for (v in select){
					selected = '';
					for (val in defaultVal){
						if ( select[v] == defaultVal[val] )
						{
							selected = 'checked="1"';
							continue;
						}
				    }  
					
					formHtml += '<label><input name="field['+name+'][]" type="checkbox" value="'+select[v]+'" '+selected+' />'+select[v]+'</label>';
				}
				formHtml = '<p>'+formHtml+'</p>';
				break;
	
			default:
				formHtml = '<input type="text" name="field['+name+']" autocomplete="off" class="midInput recTagInput" value="'+defaultVal+'">';
				break;
		}

		html += '<li><em>'+title+'：</em>'+formHtml+'</li>';
	}
	return html;
}