const createBooksPanel = () => {
    // Verificar si el store de autores existe
    const authorStore = Ext.getStore("authorStore");
    if(!authorStore){
        console.warn("authorStore no encontrado. Cargue el archivo author.js");
    }
    
    // Definir el modelo solo una vez
    if (!Ext.ClassManager.isCreated('App.Model.Book')) {
        Ext.define("App.Model.Book", {
            extend: 'Ext.data.Model',
            fields: [
                {name: "id", type: "int"},
                {name: "title", type: "string"},
                {name: "description", type: "string"},
                {name: "publication_date", type: "date", dateFormat: 'Y-m-d'},
                {name: "isbn", type: "string"},
                {name: "gender", type: "string"},
                {name: "edition", type: "int"},
                
                // Campos para información del autor - corregir mapeo
                {name: "author_id", mapping: 'author.id', type: "int"},
                {name: "author_first_name", mapping: 'author.first_name', type: "string"},
                {name: "author_last_name", mapping: 'author.last_name', type: "string"},
                
                // Campo calculado para el nombre completo del autor
                {
                    name: "authorName", 
                    convert: function(value, record) {
                        const firstName = record.get('author_first_name');
                        const lastName = record.get('author_last_name');
                        
                        // Si tenemos datos del autor, devolver nombre completo
                        if (firstName && lastName) {
                            return `${firstName} ${lastName}`;
                        }
                        // Si solo tenemos firstName
                        else if (firstName) {
                            return firstName;
                        }
                        // Si solo tenemos lastName
                        else if (lastName) {
                            return lastName;
                        }
                        // Si no hay datos del autor
                        else {
                            return "N/A";
                        }
                    }
                }
            ]
        });
    }

    const bookStore = Ext.create("Ext.data.Store", {
        storeId: "bookStore",
        model: "App.Model.Book",
        proxy: {
            type: "rest",
            url: "api/book.php",
            reader: {
                type: "json", 
                rootProperty: "" // Ajustar según tu API
            },
            writer: {type: "json", writeAllFields: true},
            appendId: false
        },
        autoLoad: true,
        autoSync: false,
        // Agregar listener para debug
        listeners: {
            load: function(store, records, success) {
                console.log('Books store loaded:', success);
                console.log('First record data:', records[0] ? records[0].getData() : 'No records');
                // Mostrar estructura del primer registro para debug
                if (records.length > 0) {
                    console.log('Raw data del primer libro:', records[0].raw);
                }
            }
        }
    });

    const grid = Ext.create('Ext.grid.Panel', {
        title: "Books",
        store: bookStore,
        itemId: "bookPanel",
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
                text: "Author",
                flex: 1,
                sortable: true,
                dataIndex: "authorName"
            },
            {
                text: "ISBN",
                flex: 1,
                sortable: false,
                dataIndex: "isbn"
            },
            {
                text: "Gender",
                width: 120,
                sortable: false,
                dataIndex: "gender"
            },
            {
                text: "Edition",
                width: 80,
                sortable: false,
                dataIndex: "edition"
            }
        ],
        tbar: 
        [
            {
                text:'Add'
            },
            {
                text:'Edit'
            },
            {
                text:'Delete'
            }
        ]
    });
    
    return grid;
};

// Hacer la función disponible globalmente
window.createBooksPanel = createBooksPanel;