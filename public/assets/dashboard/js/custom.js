$(document).ready(function() {
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });
});

$(document).on('click', '.krishna .dropdown-menu', function (e) {
    e.stopPropagation();
});


$(document).ready(function() {
    $(document).on("change",".timezone_switcher",function (){
        $(".timezone_switcher_form").submit();
    });

    $(document).on('keyup','.textareaWithTextLimit',function() {
        let maxLength = parseInt($(this).attr("maxlength"));
        let text_length = maxLength - $(this).val().length;
        $(this).parent().find('.rchars').text(text_length);
    });

    $('#txtDate4').change(function() {
        if($('#txtDate4').val()!="")
        {
            if($('#txtDate4').val()<$('#txtDate12').val())
            {

                alert("Offer expiry date should be greater than or equal to the date of deposit");
                $('#txtDate4').val('');
            }
        }
    });

    $(".custom-data-tables").parent().addClass("table-responsive");

    $("#url").click(function(){

        $("#fileinput").hide();
        $("#url").hide();

        $('#ffile').removeAttr('required');


        $("#urlinput").show();
        $("#addffile").show();

        $("#uurl").attr("required", true);

    });

    $("#addfile").click(function(){

        $("#urlinput").hide();
        $("#addfile").hide();

        $('#uurl').removeAttr('required');


        $("#fileinput").show();
        $("#addfile").show();
        $("#ffile").attr("required", true);
    });

});

function validate(file) {
    var ext = file.split(".");
    ext = ext[ext.length-1].toLowerCase();
    var arrayExtensions = ["pdf","jpg"];
    if (arrayExtensions.lastIndexOf(ext) == -1) {
        alert("Wrong extension type only PDF Files are allowed.");
        $("#file").val('');
    }
}

$(function(){
    var dtToday = new Date();

    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();

    var maxDate = year + '-' + month + '-' + day;

    $('#txtDate4').attr('min', maxDate);
    $('#txtDate4').attr('max', "2050-01-01");
});


$(function(){
    var dtToday = new Date();

    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();

    var maxDate = year + '-' + month + '-' + day;

    $('#txtDate1').attr('min', maxDate);
    $('#txtDate1').attr('max', "2050-01-01");
});

function check_fields()
{
    var empty = true;

    $('input[type="text"]').each(function(){
        if($(this).val()!=""){
            empty =false;
            return false;
        }
    });

    if(empty==true)
    {
        location='index.php';
    }
    else if(empty==false)
    {
        $('#myModal').modal('show');
    }

}

function thousandSeparator(n, sep) {
    let sRegExp = new RegExp('(-?[0-9]+)([0-9]{3})'),
        sValue = n + '';

    if (sep == undefined  ) { sep = ','; }

    while (sRegExp.test(sValue)) {
        sValue = sValue.replace(sRegExp, '$1' + sep + '$2');
    }

    return sValue;
}

function showSeparator(id) {
    let myValue = document.getElementById(id).value;
    let check_value="";
    check_value=myValue.slice(-1);

    myValue = thousandSeparator(myValue.replace(/,/g, ""), ',');
    document.getElementById(id).value = myValue;

    if ($("#dep_amm").val() <=0) {
        //alert("Value Less than 1 can't be used ");
        document.getElementById(id).value = 1;
    }
}

function isNumberKey(evt){
    if ((evt.which != 46 || evt.value.indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) {
    }
    var input = evt.value;
    if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
        return false;
    }
}

function reset()
{
    $(':input','#myform')
        .not(':button, :submit, :reset, :hidden')
        .val('')
        .removeAttr('checked')
        .removeAttr('selected');
}

$(document).ready(function() {

    $('#chat_modal').modal('show');

});

(function () {
    // hold onto the drop down menu
    let dropdownMenu;

    // and when you show it, move it to the body
    $(window).on('show.bs.dropdown', function (e) {

        // grab the menu
        dropdownMenu = $(e.target).find('.dropdown-menu');
        if (!dropdownMenu.hasClass('dropdown-menu-navbar')) {

            // detach it and append it to the body
            $('body').append(dropdownMenu.detach());

            // grab the new offset position
            let eOffset = $(e.target).offset();

            // make sure to place it where it would normally go (this could be improved)
            dropdownMenu.css({
                'display': 'block',
                'top': eOffset.top + $(e.target).outerHeight(),
                'left': eOffset.left
            });
        }
    });
        // and when you hide it, reattach the drop down, and hide it normally
        $(window).on('hide.bs.dropdown', function (e) {
            if (!dropdownMenu.hasClass('dropdown-menu-navbar')) {
                $(e.target).append(dropdownMenu.detach());
                dropdownMenu.hide();
            }
        });
})();