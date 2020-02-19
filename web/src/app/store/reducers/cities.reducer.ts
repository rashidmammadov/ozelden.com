import { createReducer, on } from '@ngrx/store';
import { setCities } from '../actions/cities.action';
import { CityType } from '../../interfaces/city-type';

export const initialState: CityType[] = [];

export function citiesReducer(state, action) {
    return createReducer(initialState,
        on(setCities, () => action.cities)
    )(state, action);
}
