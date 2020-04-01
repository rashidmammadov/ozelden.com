export interface TableColumnType {
    header: string;
    value?: string;
    additional?: string;
    icon_button?: string;
    button?: boolean;
    icon?: string;
    class?: (d?: any) => void | string,
    calc?: (d?: any) => void;
    click?: (d?: any) => void;
}
