jQuery( document ).ready(function() {
    jQuery( ".delivery_slot_section p.add_row" ).remove();
    jQuery('.delivery_slot_section tr:last-child .remove_row').after('<p class="add_row">+</p>');
    jQuery(".delivery_slot_section p.add_row").live("click",function(){
        var found = jQuery(this).parents('tr');
        var index= jQuery('.delivery_slot_section .special_slot_row').index(found)+1;
        var currentDate = new Date(new Date().getTime()/* + 24 * 60 * 60 * 1000*/);
        var day = ("0" + currentDate.getDate()).slice(-2);  
        var month = ("0" + (currentDate.getMonth() + 1)).slice(-2); 
        var year = currentDate.getFullYear();
        var mindate= year + "-" + month + "-" + day;
        var slot='<tr class="special_slot_row" data-key="'+index+'"><td><select name="sp['+index+'][locaton]"><option value="melbourne">Melbourne</option><option value="sydney">Sydney</option></select></td><td><input required type="date" name="sp['+index+'][date]" min="'+mindate+'"></td><td><input name="sp['+index+'][slot]" required type="number" name="max_slot['+index+']"></td><td><p class="remove_row js">-</p></td></tr>';
        jQuery(this).parents('tr').after(slot);
        jQuery('.delivery_slot_section p.add_row').remove();
        jQuery('.delivery_slot_section tr:last-child .remove_row').after('<p class="add_row">+</p>');
    });

    jQuery(".delivery_slot_section p.remove_row").live("click",function(){
        var didConfirm = confirm("Are you sure to delete this record?");
        if (didConfirm == true) {
            jQuery(this).parent().parent().remove();
            jQuery( ".delivery_slot_section p.add_row" ).remove();
            jQuery('.delivery_slot_section tr:last-child .remove_row').after('<p class="add_row">+</p>');
            return true;
        } else {
            return false;
        }
    });

    
});