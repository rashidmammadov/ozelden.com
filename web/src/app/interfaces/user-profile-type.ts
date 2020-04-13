import { AnnouncementType } from './announcement-type';
import { AverageType } from './average-type';
import { SuitabilityType } from './suitability-type';
import { TutorLectureType } from './tutor-lecture-type';

export interface UserProfileType {
    id: number;
    type: string;
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
    tutored_announcements: AnnouncementType[];
    tutor_statistics: AverageType;
    tutor_lectures: TutorLectureType[],
    tutor_suitability: SuitabilityType
}
