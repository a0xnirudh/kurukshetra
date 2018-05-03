$(document).ready(function(){
    var sortable = true;
    
    $('#table').bootstrapTable({
        url: "api/get_all_hint_data.php",
        columns: [{
            field: 'id',
            title: 'Id',
            valign: 'middle',
            align: "center",
            sortable: sortable
        },{
            field: 'hint_text',
            title: 'Hint',
            valign: 'middle',
            sortable: sortable
        }, {
            field: 'challenge_id',
            title: 'Challenge ID',
            align: 'center',
            valign: 'middle',
            sortable: sortable
        },{
            field: 'enabled',
            title: 'Enabled?',
            valign: 'middle',
            align: "center",
            sortable: sortable,
            events: enable_hint,
            formatter: operateFormatter,
        }]
    });
});


function operateFormatter(value, row, index) {
    
    var checked = "";
    if(value == "1"){
        checked = "checked";
    }
    else{
        checked = "";
    }
    // console.log(checked);
    return [
        '<a class="like" href="javascript:void(0)" >',
            '<label class="switch">',
              '<input style="width:24px" type="checkbox"'+checked+'>',
              '<span class="slider"></span>',
            '</label>',
        '</a>'
    ].join('');
}

window.enable_hint = {
    'click .like': function (e, value, row, index) {
        console.log(row);
        var url = "api/update_hint_state.php";
        action = "disabled";
        if(row.enabled != 1)
            action = "enabled";

        var data = {
            id: row.id,
            action: action
        };

        $.post(url, data, function(data1,success){
            print_message(row, action);
            $.getJSON("api/get_all_hint_data.php",function(data1,success){
                $("#table").bootstrapTable("load",data1);
            });
        });
    }
};

function print_message(row, action){
    var info = 'HINT with id: '+row.id+' is '+action;

    if(action == "approved" || action == "enabled" || action == "updated")
    {
        $("#result").removeClass("alert alert-danger");
        $("#result").addClass("alert alert-success");
    }
    else
    {
        $("#result").removeClass("alert alert-success");
        $("#result").addClass("alert alert-danger");
    }
    
    $("#result").html(info); 
}











