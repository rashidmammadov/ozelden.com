(function(){
    'use strict';

    function ozSearchResultCard($filter, $timeout) {

        function link(scope, elem, attrs) {
            var margin = {top: 16, left: 16, right: 16, bottom: 16};
            var height = 75;

            function toRadians(degs) {
                return Math.PI * degs / 180;
            }

            var radialScale = d3.scaleLinear().domain([0, 10])
                .range([0, toRadians(359)]);

            var arc = d3.arc()
                .innerRadius(16 * 1.70)
                .outerRadius(16 * 2)
                .startAngle(0);

            function drawList(data) {
                var userId = 'user-' + data.id;
                var userCard = d3.select(elem[0]).data([data]);
                var width = document.querySelectorAll("oz-search-result md-card")[0].clientWidth;

                var xRange = d3.scaleBand()
                    .domain( [0, 1, 2] )
                    .rangeRound([margin.left, width - margin.left - margin.right]).paddingInner(0.9).paddingOuter(0.2);

                var graphData = [data.contact, data.discipline, data.expression];
                var graphs = userCard.select('.oz-user-card-content .overall graph').selectAll('svg')
                    .data(graphData);

                graphs.exit().remove();
                graphs.enter().append('svg')
                    .attr('id', function (d, i) {return userId + '-graph-' + i;})
                    .attr('width','33.33%').attr('height', height);

                var graphsConfig = liquidFillGaugeDefaultSettings();
                graphsConfig.minValue = 0;
                graphsConfig.maxValue = 10;
                graphsConfig.displayPercent = false;
                graphsConfig.circleColor = '#D1A377';
                graphsConfig.textColor = '#721C47';
                graphsConfig.waveTextColor = '#721C47';
                graphsConfig.waveColor = '#D1A377';
                graphData.forEach(function (value, i) {
                    loadLiquidFillGauge(userId + '-graph-' + i, value, graphsConfig);
                });


                /*var overall = userCard.select('.oz-user-card-content .overall graph').append('svg')
                    .attr('width', width - margin.left - margin.right).attr('height', height)
                    .attr('transform', 'translate(' + margin.left + ',' + 0 + ')')
                    .selectAll('.pie-chart').data(function(d) { return [d.expression, d.attention, d.contact]; });

                var overallDiv = overall.enter().append('g')
                    .attr('width', (width - margin.left - margin.right)/3)
                    .attr('transform', function(d, i) {return 'translate(' +  xRange(i)  + ',' + 0 + ')' } );

                overallDiv.append('path')
                    .attr('d', arc.endAngle(function(d) { return radialScale(d); }))
                    .style('fill', '#D1A377')
                    .attr('transform', 'translate(' +  0  + ',' + margin.top*2 + ')');

                overallDiv.append('text').attr('class', 'size')
                    .text(function(d) { return d; })
                    .attr('transform', 'translate(' +  (-11)  + ',' + 36 + ')');*/

            }

            scope.$watch('user', function(data) {
                $timeout(function() { drawList(data) });
            }, true);
        }

        return {
            restrict: 'E',
            templateUrl: 'html/directive/ozSearchResultCard.html',
            controller: 'ozSearchResultCardDirectiveCtrl',
            controllerAs: 'Ctrl',
            link: link,
            scope: {
                user: '=ngUserData'
            }
        }
    }

    angular.module('ozelden.directives').directive('ozSearchResultCard', ozSearchResultCard);
})();