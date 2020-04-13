import { RegionType } from './region-type';
import { TutorLectureType } from './tutor-lecture-type';

export interface UserProfileType {
    id: number;
    picture?: string;
    name: string;
    surname: string;
    boost?: boolean;
    recommend?: boolean;
    profession?: string;
    sex: string;
    birthday: string;
    age: number | string | Date;
    city: string;
    district: string;
    description: string;
    tutor_lectures: TutorLectureType[],
    tutor_suitable_regions: RegionType[]
}
