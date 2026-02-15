
export type ResponseDataType = {
    status : number;
    data   : any;
    message: string;
    error  : Object;
}

export type DataStatistic = {
	without_study: number;
	with_study: number;
	time_without_study: string;
	count_events_weekly: number;
	averange_time_without_study: number;
	more_time_without_study: number;
	total_quantity_each_tatus: string;
	media_averange: {point_average?: number; amount_event?: number};
};