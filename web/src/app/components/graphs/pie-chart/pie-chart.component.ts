import { Component, ElementRef, HostListener, Input, OnChanges, ViewChild } from '@angular/core';
import * as d3 from 'd3';
import { TYPES } from 'src/app/constants/types.constant';

let element;
let arc;
let outerArc;
let color;
let pie;
let slice;
let legend;
let legendData;
let svg;
let key;
let text;
let polyline;
let width: number;
let height: number;
let radius: number;
let margin: { top: number, right: number, bottom: number, left: number };
const DIVIDER = 16;
@Component({
    selector: 'app-pie-chart',
    templateUrl: './pie-chart.component.html',
    styleUrls: ['./pie-chart.component.scss']
})
export class PieChartComponent implements OnChanges {

    @ViewChild('graph_container', {static: true}) graphContainer: ElementRef;
    @Input() data: any[];

    constructor() { }

    @HostListener('window:resize', ['$event'])
    onResize() {
        this.prepare();
        this.draw(this.data);
    }

    ngOnChanges() {
        if (!this.data) { return; }
        this.prepare();
        this.draw(this.data);
    }

    private prepare() {
        element = this.graphContainer.nativeElement;
        margin = {
            top: DIVIDER,
            right: DIVIDER,
            bottom: DIVIDER,
            left: DIVIDER
        };
        width = element.clientWidth - margin.left;
        height = (element.clientWidth / 2);
        radius = Math.min(width, height) / 2;

        d3.select('svg.pie-chart').remove();

        svg = d3.select(element).append('svg').classed('pie-chart', true)
            .attr('width', width).attr('height', height)
            .append('g').attr('transform', 'translate(' + width / 2 + ',' + height / 2 + ')');

        svg.append('g').attr('class', 'slices');
        svg.append('g').attr('class', 'labels');
        svg.append('g').attr('class', 'lines');
        svg.append('g').attr('class', 'legend');

        pie = d3.pie()
            .sort(null)
            .value((d) => d.value);

        arc = d3.arc()
            .outerRadius(radius * 0.9)
            .innerRadius(radius * 0.5);

        outerArc = d3.arc()
            .innerRadius(radius * 0.9)
            .outerRadius(radius * 0.9);

        key = (d) => { return d.data.key; };

        color = {'male': '#722947', 'female': '#d3a67d'};
        legendData = [{key: '', value: ''}];
    }

    private draw(data) {
        this.drawSlices(data);
        this.drawLabels(data);
        this.drawLines(data);
        this.drawLegend(legendData);
    }

    private drawSlices(data) {
        slice = svg.select('.slices').selectAll('path.slice').data(pie(data), key);

        slice.exit().remove();

        slice.enter()
            .append('path')
            .style('fill', (d) => color[d.data.key])
            .attr('slice', key)
            .attr('d', arc)
            .on('mouseenter', this.mouseenter)
            .on('mouseout', this.mouseout);
    }

    private drawLabels(data) {
        text = svg.select('.labels').selectAll('text').data(pie(data), key);

        text.exit().remove();

        text.enter()
            .append('text')
            .attr('dy', '.35em')
            .attr('transform', (d) => {
                const pos = outerArc.centroid(d);
                pos[0] = radius * (this.midAngle(d) < Math.PI ? 1 : -1);
                return 'translate(' + pos + ')'
            })
            .attr('text-anchor', (d) => {
                return this.midAngle(d) < Math.PI ? 'start': 'end';
            })
            .text((d) => `${TYPES.GENDERS[d.data.key]}: ${d.data.value}`);
    }

    private drawLines(data) {
        polyline = svg.select('.lines').selectAll('polyline').data(pie(data), key);

        polyline.exit().remove();

        polyline.enter()
            .append('polyline')
            .attr('points', (d) => {
                const pos = outerArc.centroid(d);
                pos[0] = radius * 0.95 * (this.midAngle(d) < Math.PI ? 1 : -1);
                return [arc.centroid(d), outerArc.centroid(d), pos];
            });
    }

    private drawLegend(data) {
        legend = svg.select('.legend').selectAll('text').data(data);

        legend.exit().remove();

        legend.enter()
            .append('text')
            .attr('class', 'title')
            .attr('dy', -4)
            .text((d) => d.key);
        legend.enter()
            .append('text')
            .attr('class', 'value')
            .attr('dy', 12)
            .text((d) => d.key + d.value);
    }

    private midAngle(d) {
        return d.startAngle + (d.endAngle - d.startAngle) / 2;
    }

    private mouseenter(d) {
        svg.select('path[slice="' + key(d) + '"]').classed('focus', true);
        d3.select('.legend .title').text(TYPES.GENDERS[d.data.key]);
        d3.select('.legend .value').text(d.data.value);
    }

    private mouseout(d) {
        svg.select('path[slice="' + key(d) + '"]').classed('focus', false);
        d3.select('.legend .title').text(legendData[0].key);
        d3.select('.legend .value').text(legendData[0].value);
    }
}
