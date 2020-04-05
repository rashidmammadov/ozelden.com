import { AverageType } from './average-type';
import { ExpectationType } from './expectation-type';
import { RegionType } from './region-type';
import { StudentType } from './student-type';
import { TutorLectureType } from './tutor-lecture-type';

export interface InfoType {
    id: number;
    name: string;
    surname: string;
    birthday: string | number;
    age: number | string | Date;
    sex: string;
    picture: string;
    profession?: string;
    description?: string;
    average?: AverageType;
    regions?: RegionType[];
    lectures?: TutorLectureType[];
    expectation?: ExpectationType;
    student?: StudentType;
    boost?: boolean;
}
