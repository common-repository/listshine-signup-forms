/**
 * Created by mariop on 27.2.17.
 */

jQuery(function ($) {
    try{
        tinymce.on('SetupEditor', function (editor) {
            editor.on('init',function(){
                var button = this.buttons['addlistshineform'];
                var obj = forms;
                var i = 0;
                var eventMap = {};
                $.each(obj, function(index, form){
                    if(form['name']!=null) {
                        eventMap[form['name']] = i;
                        i++;
                        button.menu.push({
                            text: form['name'],
                            onclick: function () {
                                var string = "[listshine_form id= " + form['uuid'] +"]";
                                editor.insertContent(string);
                            }
                        });
                    }
                });
            });
        });
    } catch (e){
    }
});