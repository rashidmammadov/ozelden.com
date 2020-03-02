import { NgModule } from '@angular/core';
import { MatBadgeModule } from '@angular/material/badge';
import { MatButtonModule } from '@angular/material/button';
import { MatCardModule } from '@angular/material/card';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { MatChipsModule } from '@angular/material/chips';
import { MatDialogModule } from '@angular/material/dialog';
import { MatDividerModule } from '@angular/material/divider';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatSnackBarModule } from '@angular/material/snack-bar';
import { MatIconModule } from '@angular/material/icon';
import { MatSelectModule } from '@angular/material/select';
import { MatSlideToggleModule } from '@angular/material/slide-toggle';
import { MatSortModule } from '@angular/material/sort';
import { MatRadioModule } from '@angular/material/radio';
import { MatTableModule } from '@angular/material/table';
import { MatToolbarModule } from '@angular/material/toolbar';
import { MatTooltipModule } from '@angular/material/tooltip';

@NgModule({
    exports: [
        MatBadgeModule,
        MatButtonModule,
        MatCardModule,
        MatCheckboxModule,
        MatChipsModule,
        MatDialogModule,
        MatDividerModule,
        MatFormFieldModule,
        MatInputModule,
        MatSnackBarModule,
        MatIconModule,
        MatSelectModule,
        MatSlideToggleModule,
        MatSortModule,
        MatRadioModule,
        MatTableModule,
        MatToolbarModule,
        MatTooltipModule
    ]
})
export class AngularMaterialModule {}
