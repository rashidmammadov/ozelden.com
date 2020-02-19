import { createReducer, on } from '@ngrx/store';
import { setLectures } from '../actions/lectures.action';
import { LectureType } from '../../interfaces/lecture-type';

export const initialState: LectureType[] = [];

export function lecturesReducer(state, action) {
    return createReducer(initialState,
        on(setLectures, () => action.lectures)
    )(state, action);
}
