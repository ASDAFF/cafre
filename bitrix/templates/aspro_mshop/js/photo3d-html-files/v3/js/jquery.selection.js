/*

$("img").disableSelection();
$("div").disableSelection();
$(".class").disableSelection();
$("#id").disableSelection();

*/

jQuery.fn.extend({ 
    disableSelection : function() { 
            this.each(function() { 
                    this.onselectstart = function() { return false; }; // IE, Chrome, Safari
                    this.unselectable = "on"; // IE, Opera
                    jQuery(this).css('-moz-user-select', 'none'); // FF
            }); 
    },
    enableSelection : function() { 
            this.each(function() { 
                    this.onselectstart = function() {}; 
                    this.unselectable = "off"; 
                    jQuery(this).css('-moz-user-select', 'auto'); 
            }); 
    }
});