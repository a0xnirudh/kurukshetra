$(document).ready(function(){    
    var sortable = true;
    
    $('#table').bootstrapTable({
        url: "api/get_all_users_data.php",
        columns: [{
            field: 'id',
            title: 'Id',
            valign: 'middle',
            align: "center"
        },{
            field: 'name',
            title: 'Name',
            valign: 'middle',
            sortable: sortable,
            width: "40%",
            formatter: function (data, row, type){
                return row.first_name+" "+row.last_name;
            }
        }, {
            field: 'email',
            title: 'Email',
            align: 'center',
            valign: 'middle',
            sortable: sortable,
            formatter: function (data, row, type){
                return "<a href='"+row.link+"'>"+data+"</a>";
            }
        }, {
            field: 'picture',
            title: 'Pic',
            align: 'center',
            valign: 'middle',
            formatter: function (data, row, type){
                return "<img src='"+data+"' width=64px/>";
            }
        }, {
            field: 'is_admin',
            title: 'Admin?',
            align: 'center',
            valign: 'middle',
            events: is_admin,
            formatter: operateFormatter,
            sortable: sortable
        }, {
            field: 'enabled',
            title: 'Enable?',
            align: 'center',
            valign: 'middle',
            events: enable_user,
            formatter: operateFormatter,
            sortable: sortable
        }, {
            field: 'updated_by',
            title: 'Updated By',
            align: 'center',
            valign: 'middle'
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

window.is_admin = {
    'click .like': function (e, value, row, index) {
        // console.log(row);
        var url = "api/update_user_state.php";
        action = "remove_admin";
        if(row.is_admin != 1)
            action = "make_admin";
        var data = {
            id: row.id,
            action: action
        };

        $.post(url, data, function(data1,success){
            // alert('User with id: '+row.id+' is Updated');
            $.getJSON("api/get_all_users_data.php",function(data1,success){
                $("#table").bootstrapTable("load",data1);
                var info = "";
                if(action == "make_admin")
                {
                    info += "Admin Access Provided";
                    $("#result").removeClass("alert alert-danger");
                    $("#result").addClass("alert alert-success");
                }
                else
                {
                    info += "Admin Access Revoked";
                    $("#result").removeClass("alert alert-success");
                    $("#result").addClass("alert alert-danger");
                }
                
                $("#result").html(info); 
            });            
        });
    }
};

window.enable_user = {
    'click .like': function (e, value, row, index) {
        console.log(row);
        var url = "api/update_user_state.php";
        action = "disabled";
        if(row.enabled != 1)
            action = "enabled";

        var data = {
            id: row.id,
            action: action
        };

        $.post(url, data, function(data1,success){
            // alert('User with id: '+row.id+' is '+action);
            $.getJSON("api/get_all_users_data.php",function(data1,success){
                $("#table").bootstrapTable("load",data1);
                var info = "";
                if(action == "enabled")
                {
                    info += "User Enabled";
                    $("#result").removeClass("alert alert-danger");
                    $("#result").addClass("alert alert-success");
                }
                else
                {
                    info += "User Disabled";
                    $("#result").removeClass("alert alert-success");
                    $("#result").addClass("alert alert-danger");
                }
                
                $("#result").html(info); 
                  //adding class
              
            });
        });
        // alert('You click like action, row: ' + JSON.stringify(row));
    }
};











