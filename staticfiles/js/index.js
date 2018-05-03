$(document).ready(function(){

    $.getJSON("/api/challenges_per_type.php",function(data1,success){ 
        var title = "Challenges as per Type";
        var data = [{
            name: 'no of challenges',
            colorByPoint: true,
            data: data1
        }];

        fill_chart("container", data, title);
    });
    
    $.getJSON("/api/challenges_per_language.php",function(data,success){
        var title = "Challenges as per Language";
        var data = [{
            name: 'no of challenges',
            colorByPoint: true,
            data: data
        }];

        fill_chart("container2", data, title);
    });

    $.getJSON("/api/challenges_per_difficulty.php",function(data,success){
        var title = "Challenges as per Severity";
        var data = [{
            name: 'no of challenges',
            colorByPoint: true,
            data: data
        }];

        fill_chart("container3", data, title);
    });
});

function fill_chart(id,data,title){
    Highcharts.chart(id, {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: title
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        series: data
    });
}

