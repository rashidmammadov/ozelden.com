import { createAction, props } from '@ngrx/store';
import { MissingFieldsType } from '../../interfaces/missing-fields-type';

export const setMissingFields = createAction('[Missing Fields] Set', props<{missingFields: MissingFieldsType}>());
