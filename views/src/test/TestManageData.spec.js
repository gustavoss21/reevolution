let m = require('../utils/ManageData.ts');

describe('ManageData', () => {
    test('o arquivo deve ser um objeto com chave topics', () => {
        let mange = (new m.ManageData(data));
        mange.setData(data);
        mange.orderByTopic();
        expect(Object.keys(mange)[0]).toBe( "topics");
    })

    test('o arquivo deve ser um objeto com chave stages', () => {
        let mange = new m.ManageData(data);
        mange.setData(data);
        mange.orderByStage();
        expect(Object.keys(mange)[0]).toBe( "stages");
    })

    test('o arquivo deve ser um objeto com chave categories', () => {
        let mange = (new m.ManageData(data));
        mange.setData(data);
        mange.orderByCategory();
        expect(Object.keys(mange)[0]).toEqual( "categories");
    })

     test('deve retornar um score', () => {
        let inst = new m.ManageData();
        inst.setData(data)
        inst.generateAnalysisData();
        expect(inst.data.themes[0].score).toEqual("11.31");
    })

     test('deve setar os dados de um data chart', () => {
        let mange = new m.ManageData(data);
        mange.setData(data);

        mange.setChartData(data['themes'][0],1);
        expect(typeof mange['dataChart']).toEqual( "object");
    })

     test('deve setar os dados de um data chart', () => {
        let mange = new m.ManageData(data);
        mange.setData(data);
        mange.setChartData(data['themes'][0],1);
        let config = mange.configChart(1);
    
        expect(Object.keys(mange)[0]).toEqual( "categories");
    })

     test('deve retornar um data chart by theme', () => {
        let mange = new m.ManageData();
        mange.setData(data);
        mange.analysisDataTheme();
    
        expect(Object.keys(mange)[0]).toEqual( "categories");
    })

     test('deve retornar um data chart by topic', () => {
        let mange = new m.ManageData();
        mange.setData(data);
        mange.orderByTopic();
        mange.analysisDataTopic();
    
        expect(Object.keys(mange)[0]).toEqual( "categories");
    })

    test('deve retornar um data chart by stage', () => {
        let mange = new m.ManageData();
        mange.setData(data);
        mange.orderByStage();
        mange.analysisDataStage();
    
        expect(Object.keys(mange)[0]).toEqual( "categories");
    })
})


