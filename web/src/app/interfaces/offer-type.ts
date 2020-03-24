import { TutorLectureType } from './tutor-lecture-type';
import { UserType } from './user-type';
import { StudentType } from './student-type';

export interface OfferType {
    offer_id: number;
    sender_id: number;
    sender?: UserType
    receiver_id: number;
    receiver: UserType;
    student_id?: number;
    student?: StudentType;
    sender_type: string;
    tutor_lecture_id: number;
    tutor_lecture?: TutorLectureType;
    offer: number;
    currency?: string;
    status: number;
    offer_type?: string;
    updated_at?: string;
}
