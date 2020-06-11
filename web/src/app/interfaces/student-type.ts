import { ParentType } from './parent-type';

export interface StudentType {
    student_id: number;
    type: string;
    parent_id: number;
    picture: string | null;
    name: string;
    surname: string;
    birthday: string;
    sex: string;
    file?: any;
    age?: number | string | Date;
    parent?: ParentType;
}
