import { createReducer, on } from '@ngrx/store';
import { setMissingFields } from '../actions/missing-field.action';
import { MissingFieldsType } from '../../interfaces/missing-fields-type';

export const initialState: MissingFieldsType = {picture: null, lecture: null, region: null};

export function missingFieldsReducer(state, action) {
    return createReducer(initialState,
        on(setMissingFields, () => action.missingFields)
    )(state, action);
}
