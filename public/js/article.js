const createArticlesPanel = () => {
    const authorStore = Ext.getStore("authorStore");
    if(!authorStore){
        console.warn("authorStore no encontrado. Cargue el archivo author.js");
    }
    if (!Ext.ClassManager.isCreated('App.Model.Article')) {
        Ext.define("App.Model.Article", {
            extend: 'Ext.data.Model',
            fields: [
                {name: "id", type: "int"},
                {name: "title", type: "string"},
                {name: "description", type: "string"},
                {name: "publication_date", type: "date", dateFormat: 'Y-m-d'},
                {name: "doi", type: "string"},
                {name: "journal", type: "string"},
                {name: "author_id", mapping: 'author.id', type: "int"},
                {
                    name: "authorName", 
                    convert: function(value, record) {
                        const a = record.get('author');
                        return a ? `${a.first_name} ${a.last_name}` : '';

                    }
                }
            ]
        });
    }

    const articleStore = Ext.create("Ext.data.Store", {
        storeId: "articleStore",
        model: "App.Model.Article",
        proxy: {
            type: "rest",
            url: "api/article.php",
            reader: {
                type: "json", 
                rootProperty: ""
            },
            writer: {type: "json", writeAllFields: true},
            appendId: false
        },
        autoLoad: true,
        autoSync: false,
        listeners: {
            load: function(store, records, success) {
                console.log('Articles store loaded:', success);
                console.log('First record data:', records[0] ? records[0].getData() : 'No records');
                if (records.length > 0) {
                    console.log('Raw data del primer artículo:', records[0].raw);
                }
            }
        }
    });

    const grid = Ext.create('Ext.grid.Panel', {
        title: "Articles",
        store: articleStore,
        itemId: "articlePanel",
        layout: "fit",
        columns: [
            {
                text: "Title",
                flex: 1,
                sortable: true,
                dataIndex: "title"
            },
            {
                text: "Description",
                flex: 2,
                sortable: false,
                dataIndex: "description"
            },
            {
                text: "Publication Date",
                flex: 1,
                sortable: true,
                dataIndex: "publication_date",
                renderer: function(value) {
                    if (value instanceof Date) {
                        return Ext.util.Format.date(value, 'Y-m-d');
                    }
                    return value;
                }
            },
            {
                text: "DOI",
                flex: 1,
                sortable: false,
                dataIndex: "doi"
            },
            {
                text: "Journal",
                flex: 1,
                sortable: false,
                dataIndex: "journal"
            },
            {
                text: "Author",
                flex: 1,
                sortable: true,
                dataIndex: "authorName"
            }
        ],
        tbar: [
            { text: 'Add' },
            { text: 'Edit' },
            { text: 'Delete' }
        ]
    });

    return grid;
};

// Hacer la función disponible globalmente
window.createArticlesPanel = createArticlesPanel;
