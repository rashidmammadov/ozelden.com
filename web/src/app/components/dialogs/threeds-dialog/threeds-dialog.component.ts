import { Component, ElementRef, Inject, OnInit, ViewChild } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { MAT_DIALOG_DATA } from '@angular/material/dialog';
import { GoogleAnalyticsService } from '../../../services/google-analytics/google-analytics.service';

@Component({
    selector: 'app-threeds-dialog',
    templateUrl: './threeds-dialog.component.html',
    styleUrls: ['./threeds-dialog.component.scss']
})
export class ThreedsDialogComponent implements OnInit {

    @ViewChild('iframe', { static: true }) iframe: ElementRef;
    trustedHTML: any;

    constructor(private sanitizer: DomSanitizer, @Inject(MAT_DIALOG_DATA) public data: string) { }

    ngOnInit(): void {
        this.trustedHTML = this.sanitizer.bypassSecurityTrustHtml(this.data);
        setTimeout(() => this.setIframe(this.iframe));
        GoogleAnalyticsService.confirm3DSSecurity();
    }

    private setIframe(iframe: ElementRef): void {
        const win: Window = iframe.nativeElement.contentWindow;
        const doc: Document = win.document;
        doc.open();
        doc.write(this.data);
        doc.close()
    }

}
