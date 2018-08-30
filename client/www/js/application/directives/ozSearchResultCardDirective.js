(function(){
    'use strict';

    function ozSearchResultCard($filter, $timeout) {

        function link(scope, elem, attrs){
            var margin = {top: 16, left: 16, right: 16, bottom: 16};
            var height = 75;
            var currentDate = new Date().getTime();

            function toRadians(degs) {
                return Math.PI * degs / 180;
            }

            var radialScale = d3.scaleLinear().domain([0, 10])
                .range([0, toRadians(359)]);

            var arc = d3.arc()
                .innerRadius(16 * 1.70)
                .outerRadius(16 * 2)
                .startAngle(0);

            function drawList(data){
                var id = Number(data.id);
                var userCard = d3.select(elem[0]).data([data]);
                var width = document.querySelectorAll("oz-search-result md-card")[0].clientWidth;
                userCard.selectAll('.oz-user-card-content .tab').style('display', 'none');
                userCard.select('[tab-index="0"]').style('display', 'block');

                var cardHeader = userCard.select('.oz-user-card-header .name');
                cardHeader.html(function(d){
                    return '<h3>' + d.name + ' ' +  d.surname +'</h3>' + '<p> Tutor </p>';
                });

                var userImage = userCard.select('.oz-user-card-info .image');
                userImage.append('div')
                    .attr('style', function(d){
                        var image;
                        if (!d.image){
                            if (d.sex === 'mars'){
                                image = 'background: url("img/default/male-user-white.png") no-repeat center center;';
                            } else {
                                image = 'background: url("img/default/female-user-white.png") no-repeat center center;'
                            }
                        } else {
                            image = 'background: url(' + d.image + ') no-repeat center center;';
                        }
                        return image + 'width: 64px; height: 64px; border-radius: 100%;'
                    });

                userCard.select('.oz-user-card-info .location').html(function(d){
                    return d.location;
                });
                userCard.select('.oz-user-card-info .birthday').text(function(d){
                    return dateDifference(currentDate, d.birthDate, 'year', 'AGE');
                });
                userCard.select('.oz-user-card-info .register').html(function(d){
                    return dateDifference(currentDate, d.registerDate, 'month');
                });

                var xRange = d3.scaleBand()
                    .domain( [0, 1, 2] )
                    .rangeRound([margin.left, width - margin.left - margin.right]).paddingInner(0.9).paddingOuter(0.2);

                var overall = userCard.select('.oz-user-card-content .overall graph').append('svg')
                    .attr('width', width - margin.left - margin.right).attr('height', height)
                    .attr('transform', 'translate(' + margin.left + ',' + 0 + ')')
                    .selectAll('.pie-chart').data(function(d){
                        return [d.expression, d.attention, d.contact];
                    });

                var overallDiv = overall.enter().append('g')
                    .attr('width', (width - margin.left - margin.right)/3)
                    .attr('transform', function(d, i) {return 'translate(' +  xRange(i)  + ',' + 0 + ')' } );

                overallDiv.append('path')
                    .attr('d', arc.endAngle(function(d){
                        return radialScale(d);
                    }))
                    .style('fill', '#D1A377')
                    .attr('transform', 'translate(' +  0  + ',' + margin.top*2 + ')');

                overallDiv.append('text').attr('class', 'size')
                    .text(function(d){return d;})
                    .attr('transform', 'translate(' +  (-11)  + ',' + 36 + ')');

                userCard.selectAll('.oz-user-card-action button')._groups.forEach(function(button){
                    button.forEach(function(tab){
                        angular.element(tab).on('click', function(){
                            var pageIndex = angular.element(tab).attr('tab-index');
                            changeTab(id, pageIndex);
                        });
                    });
                });
            }

            function dateDifference(current, given, range, type){
                var result;
                var dateA = new Date(current);
                var dateB = new Date(given);
                if(range === 'year'){
                    var year = dateA.getFullYear() - dateB.getFullYear();
                    result = year ? year + ' ' + $filter('translate')(type) : '?';
                } else if(range === 'month'){
                    result = ((dateA.getFullYear() - dateB.getFullYear())*12 + dateA.getMonth()) - dateB.getMonth();
                    var year = Math.floor(result / 12);
                    var month = result % 12;
                    if (!year && !month) {
                        result = $filter('translate')('NEW');
                    } else {
                        result =  (year ? year + ' ' + $filter('translate')('Y') : '') + ' ' +
                            (month ? month + ' ' + $filter('translate')('M') : '');
                    }
                }
                return result;
            }

            function changeTab(cardIndex, index) {
                var userCard = d3.select('oz-search-result #user-' + cardIndex + ' md-card');
                var tabs = angular.element(userCard.selectAll('.oz-user-card-content .tab')._groups[0]);
                var actions = angular.element(userCard.selectAll('.oz-user-card-action button')._groups[0]);
                tabs.css('display', 'none');
                actions.removeClass('active');
                angular.element(tabs[index]).css('display', 'block');
                angular.element(actions[index]).addClass('active');
            }

            scope.$watch('user', function(data) {
                $timeout(function() { drawList(data) });
            }, true);
        }

        return {
            restrict: 'E',
            templateUrl: 'html/directive/ozSearchResultCard.html',
            link: link,
            scope: {
                user: '=ngUserData'
            }
        }
    }

    angular.module('ozelden.directives').directive('ozSearchResultCard', ozSearchResultCard);
})();