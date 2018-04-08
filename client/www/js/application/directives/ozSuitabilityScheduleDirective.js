(function(){
    'use strict';

    function ozSuitabilitySchedule($filter) {

        function table(scope, elem, attrs) {
            var margin = { top: 16, right: 16, bottom: 32, left: 0 },
                width = 512,
                height = 450 - margin.top - margin.bottom,
                gridSize = Math.floor(width / 7),
                legendElementWidth = gridSize*2,
                buckets = 9,
                colors = ["#f0f0f0","#721C47","#D1A377","#c7e9b4","#7fcdbb","#41b6c4","#1d91c0","#225ea8","#253494","#081d58"],
                days = ["MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY", "SATURDAY", "SUNDAY"],
                times = ["08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00", "24:00"];

            var svg = d3.select("oz-suitability-schedule").append("svg")
                .attr("width", width-170)
                .attr("height", height + margin.top + margin.bottom)
                .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

            var dayLabels = svg.selectAll(".dayLabel")
                .data(days)
                .enter().append("text")
                //.attr("translate", function (d) { return d; })
                .text(function (d) {
                    return $filter('translate')(d);
                })
                .attr("x", function (d, i) { return i * gridSize / 1.9; })
                .attr("y", 0)
                .style("text-anchor", "middle")
                .attr("transform", "translate(64,8)")
                .style("fill", function (d, i) { return ((i >= 0 && i <= 4) ? "#000000" : "#b3b3b3"); });

            var timeLabels = svg.selectAll(".timeLabel")
                .data(times)
                .enter().append("text")
                .text(function(d) { return d; })
                .attr("x", 0)
                .attr("y", function(d, i) { return i * gridSize / 3.3; })
                .style("text-anchor", "middle")
                .attr("transform", "translate(20, 32)")
                .style("fill", function(d, i) { return ((i >= 0 && i <= 10) ? "#000000" : "#b3b3b3"); });

            var heatmapChart = function(data) {
                var colorScale = d3.scaleLinear().domain([0, 1])
                    .range([colors[0], colors[1]]);

                var cards = svg.selectAll(".hour")
                    .data(data, function(d) {
                        return d.day+':'+d.hour;
                    });

                //cards.append("title");

                cards.enter().append("rect")
                    .attr("x", function(d) {
                        return (d.day - 1) * gridSize / 1.9;
                    })
                    .attr("y", function(d) {
                        return (d.hour - 8) * gridSize / 3.3;
                    })
                    .attr("rx", 2)
                    .attr("ry", 2)
                    .attr("class", "hour bordered")
                    .attr("width", gridSize / 2.1)
                    .attr("height", gridSize / 4)
                    .attr("transform", "translate(46, 18)")
                    .style("fill", function (d) {
                        return (d.value) > 0 ? colors[1] : colors[0];
                    })
                    .style("cursor", "pointer")
                    .on("click", function (d) {
                        d.value = (d.value == 0 ? 1 : 0);
                        heatmapChart(data);
                        scope.onClick(d);
                        scope.$apply();
                    });

                cards.transition().duration(300)
                    .style("fill", function(d) { return colorScale(d.value); });

                cards.select("title").text(function(d) { return d.value; });
                cards.exit().remove();
            };

            scope.$on('$drawDayHourTable', function(event, data){
                heatmapChart(data);
            });
        }

        return {
            restrict: 'E',
            link: table,
            scope: {
                onClick: '=onClick'
            }
        }

    }
    angular.module('ozelden.directives').directive('ozSuitabilitySchedule', ozSuitabilitySchedule);
})();