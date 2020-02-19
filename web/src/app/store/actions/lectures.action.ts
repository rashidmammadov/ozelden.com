import { createAction, props } from '@ngrx/store';
import { LectureType } from '../../interfaces/lecture-type';

export const setLectures = createAction('[Lectures] SetLectures', props<{lectures: LectureType[]}>());
