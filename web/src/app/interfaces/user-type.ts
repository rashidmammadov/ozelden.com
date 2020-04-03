export interface UserType {
    id: number;
    type: string;
    name: string;
    surname: string;
    birthday: number | string;
    email: string;
    identity_number: string | number;
    sex: string;
    state: number;
    remember_token: string;
    onesignal_device_id: string;
}
