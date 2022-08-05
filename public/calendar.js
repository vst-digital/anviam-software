jQuery(document).ready(function(t, e, i) {
    jQuery.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN' : jQuery('meta[name="csrf-token"]').attr('content')
        }
    });

    function t(t) {
        t.each(function() {
            var t = {
                title: jQuery.trim(jQuery(this).text())
            };
            jQuery(this).data("eventObject", t), jQuery(this).draggable({
                zIndex: 1070,
                revert: !0,
                revertDuration: 0
            })
        })
    }
    t(jQuery("#external-events div.external-event"));
    var e = new Date,
        i = e.getDate(),
        n = e.getMonth(),
        r = e.getFullYear();
        var calendar = jQuery("#calendar").fullCalendar({
        timeFormat: 'hh:mm a',
        header: {
            left: "prev,next today",
            center: "title",
            right: "month,agendaWeek,agendaDay"
        },
        buttonText: {
            today: "today",
            month: "month",
            week: "week",
            day: "day"
        },
        events:'/admin/calendar_data',
        editable: !0,
        selectable: !0,
        droppable: !0,
        drop: function(t, e) {
            var i = jQuery(this).data("eventObject"),
                n = jQuery.extend({}, i);
            n.start = t, n.allDay = e, n.backgroundColor = jQuery(this).css("background-color"), n.borderColor = jQuery(this).css("border-color"), jQuery("#calendar").fullCalendar("renderEvent", n, !0), jQuery("#drop-remove").is(":checked") && jQuery(this).remove()

        },
        eventClick: function(calEvent, jsEvent, view) {
            jQuery("input[name=editreferenceto]").removeAttr('checked');
            var jQuerythis = this;
            var loggedinUser    = jQuery('#loggedinuser').val();
            var createdby       = calEvent.created_by;
            var editshow = disabled = '';
            jQuery('.viewonly').hide();
            if(loggedinUser != createdby){
                jQuery('.owneredit').hide();
                jQuery('#editreferenceSelecter').find('input').attr('disabled','disabled');
                // jQuery('.justview').show();
                // jQuery('.justprojectview').show();
                editshow = 1;
                //disabled = 'disabled="disabled"';
                jQuery('#editEventLabel').html('View Event');
                jQuery("#editEvent").find('.delete-event').hide();
                jQuery('#save_event').hide();
                jQuery('#delete_event').hide();
            }else{
                jQuery('.owneredit').show();
                // jQuery('.justprojectview').hide();
                jQuery('#editreferenceSelecter').find('input').removeAttr('disabled');
                jQuery('#editEventLabel').html('Edit Event');
                jQuery('#delete_event').show(0);
                jQuery('#save_event').show(0);
            }
            //console.log(calEvent);
            jQuery("#editEventName").val(calEvent.title);
            jQuery("#editEventDes").val(calEvent.description);
            jQuery("#editEventId").val(calEvent.id);
            var users = JSON.parse(calEvent.users);
            jQuery('#editEventUsers').val(users);
            jQuery('#editEventUsers').select2().trigger('change');
            jQuery("#editEventStarts").datetimepicker("date", calEvent.start)
            jQuery("#editEventEnds").datetimepicker("date", calEvent.end)

            jQuery("input[name=editreferenceto][value=" + calEvent.referenceto + "]").prop('checked', true);
            jQuery('.commondrp').hide();
            jQuery('.editshow'+calEvent.referenceto+editshow).show();
            if(calEvent.referenceto == 1){
                jQuery('#editMemos').val(calEvent.references);
                jQuery('#editMemos').select2().trigger('change');
            }else if(calEvent.referenceto == 2){
                jQuery('#editProjects').val(calEvent.references);
                jQuery('#editProjects').select2().trigger('change');
            }else{
                jQuery('.editshow'+calEvent.referenceto+editshow).hide();
                // jQuery('.viewonly').hide();
            }

            jQuery("#editEvent").modal({
                backdrop: 'static'
            });
            jQuery("#editEvent").find('.delete-event').off("click").on('click',function(e) {
                swal({
                    title: 'Are you sure?',
                    text: 'This record and it`s details will be permanently deleted!',
                    icon: 'warning',
                    buttons: ["Cancel", "Yes!"],
                }).then(function(value) {
                    if (value) {
                        jQuery("#calendar").fullCalendar('removeEvents', function(ev) {
                            jQuery('#cover-spin').show(0);
                            var id = calEvent.id;
                            jQuery.ajax({
                                url:"/admin/calendar_action",
                                type:"POST",
                                data:{
                                    id:id,
                                    type:"delete"
                                },
                                success:function(response)
                                {
                                    jQuery('#cover-spin').hide(0);
                                    calendar.fullCalendar('refetchEvents');
                                    swal("Event Deleted Successfully");
                                },error:function(response){
                                    calendar.fullCalendar('refetchEvents');
                                }
                            })


                            return (ev._id == calEvent._id);
                        });
                    }else{
                        return false;
                    }
                });
                jQuery("#editEvent").modal('hide');
            });
            jQuery("#editEvent").find('.save-event').off('click').on('click',function() {

                calEvent.title = jQuery("#editEventName").val();
                calEvent.start = jQuery("#editEventStarts").val();
                calEvent.end = jQuery("#editEventEnds").val();
                calEvent.description = jQuery("#editEventDes").val();
                calEvent.users = jQuery("#editEventUsers option:selected").map(function(){ return this.value }).get().join(",");
                calEvent.id = jQuery("#editEventId").val();
                //var categoryClass = jQuery("#addColor [type=radio]:checked").data("color");
                calEvent.referenceto = jQuery("#editreferenceSelecter [type=radio]:checked").data("value");
                calEvent.projects = jQuery("#editProjects option:selected").val();
                calEvent.memos = jQuery("#editMemos option:selected").val();
                jQuery("#calendar").fullCalendar('updateEvent', calEvent);

                console.log(calEvent);
                if (calEvent.title.length == 0) {
                    swal('Please add a title to your event');
                    return false;
                }else if (calEvent.users.length == 0) {
                    swal('Please select atleast one user to your event');
                    return false;
                }else if (new Date(calEvent.end) < new Date(calEvent.start)) {
                    swal('End date should be greater that start date.');
                    return false;
                }else{
                    jQuery('#cover-spin').show(0);
                    jQuery.ajax({
                        url:"/admin/calendar_action",
                        type:"POST",
                        data:{
                            title: calEvent.title,
                            start: calEvent.start,
                            end: calEvent.end,
                            description: calEvent.description,
                            referenceto: calEvent.referenceto,
                            users: calEvent.users,
                            id: calEvent.id,
                            projects: calEvent.projects,
                            memos: calEvent.memos,
                            type: 'update'
                        },
                        success:function(response)
                        {
                            calendar.fullCalendar('refetchEvents');
                            swal("Event Updated Successfully");
                            jQuery('#cover-spin').hide(0);
                        },error:function(response)
                        {
                            jQuery('#cover-spin').hide(0);
                        }
                    });
                }

                jQuery("#editEvent").modal('hide');
                return false;
            });
        },


        select: function(start, end, allDay) {
            jQuery.noConflict();
            var jQuerythis = this;
            jQuery("#addEvent").modal({
                backdrop: 'static',
                keyboard: false
            });
            var token = jQuery("#csrf_token_header").val();
            jQuery("#eventStarts").datetimepicker("date", start);
            jQuery("#eventEnds").datetimepicker("date", end);
            var form = jQuery("#addEventForm");
            jQuery("#addEvent").find('.save-event').off('click').on('click',function() {
                var title = form.find("#eventName").val();
                var start = form.find("#eventStarts").val();
                var end = form.find("#eventEnds").val();
                var description = form.find("#eventDes").val();
                var users = jQuery("#eventUsers option:selected").map(function(){ return this.value }).get().join(",");
                var projects = jQuery("#addProjects option:selected").val();
                var memos = jQuery("#addMemos option:selected").val();
                var categoryClass = form.find("#addColor [type=radio]:checked").data("color");
                var referenceto = form.find("#referenceSelecter [type=radio]:checked").data("value");
                //console.log(users.length);
                if (title.length == 0) {
                    swal('Please add a title to your event');
                    return false;
                }else if (users.length == 0) {
                    swal('Please select atleast one user to your event');
                    return false;
                }else if (new Date(end) < new Date(start)) {
                    swal('End date should be greater that start date.');
                    return false;
                }else{
                    jQuery('#cover-spin').show(0);
                    jQuery.ajax({
                        url:"/admin/calendar_action",
                        type:"POST",
                        data:{ _token: token,
                            title: title,
                            color: categoryClass,
                            start: start,
                            end: end,
                            description: description,
                            referenceto: referenceto,
                            users: users,
                            memos: memos,
                            projects: projects,
                            type: 'add'
                        },
                        success:function(data)
                        {
                            calendar.fullCalendar('refetchEvents');
                            jQuery('input[type="text"],input[type="email"],input[type="tel"], file, textarea').val('');
                            jQuery('#cover-spin').hide(0);
                            swal("Event Created Successfully");
                        },error:function(response){
                            jQuery('#cover-spin').hide(0);
                        }
                    });

                    jQuery("#addEvent").modal('hide');
                }
                return false;
            });
            jQuery("#calendar").fullCalendar('unselect');

        }
    });
    var a = "#3c8dbc";
    jQuery("#color-chooser-btn");
    jQuery("#color-chooser > li > a").on("click", function(t) {
        t.preventDefault(), a = jQuery(this).css("color"), jQuery("#add-new-event").css({
            "background-color": a,
            "border-color": a
        })
    }), jQuery("#add-new-event").on("click", function(e) {
        e.preventDefault();
        var i = jQuery("#new-event").val();
        if (0 != i.length) {
            var n = jQuery("<div />");
            n.css({
                "background-color": a,
                "border-color": a,
                color: "#fff"
            }).addClass("external-event"), n.html(i), jQuery("#external-events").prepend(n), t(n), jQuery("#new-event").val("")
        }
    });
    jQuery('.checkboxcls').on('click',function(){
        var rel = jQuery(this).attr('rel');
        jQuery('.commondrp').hide();
        jQuery('.viewonly').hide();
        if(rel != 'nothing'){
           jQuery('.'+rel).show();
        }
    });
})
