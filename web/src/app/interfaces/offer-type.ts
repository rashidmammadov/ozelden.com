export interface OfferType {
    offer_id: number;
    sender_id: number;
    receiver_id: number;
    student_id?: number;
    sender_type: string;
    tutor_lecture_id: number;
    offer: number;
    currency?: string;
    status: number;
}
