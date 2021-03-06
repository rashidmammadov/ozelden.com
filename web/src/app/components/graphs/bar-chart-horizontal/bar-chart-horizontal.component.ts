import { Component, ElementRef, HostListener, Input, OnChanges, ViewChild } from '@angular/core';
import * as d3 from 'd3';
import {GraphService} from "../../../services/graph/graph.service";

let element;
let svg;
let width: number;
let height: number;
let margin: { top: number, right: number, bottom: number, left: number };
let xAxis;
let yAxis;
let maxValue;
let tooltip;
const DIVIDER = 16;
@Component({
    selector: 'app-bar-chart-horizontal',
    templateUrl: './bar-chart-horizontal.component.html',
    styleUrls: ['./bar-chart-horizontal.component.scss']
})
export class BarChartHorizontalComponent implements OnChanges {

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

        xAxis = d3.scaleLinear()
            .range([0, width - margin.right * 3]);

        yAxis = d3.scaleBand()
            .range([height, 0])
            .padding(0.1);

        d3.select('svg.bar-chart-horizontal').remove();

        svg = d3.select(element).append('svg').classed('bar-chart-horizontal', true)
            .attr('width', width).attr('height', height)
            .append('g').attr('transform', 'translate(' + margin.left * 3 + ',' + 0 + ')');

        d3.select('app-bar-chart-horizontal .directive-tooltip').remove();
        tooltip = d3.select(element).append('div')
            .attr('class', 'directive-tooltip')
            .style('display', 'none');
    }

    private draw(data) {
        this.scaleDomainRange(data);
        this.drawBars(data);
        this.drawLabels(data);
        this.drawYAxis();
    }

    private scaleDomainRange(data) {
        maxValue = d3.max(data, (d) => { return d.value; });
        xAxis.domain([0, maxValue]);
        yAxis.domain(data.map((d) => { return d.key; }));
    }

    private drawBars(data) {
        let bars = svg.append('g').classed('bars', true).selectAll('.bar')
            .data(data);

            bars.enter().append('rect')
                .attr('class', 'bar')
                .attr('bar', (d) => d.key)
                .attr('width', (d) => {return xAxis(d.value); } )
                .attr('y', (d) => { return yAxis(d.key); })
                .attr('height', yAxis.bandwidth())
                .on('mouseover', this.mouseover)
                .on('mousemove', this.mouseover)
                .on('mouseout', this.mouseout);

            bars.exit().remove();
    }

    private drawLabels(data) {
        let labels = svg.append('g').classed('labels', true).selectAll('.label')
            .data(data);

            labels.enter().append('text')
                .classed('label', true)
                .classed('inner', (d) => d.value > maxValue / 1.5)
                .classed('outer', (d) => d.value <= maxValue / 1.5)
                .attr('y', (d) => { return yAxis(d.key) + yAxis.bandwidth() / 2 + 4; })
                .attr('x', (d) => { return d.value <= maxValue / 1.5 ? xAxis(d.value) + 4 : xAxis(d.value) - 4; })
                .text((d) => { return d.value; });

            labels.exit().remove();
    }

    private drawYAxis() {
        svg.append('g')
            .call(d3.axisLeft(yAxis));
    }

    mouseover(d) {
        d3.selectAll('app-bar-chart-horizontal .bar').attr('opacity', 0.5);
        d3.select('app-bar-chart-horizontal .bar[bar="' + d.key + '"]').attr('opacity', 1);

        const event = d3.event;
        tooltip.style('left', GraphService.getTooltipXPosition(event))
            .style('top', GraphService.getTooltipYPosition(event))
            .style('display', 'inline-block')
            .html(GraphService.prepareTooltipHTML(d));
    }

    mouseout() {
        d3.selectAll('app-bar-chart-horizontal .bar').attr('opacity', 1);
        tooltip.style('display', 'none');
    }

}
