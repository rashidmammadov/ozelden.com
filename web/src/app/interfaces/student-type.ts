export interface StudentType {
    studentId: number;
    type: string;
    parentId: number;
    picture: string | null;
    name: string;
    surname: string;
    birthday: string;
    sex: string;
    file?: any;
    age?: number | string | Date;
}
