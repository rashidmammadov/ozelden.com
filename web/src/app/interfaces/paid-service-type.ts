export interface PaidServiceType {
    bid: number;
    boost: string | number;
    recommend: string | number;
    readableBoost?: string | number | Date;
    readableRecommend?: string | number | Date;
}
