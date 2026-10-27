export type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

export type Paginator<T> = {
    data: T[];
    current_page: number;
    first_page_url: string;
    from: number | null;
    last_page: number;
    last_page_url: string;
    links: PaginationLink[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number | null;
    total: number;
};

export type DataTablePage<T> = {
    data: T[];
    total: number;
    page: number;
    perPage: number;
};

export type DataTableQueryParams = {
    page?: number;
    per_page?: number;
    sort?: string;
    direction?: "asc" | "desc";
    search?: string;
};

export type DefaultPaginationSettings = {
    per_page: number;
    default_page: number;
};
