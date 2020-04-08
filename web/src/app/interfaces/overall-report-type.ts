import { GenderDistributionType } from './gender-distribution-type';
import { PriceDistributionType } from './price-distribution-type';

export interface OverallReportType {
    total_count: number;
    average_price: number;
    gender_distribution: GenderDistributionType[];
    price_distribution: PriceDistributionType[];
}
