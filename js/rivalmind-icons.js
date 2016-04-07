jQuery(document).ready(function ($) {
    //Get the form template before adding js generated elements.
    //Use .first to ensure we only get the first element
    var formTemplate = $('.rm-icon-boxes .rm-icon-box-form').first().clone(true);
    formTemplate.addClass('.cloned');

    function initialize_plugins(){
        $('.rm-icon-boxes .rm-icon-box-form').not('.cloned').each(function(){
            $(this).find('.chosen-select').chosen({
                disable_search_threshold: 10,
                width: "200px",
            });
            $(this).find('.rm-color-picker').wpColorPicker();
            //$(this).addClass('initialized');
        });
    }

    initialize_plugins();
    //$('.rm-button-color-picker').wpColorPicker();

    $(document).on('chosen:showing_dropdown', '.chosen-select', function () {
        $('.rm_chosen_select .chosen-results li').each(function () {
            $(this).addClass('fa fa-' + $(this).text());
        });
    });

    function box_form(newBox,number, instance){
        newBox.find('h3').html('Box #' + parseInt(instance + 1));
        newBox.find('.rm-input-field').each(function(){
            var name = $(this).attr('name');
            var updated = name.replace('widget-rivalmind-icon-boxes[__i__][boxes][0]', 'widget-rivalmind-icon-boxes[' + number + '][boxes][' + instance + ']');
            $(this).attr('name', updated);
        });
        return newBox;
    }
    /*
    Adds a new icon box
     */
    $(document).on('click', '.rm-add-icon-box', function(e){
        e.preventDefault();
        var widgetForm = $(this).parent().parent();
        var boxes = widgetForm.find('.rm-icon-boxes');
        //var id_base = widgetForm.find('.id_base').val();
        var number = widgetForm.find('.widget_number').val();
        var boxForm = widgetForm.find('.rm-icon-box-form');
        var instance = boxForm.length;
        var newBox = formTemplate.clone(true);
        newBox.removeClass('.cloned');
        boxes.append(box_form(newBox, number, instance));
        initialize_plugins();
    });
    $(document).on('click', 'a.rm-remove-box', function(e){
        e.preventDefault();
        $(this).parent().find('input').each(function(){
            $(this).val('');
        });
        $(this).parent().fadeOut('slow');
    });

});