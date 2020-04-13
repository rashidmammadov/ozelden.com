import { CourseType } from './course-type';
import { FacilityType } from './facility-type';
import { LocationType } from './location-type';
import { RegionType } from './region-type';

export interface SuitabilityType {
    course_type: CourseType;
    facility: FacilityType;
    location: LocationType;
    regions: RegionType[];
}
