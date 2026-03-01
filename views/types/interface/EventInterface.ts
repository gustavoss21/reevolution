
export interface Stage {
    name        : string;
    priority_op : number;
    domain_level: string;
    status      : string;
    score       : number;
    upadated_at : string;
}

export interface Topic {
    stage: Stage;
}

export type EventType =  {
    id: number;
    topic: Topic;
}

export interface WithoutStudyEvent {
    id: number;
    name: string;
    priority: number;
    domain_level: string;
    status: string;
    score: number;
    upadated_at: string;
}

export interface PriorityEvent {
    id: number;
    name: string;
    priority_op: number;
    domain_level: string;
    status: string;
    score: number;
    upadated_at: string;
}