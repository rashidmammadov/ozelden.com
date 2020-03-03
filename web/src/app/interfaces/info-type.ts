import { AverageType } from './average-type';
import { RegionType } from './region-type';
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
}
