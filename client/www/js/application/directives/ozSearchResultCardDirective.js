(function(){
    'use strict';

    function ozSearchResultCard($filter, $timeout) {

        function link(scope, elem, attrs) {
            var graphsConfig = liquidFillGaugeDefaultSettings();
            graphsConfig.minValue = 0;
            graphsConfig.maxValue = 10;
            graphsConfig.displayPercent = false;
            graphsConfig.circleColor = '#D1A377';
            graphsConfig.textColor = '#721C47';
            graphsConfig.waveTextColor = '#FFFFFF';
            graphsConfig.waveColor = '#D1A377';

            var height = 75;

            function drawList(data) {
                if (data) {
                    var userId = 'user-' + data.id;
                    var userCard = d3.select(elem[0]).data([data]);

                    var graphData = [data.contact, data.discipline, data.expression];
                    var graphs = userCard.select('.oz-user-card-content .overall graph').selectAll('svg')
                        .data(graphData);

                    graphs.exit().remove();
                    graphs.enter().append('svg')
                        .attr('id', function (d, i) { return userId + '-graph-' + i; })
                        .attr('width', '33.33%').attr('height', height);

                    graphData.forEach(function (value, i) {
                        d3.select('#'+ userId + '-graph-' + i)._groups[0][0] &&
                            loadLiquidFillGauge(userId + '-graph-' + i, value, graphsConfig);
                    });
                }
            }

            scope.$watch('user', function(data) {
                data && $timeout(function() { drawList(data) });
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