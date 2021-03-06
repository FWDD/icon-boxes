jQuery(document).ready(function ($) {
    //Get the form template before adding js generated elements.
    //Use .first to ensure we only get the first element
    var formTemplate = $('#widget-list .fwdd-icon-box-form').first().clone(true);
    //formTemplate.addClass('.cloned');

    function initialize_plugins(){
        $('#widgets-right .fwdd-icon-box-form').each(function(){
            $(this).find('.chosen-select').chosen({
                disable_search_threshold: 10,
                width: "200px",
            });
            $(this).find('.fwdd-color-picker').wpColorPicker();
        });
        $('#widgets-right .fwdd-button-color-picker').wpColorPicker();
    }

    initialize_plugins();

    $(document).on('chosen:showing_dropdown', '.chosen-select', function () {
        $('.fwdd_chosen_select .chosen-results li').each(function () {
            $(this).addClass('fa fa-' + $(this).text());
        });
    });
    $(document).on('widget-updated widget-added', function(){
        initialize_plugins();
    });
    function box_form(newBox,number, instance){
        newBox.find('h3').html('Box #' + parseInt(instance + 1));
        newBox.find('.fwdd-input-field').each(function(){
            var name = $(this).attr('name');
            var updated = name.replace('widget-fwdd-icon-boxes[__i__][boxes][0]', 'widget-fwdd-icon-boxes[' + number + '][boxes][' + instance + ']');
            $(this).attr('name', updated);
        });
        return newBox;
    }
    /*
    Adds a new icon box
     */
    $(document).on('click', '.fwdd-add-icon-box', function(e){
        e.preventDefault();
        var widgetForm = $(this).parent().parent();
        var boxes = widgetForm.find('.fwdd-icon-boxes');
        var number = widgetForm.find('.multi_number').val();
        if(number === ''){
            number =  widgetForm.find('.widget_number').val();
        }
        var boxForm = widgetForm.find('.fwdd-icon-box-form');
        var instance = boxForm.length;
        var newBox = formTemplate.clone(true);
        boxes.append(box_form(newBox, number, instance));
        initialize_plugins();
    });
    $(document).on('click', 'a.fwdd-remove-box', function(e){
        e.preventDefault();
        $(this).parent().find('input').each(function(){
            $(this).val('');
        });
        $(this).parent().fadeOut('slow');
    });

});