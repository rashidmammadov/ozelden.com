export interface AnnouncementType {
    announcement_id: number,
    tutored_id: number;
    student_id?: number;
    lecture_area: string;
    lecture_theme?: string;
    city: string;
    district?: string;
    min_price?: number;
    max_price?: number;
    currency?: string;
    sex?: string;
    status: number;
}
