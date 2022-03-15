// DBのモデルはここに書く
interface WorkStamp {
    id: number;
    stamp_at: string;
    created_at: string;
    updated_at: string;
}

interface Work {
    id: number;
    user_id: number;
    work_type_id: number;
    created_at: string;
    updated_at: string;
    note: string | null;
    work_stamps: Array<WorkStamp>;
}
