/*
 * lang_property.js
 * 
 * version 1.0
 * 
 * by Sean Kim
 * 
 */


/*
* for editing dialog
*/

let dialogModified = false;
let curScrollPos = 0;

function initDialog() {
    $("body").css("overflow", "hidden");
    $(".edit-dialog form input").first().focus();
    $('.edit-dialog form input').on('keypress', function (e) {
        return e.which !== 13;
    });
    $('#button-cancel').click(function (event) {
        if (!dialogModified || confirm("There is unsaved changes in the properties. Are you sure to cancel change?")) {
            $(".edit-dialog").remove();
            $("body").css("overflow", "auto");
        }
    });
    $(".edit-dialog form input").change(function() {
        dialogModified = true;
    });
}


/*
* display language property form
* @returns {undefined}
*/
function editProperty() {
    if (navigator.onLine) {
        param = "prop[lang_code]=" + settings.lang;
        curScrollPos = $(document).scrollTop();
        $.ajax({
            type: "POST",
            url: "/publications/translator/edit-property",
            data: param
        }).done(function (data) {
            $("body").append($(data));
            initDialog();
            console.log("editProperty - initDialog()");
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log("Error:" + errorThrown + "; " + jqXHR.responseText);
            alert("Error:" + errorThrown + "; " + jqXHR.responseJSON.message);
        }).always(function (jqXHR, textStatus, errorThrown) {
            $(document).scrollTop(curScrollPos);
        });
    } 
    else{
        alert("Please connect to internet to edit language property!!!")
    }
}


/*
* display book property form
* @returns {undefined}
*/
function editBookNames() {
    if (navigator.onLine) {
        curScrollPos = $(document).scrollTop();
        $.ajax({
            type: "POST",
            url: "/publications/translator/edit-book-name",
            data: {'lang_code': settings.lang}
        }).done(function (data) {
            $("body").append($(data));
            initDialog();
            console.log("editBookNames - initDialog()");
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log("Error:" + errorThrown + "; " + jqXHR.responseText);
            alert("Error:" + errorThrown + "; " + jqXHR.responseJSON.message);
        }).always(function (jqXHR, textStatus, errorThrown) {
            $(document).scrollTop(curScrollPos);
        });
    } 
    else {
        alert("Please connect to internet to edit book names!!!")
    }
}



$(function () 
{
    $(document).on("keyup", function (event) {
        let code = (event.keyCode ? event.keyCode : event.which);

        // move to next .target-text
        if (code === 27 && $(".dialog").length) {    // ESC
            if (!dialogModified || confirm("There is unsaved changes in the properties. Are you sure to cancel change?")) {
                $(".dialog").remove();
                $("body").css("overflow", "auto");
            }
        }
    });

    $(document).on('submit', '#edit-lang-prop', function (event) {
        // replace lang code
        if (settings.lang != 'en' && $("input[name='prop[lang_code]']").val() == 'en') {
            $("input[name='prop[lang_code]']").val(settings.lang);
            $("input[name='prop[lang_code3]']").val(settings.lang);
        }
        $.post("/publications/translator/edit-property", $(this).serialize())
            .done(function (data) {
                alert("Change has been submitted! The changes will be applied from next book.");
                dialogModified = false;
                console.log("editProperty - submitted");
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log("Error:" + errorThrown + "; " + jqXHR.responseText);
                alert("Error:" + errorThrown + "; " + jqXHR.responseJSON.message);
            }).always(function() {
                $('#button-cancel').click();
            });
        event.preventDefault();
    });

    $(document).on('submit', '#edit-book-name', function (event) {
        $.post("/publications/translator/edit-book-name", $(this).serialize())
                .done(function (data) {
                    alert("Change has been saved. The changes will be applied from next book.");
                    $(".dialog").remove();
                    dialogModified = false;
                    console.log("editBookNames - submitted");
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    console.log("Error:" + errorThrown + "; " + jqXHR.responseText);
                    alert("Error:" + errorThrown + "; " + jqXHR.responseJSON.message);
                }).always(function() {
                    $('#button-cancel').click();
                });
        
        event.preventDefault();
    });

});

