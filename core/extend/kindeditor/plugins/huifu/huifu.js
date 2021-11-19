KindEditor.plugin('huifu', function(K) {
        var editor = this, name = 'huifu';
        // 点击图标时执行
        editor.clickToolbar(name, function() {
               var content= $('#content_beifen').val();
                
                editor.html(content);
              // alert($('#content_beifen'));
        });
});