var data = {
    'themes': [
    {   
        "id": 1,
        "name": "Godói e Associados",
        "slug": "consequuntur-at-suscipit-nostrum-porro-et-officia-sed",
        "description": "Eos dolor neque iste officia quas. Amet ut itaque debitis reprehenderit. Velit sed cum voluptas mollitia tempore quo. Ut ipsam omnis autem qui dolorem vel.",
        "created_at": "2026-07-02 10:39:21",
        "updated_at": "2026-07-01 10:51:10",
        "topics": [
            {
                "id": 1,
                "name": "qui",
                "theme_id": 1,
                "slug": "dolor-odit-molestias-saepe-unde-quo-voluptas-illo-rerum",
                "description": "Sint minus provident et tempora. Adipisci maxime dolore veniam reprehenderit aut sint. Non unde delectus eos iusto. Sed aliquam voluptatem nisi non aperiam sunt porro nobis.",
                "created_at": "2026-07-02 10:39:21",
                "updated_at": "2026-07-02 10:39:21",
                "actived": 1,
                "can_explain": null,
                "study_time": null,
                "times_week_necessary": null,
                "domain_week": null,
                "started_study": null,
                "lot_to_discuss": null,
                "end_date": "2026-07-02 10:39:21",
                "stages": [
                    {
                        "id"            : 1,
                        "name"          : "Sr. Marco Ramos Fidalgo",
                        "topic_id"      : 6,
                        "slug"          : "Sr. Marco Ramos Fidalgo",
                        "description"   : null,
                        "created_at"    : "2026-07-02 10:39:24",
                        "updated_at"    : "2026-07-02 10:39:24",
                        "summary"       : null,
                        "synthesis"     : null,
                        "status"        : 0,
                        "domain_level"  : 2,
                        "attention"     : null,
                        "learning_stage": 2,
                        "priority"      : 1,
                        "partial_score" : 6,
                        "more_advanced" : 0
                    },
                    {
                        "id": 2,
                        "name": "Dr. Valentin Lourenço Filho",
                        "topic_id": 1,
                        "slug": "Dr. Valentin Lourenço Filho",
                        "description": null,
                        "created_at": "2026-07-02 10:39:21",
                        "updated_at": "2026-07-02 10:39:21",
                        "summary": null,
                        "synthesis": null,
                        "status": 1,
                        "domain_level": 0,
                        "attention": null,
                        "learning_stage": 2,
                        "priority": 1,
                        "partial_score": 11,
                        "more_advanced": 0
                    },
                   
                ]
            },
            {
                "id": 5,
                "name": "tenetur",
                "theme_id": 1,
                "slug": "quas-facere-magni-nostrum-fugiat-aliquid",
                "description": "Non eum sequi id veniam in quae consectetur. Sed veritatis provident temporibus qui.",
                "created_at": "2026-07-02 10:39:22",
                "updated_at": "2026-07-02 10:39:22",
                "actived": 1,
                "can_explain": null,
                "study_time": null,
                "times_week_necessary": null,
                "domain_week": null,
                "started_study": null,
                "lot_to_discuss": null,
                "end_date": "2026-07-02 10:39:22",
                "stages": [
                    {
                        "id"            : 1,
                        "name"          : "Sr. Marco Ramos Fidalgo",
                        "topic_id"      : 6,
                        "slug"          : "Sr. Marco Ramos Fidalgo",
                        "description"   : null,
                        "created_at"    : "2026-07-02 10:39:24",
                        "updated_at"    : "2026-07-02 10:39:24",
                        "summary"       : null,
                        "synthesis"     : null,
                        "status"        : 0,
                        "domain_level"  : 2,
                        "attention"     : null,
                        "learning_stage": 2,
                        "priority"      : 1,
                        "partial_score" : 6,
                        "more_advanced" : 0
                    },
                    {
                        "id": 22,
                        "name": "Dr. Filipe Quintana Uchoa Jr.",
                        "topic_id": 5,
                        "slug": "Dr. Filipe Quintana Uchoa Jr.",
                        "description": null,
                        "created_at": "2026-07-02 10:39:24",
                        "updated_at": "2026-07-02 10:39:24",
                        "summary": null,
                        "synthesis": null,
                        "status": -1,
                        "domain_level": 0,
                        "attention": null,
                        "learning_stage": 3,
                        "priority": 4,
                        "partial_score": 13,
                        "more_advanced": 0
                    }
                ]
            },
            {
                "id": 6,
                "name": "eligendi",
                "theme_id": 1,
                "slug": "tenetur-delectus-rem-dolore-assumenda-quidem-repellat-soluta",
                "description": "Porro qui laudantium animi fugit est iure ut. Odit corporis veniam eum enim maiores aut nisi. At culpa voluptatum aspernatur et.",
                "created_at": "2026-07-02 10:39:23",
                "updated_at": "2026-07-02 10:39:23",
                "actived": 1,
                "can_explain": null,
                "study_time": null,
                "times_week_necessary": null,
                "domain_week": null,
                "started_study": null,
                "lot_to_discuss": null,
                "end_date": "2026-07-02 10:39:23",
                "stages": [
                    {
                        "id": 28,
                        "name": "Kevin Ferreira Gil",
                        "topic_id": 6,
                        "slug": "Kevin Ferreira Gil",
                        "description": null,
                        "created_at": "2026-07-02 10:39:24",
                        "updated_at": "2026-07-02 10:39:24",
                        "summary": null,
                        "synthesis": null,
                        "status": -1,
                        "domain_level": 1,
                        "attention": null,
                        "learning_stage": 1,
                        "priority": 4,
                        "partial_score": 11,
                        "more_advanced": 0
                    },
                    {
                        "id": 1,
                        "name": "Sr. Marco Ramos Fidalgo",
                        "topic_id": 6,
                        "slug": "Sr. Marco Ramos Fidalgo",
                        "description": null,
                        "created_at": "2026-07-02 10:39:24",
                        "updated_at": "2026-07-02 10:39:24",
                        "summary": null,
                        "synthesis": null,
                        "status": 0,
                        "domain_level": 2,
                        "attention": null,
                        "learning_stage": 2,
                        "priority": 1,
                        "partial_score": 6,
                        "more_advanced": 0
                    },
                    {
                        "id": 40,
                        "name": "Dr. Thomas Ivan Ortiz",
                        "topic_id": 6,
                        "slug": "Dr. Thomas Ivan Ortiz",
                        "description": null,
                        "created_at": "2026-07-02 10:39:27",
                        "updated_at": "2026-07-02 10:39:27",
                        "summary": null,
                        "synthesis": null,
                        "status": 0,
                        "domain_level": 0,
                        "attention": null,
                        "learning_stage": 3,
                        "priority": 4,
                        "partial_score": 14,
                        "more_advanced": 0
                    }
                ]
            }
        ]
    },
]
}