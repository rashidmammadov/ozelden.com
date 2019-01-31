(function(){
    'use strict';

    function ozSuitabilitySchedule($filter) {

        function table(scope, elem, attrs) {
            var margin = { top: 16, right: 16, bottom: 32, left: 0 },
                width = 512,
                height = 450 - margin.top - margin.bottom,
                gridSize = Math.floor(width / 7),
                colors = ['#f0f0f0', '#721C47'],
                days = ['MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY'],
                times = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00', '24:00'];

            var tooltip = d3.select(elem[0]).append('div')
                .classed('element-tooltip', true)
                .style('display', 'none');

            var svg = d3.select(elem[0]).append('svg')
                .attr('width', width - 170)
                .attr('height', height + margin.top + margin.bottom)
                .append('g')
                .attr('transform', 'translate(' + margin.left + ',' + margin.top + ')');

            svg.selectAll('.day-label')
                .data(days)
                .enter().append('text')
                .text(function (d) { return $filter('translate')(d); })
                .attr('x', function (d, i) { return i * gridSize / 1.9; })
                .attr('y', 0)
                .style('text-anchor', 'middle')
                .attr('transform', 'translate(64,8)')
                .style('fill', '#707070');

            svg.selectAll('.time-label')
                .data(times)
                .enter().append('text')
                .text(function(d) { return d; })
                .attr('x', 0)
                .attr('y', function(d, i) { return i * gridSize / 3.3; })
                .style('text-anchor','middle')
                .attr('transform', 'translate(20, 32)')
                .style('fill', '#707070');

            var heatMapChart = function(data) {
                var colorScale = d3.scaleLinear().domain([0, 1])
                    .range([colors[0], colors[1]]);

                var cards = svg.selectAll('.hour')
                    .data(data, function(d) {
                        return d.day + ':' + d.hour;
                    });

                cards.enter().append('rect')
                    .attr('x', function(d) { return (d.day - 1) * gridSize / 1.9; })
                    .attr('y', function(d) { return (d.hour - 8) * gridSize / 3.3; })
                    .attr('rx', 2)
                    .attr('ry', 2)
                    .attr('class', 'hour bordered')
                    .attr('width', gridSize / 2.1)
                    .attr('height', gridSize / 4)
                    .attr('transform', 'translate(46, 18)')
                    .style('fill', function (d) { return (d.value) > 0 ? colors[1] : colors[0]; })
                    .style('cursor', 'pointer')
                    .on('mousemove', drawTooltip)
                    .on('mouseout', function () { tooltip.style('display', 'none'); })
                    .on('click', function (d) {
                        d.value = (d.value === 0 ? 1 : 0);
                        heatMapChart(data);
                        scope.onClick(d);
                        scope.$apply();
                    });

                cards.transition().duration(300)
                    .style('fill', function(d) { return colorScale(d.value); });

                cards.select('title').text(function(d) { return d.value; });
                cards.exit().remove();
            };

            function drawTooltip(d) {
                tooltip.style('left', event.layerX + 16 + 'px');
                tooltip.style('top', event.layerY - 16 + 'px');

                tooltip.style('display', 'inline-block');
                tooltip.html('<b>' + $filter('translate')(days[Number(d.day) - 1]) + '</b>' +
                    ' (' + Number(d.hour - 1) + ':00 - ' + d.hour + ':00)' +
                    '<div class="status" style="background-color: ' + (d.value ? 'green' : '#b3b3b3') + '"></div>');
            }

            scope.$on('$drawDayHourTable', function(event, data){
                heatMapChart(data);
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