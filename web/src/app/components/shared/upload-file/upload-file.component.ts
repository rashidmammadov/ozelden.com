import { Component, EventEmitter, Input, OnChanges, Output, SimpleChanges } from '@angular/core';

@Component({
    selector: 'app-upload-file',
    templateUrl: './upload-file.component.html',
    styleUrls: ['./upload-file.component.scss']
})
export class UploadFileComponent implements OnChanges {
    @Input() text: string;
    @Input() file: string;
    @Input() hideSelectButton: boolean = false;
    @Input() hideUploadButton: boolean = false;
    @Input() height: string = '128px';
    @Output() callbackSelection = new EventEmitter();
    @Output() callbackUpload = new EventEmitter();
    preview: string | ArrayBuffer = this.file;
    selectedFile: File;
    selectedFileName: string;
    disableUploadButton: boolean = true;

    constructor() { }

    ngOnChanges(changes: SimpleChanges): void {
        if (changes.file && changes.file.currentValue) {
            const changedFile = changes.file.currentValue;
            if (typeof changedFile === 'string' || changedFile.includes('base64')) {
                this.preview = changedFile;
            } else {
                this.toBase64(changedFile);
            }
        } else {
            this.preview = './assets/images/logo-mini.png';
        }
    }

    handleFileSelection($event: Event): void {
        const targetElement = <HTMLInputElement>$event.target;
        if (targetElement.files && targetElement.files[0]) {
            const reader = new FileReader();
            this.selectedFile = targetElement.files[0];
            this.selectedFileName = targetElement.files[0].name;
            this.disableUploadButton = false;

            reader.onload = (event: ProgressEvent) => {
                this.preview = (<FileReader>event.target).result;
                this.changeValue(this.preview);
            };
            reader.readAsDataURL(targetElement.files[0]);
        }
    }

    handleFileUpload(): void {
        if (this.selectedFile) {
            this.callbackUpload.emit(this.preview);
            this.disableUploadButton = true;
        }
    }

    private toBase64 = (file) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = (event: ProgressEvent) => {
            this.preview = (<FileReader>event.target).result;
        };
    };

    private changeValue(value) {
        this.callbackSelection.emit(value);
    }

}
