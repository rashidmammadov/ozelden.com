import { createAction, props } from '@ngrx/store';

export const setOffersCount = createAction('[Offers Count] Set', props<{offersCount: number}>());
