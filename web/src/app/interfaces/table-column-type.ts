export interface TableColumnType {
    header: string;
    value: string;
    button?: string;
    icon?: string;
    click?: (d?: any) => void;
}
