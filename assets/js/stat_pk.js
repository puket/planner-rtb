function setStat(ajurl, sku, ty) {
    $.ajax({
        type:'post',
        url: ajurl,
        data: { sku:sku,ty:ty },
        dataType:'html'
    }).done(function( res ){
    });	
}