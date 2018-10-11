$(document).ready(function(){    
    var sortable = true;
    
    $('#table').bootstrapTable({
        url: "api/get_all_containers.php",
        columns: [{
            field: 'id',
            title: 'ID',
            valign: 'middle',
            align: "left"
        },{
            field: 'container_id',
            title: 'Container ID',
            align: 'center',
            valign: 'middle',
            formatter: function (data, row, type){
                return data.substring(0,12);
            }
        },{
            field: 'email_id',
            title: 'Email',
            valign: 'middle',
            sortable: sortable
        },{
            field: 'timestamp',
            title: 'Up Time',
            align: 'center',
            valign: 'middle',
            formatter: uptimeformatter
        },{
            field: 'container_id',
            title: 'Action',
            align: 'center',
            valign: 'middle',
            formatter: operateFormatter
        }]
    });
});


function operateFormatter(value, row, index) {
    return "<a class='kill btn btn-sm btn-danger' style='width: 100px' href='javascript:kill_chall(\""+value.substring(0,12)+"\")' > Kill </a>";
}

function uptimeformatter(data,row,type){
    var current_time = new Date();
    var uptime = current_time - Date.parse(data);
    var seconds = uptime/1000;
    var days = Math.floor(seconds / (3600*24));
    seconds  -= days*3600*24;
    var hours   = Math.floor(seconds / 3600);
    seconds  -= hours*3600;
    var minutes = Math.floor(seconds / 60);
    var return_string = "";

    if(days > 0)
        return_string += "<button class='btn btn-xs btn-danger'>"+days+"</button> days ";

    if(hours > 0)
        return_string += "<button class='btn btn-xs btn-primary'>"+hours+"</button> hours ";

    if(minutes > 0)
        return_string += "<button class='btn btn-xs btn-success'>"+minutes+"</button> minutes ";

    return return_string;

}

function kill_chall(container_id){
    var url = "api/kill_challenge.php?id="+container_id;
    $.get(url, function(data,success){
        var info = "";
        if(data.status == true)
        {
            info += "Successfully Killed container with ID: "+container_id;
            $("#result").removeClass("alert alert-danger");
            $("#result").addClass("alert alert-success");
        }
        else
        {
            info += "Failed to kill the challenge.<br />Error Message: "+data.message;
            $("#result").removeClass("alert alert-success");
            $("#result").addClass("alert alert-danger");
        }
        
        $("#result").html(info); 
        $.getJSON("api/get_all_containers.php",function(data1,success){
            $("#table").bootstrapTable("load",data1);
        });            
    });
}




