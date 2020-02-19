import { createAction, props } from '@ngrx/store';
import { CityType } from '../../interfaces/city-type';

export const setCities = createAction('[Cities] SetCities', props<{cities: CityType[]}>());
