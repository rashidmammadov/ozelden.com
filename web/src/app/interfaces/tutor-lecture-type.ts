export interface TutorLectureType {
    tutor_lecture_id: number;
    tutor_id: number;
    lecture_area: string;
    lecture_theme: string;
    experience: number;
    price: number;
    currency: string;
    average_try: number;
    price_pleasure?: string;
    price_difference?: number | string;
}
