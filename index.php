<!Doctype "HTML">

<html lang="en">
<head>
    <title>Data Display</title>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <?php include("apiHandler.php"); ?>

    <!--Method from Google Charts to create charts in HTML-->
    <!--I did not create this method.-->
    <!--This Script is placed in <head> as it needs php functions to work-->
    <!--Original File: https://developers.google.com/chart/interactive/docs/gallery/linechart-->

    <script type="text/javascript">
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([<?php echo getDataWithStartingDate('2016-10-01', '2016-10-31') ?>]);

            var options = {
                title: 'Company Performance',
                curveType: 'function',
                legend: {position: 'bottom'}
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);
        }
    </script>
</head>

<body>
    <div id="curve_chart" style="width: 900px; height: 500px"></div>
</body>

</html>
