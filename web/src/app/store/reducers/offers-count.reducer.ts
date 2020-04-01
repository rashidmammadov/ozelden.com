import { createReducer, on } from '@ngrx/store';
import { setOffersCount } from '../actions/offers-count.action';

export const initialState: number = 0;

export function offersCountReducer(state, action) {
    return createReducer(initialState,
        on(setOffersCount, () => action.offersCount)
    )(state, action);
}
