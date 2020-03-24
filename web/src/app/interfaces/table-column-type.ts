export interface TableColumnType {
    header: string;
    value?: string;
    additional?: string;
    button?: string;
    icon?: string;
    calc?: (d?: any) => void;
    click?: (d?: any) => void;
}
